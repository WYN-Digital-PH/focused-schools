<?php
/**
 * YouTube playlist service for the Podcast page.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Integrations;

use FocusedSchoolsCore\Modules\Podcast\Fields;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Youtube_Playlist.
 *
 * Fetches, normalizes and caches the videos of one YouTube playlist. Returns
 * data only; it never renders HTML.
 *
 * API key: read from the FOCUSED_SCHOOLS_YOUTUBE_API_KEY constant
 * (wp-config.php) or, failing that, the environment variable of the same
 * name. It is never stored in an option, a settings field or the repository.
 *
 * Caching:
 * - A transient holds the fresh payload for the configured cache duration
 *   (default 6 hours).
 * - A persistent option holds the last successful payload as a fallback: a
 *   failed fetch (bad key, quota exhausted, network error) never overwrites it,
 *   so stale-but-good data keeps serving.
 *
 * Frontend rule: get_videos() only reads those two caches and makes no HTTP
 * request. When the transient has expired it schedules one background wp-cron
 * event that calls refresh(); a failed refresh sets a short back-off so the
 * event is not re-queued on every page view.
 */
class Youtube_Playlist {

	/**
	 * Transient holding the fresh payload.
	 *
	 * @var string
	 */
	const CACHE_TRANSIENT = 'focused_schools_podcast_videos_cache';

	/**
	 * Option holding the last successful payload (autoload=no).
	 *
	 * @var string
	 */
	const LAST_GOOD_OPTION = 'focused_schools_podcast_videos_last_good';

	/**
	 * Transient set after a failed refresh; while present no background refresh is queued.
	 *
	 * @var string
	 */
	const BACKOFF_TRANSIENT = 'focused_schools_podcast_videos_backoff';

	/**
	 * Cron hook that refreshes the cache in the background.
	 *
	 * @var string
	 */
	const CRON_HOOK = 'focused_schools_refresh_podcast_playlist';

	/**
	 * Name of the API key constant / environment variable.
	 *
	 * @var string
	 */
	const KEY_NAME = 'FOCUSED_SCHOOLS_YOUTUBE_API_KEY';

	/**
	 * YouTube Data API v3 playlistItems endpoint.
	 *
	 * @var string
	 */
	const API_ENDPOINT = 'https://www.googleapis.com/youtube/v3/playlistItems';

	/**
	 * Seconds to wait after a failed refresh before queueing another.
	 *
	 * @var int
	 */
	const BACKOFF_SECONDS = 1800;

	/**
	 * Register the background refresh handler.
	 *
	 * @return void
	 */
	public static function register() {
		add_action( self::CRON_HOOK, array( __CLASS__, 'refresh' ) );
	}

	/**
	 * The YouTube API key, or '' when none is configured.
	 *
	 * @return string
	 */
	public static function get_api_key() {
		$key = defined( self::KEY_NAME ) ? (string) constant( self::KEY_NAME ) : (string) getenv( self::KEY_NAME );

		return trim( $key );
	}

	/**
	 * Saved Podcast settings merged with defaults.
	 *
	 * @return array{enabled:bool,playlist_id:string,max_videos:int,cache_duration:int}
	 */
	public static function get_settings() {
		return wp_parse_args( get_option( 'focused_schools_podcast_settings', array() ), Fields::defaults() );
	}

	/**
	 * Theme-facing read: the normalized video list, cache-only.
	 *
	 * Makes no HTTP request. Returns the fresh transient, else the
	 * last-known-good payload (queueing a background refresh), else array().
	 * Returns array() when the feature flag is off.
	 *
	 * Each video: video_id, title, thumbnail_url, published_at (ISO 8601).
	 *
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,published_at:string}>
	 */
	public static function get_videos() {
		$settings = self::get_settings();

		if ( empty( $settings['enabled'] ) ) {
			return array();
		}

		$cached = get_transient( self::CACHE_TRANSIENT );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		if (
			'' !== self::get_api_key()
			&& '' !== trim( (string) $settings['playlist_id'] )
			&& false === get_transient( self::BACKOFF_TRANSIENT )
			&& ! wp_next_scheduled( self::CRON_HOOK )
		) {
			wp_schedule_single_event( time(), self::CRON_HOOK );
		}

		$last_good = get_option( self::LAST_GOOD_OPTION, array() );

		return isset( $last_good['videos'] ) && is_array( $last_good['videos'] ) ? $last_good['videos'] : array();
	}

	/**
	 * Unix time of the last successful fetch, or 0.
	 *
	 * @return int
	 */
	public static function get_last_fetched_at() {
		$last_good = get_option( self::LAST_GOOD_OPTION, array() );

		return isset( $last_good['fetched_at'] ) ? (int) $last_good['fetched_at'] : 0;
	}

	/**
	 * Drop the fresh transient and the back-off. The last-known-good option is kept.
	 *
	 * @return void
	 */
	public static function purge_cache() {
		delete_transient( self::CACHE_TRANSIENT );
		delete_transient( self::BACKOFF_TRANSIENT );
	}

	/**
	 * Fetch the playlist live and, on success, update both caches.
	 *
	 * The only method that calls the YouTube API. Called by the cron event and
	 * by the admin "Refresh Now" action, never during a frontend request. On
	 * failure both caches are left as they were and a back-off is set.
	 *
	 * @return array<int, array<string, string>>|WP_Error
	 */
	public static function refresh() {
		$settings = self::get_settings();
		$result   = self::fetch( (string) $settings['playlist_id'], (int) $settings['max_videos'] );

		if ( is_wp_error( $result ) ) {
			set_transient( self::BACKOFF_TRANSIENT, 1, self::BACKOFF_SECONDS );

			return $result;
		}

		delete_transient( self::BACKOFF_TRANSIENT );
		set_transient( self::CACHE_TRANSIENT, $result, max( 60, (int) $settings['cache_duration'] ) );
		update_option(
			self::LAST_GOOD_OPTION,
			array(
				'videos'     => $result,
				'fetched_at' => time(),
			),
			false
		);

		return $result;
	}

	/**
	 * Call the API and normalize the response.
	 *
	 * @param string $playlist_id YouTube playlist ID.
	 * @param int    $max_videos  1-50.
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,published_at:string}>|WP_Error
	 */
	private static function fetch( $playlist_id, $max_videos ) {
		$key = self::get_api_key();

		if ( '' === $key ) {
			return new WP_Error(
				'fs_youtube_missing_key',
				sprintf(
					/* translators: %s: constant name. */
					__( '%s is not defined in wp-config.php or the environment.', 'focused-schools-core' ),
					self::KEY_NAME
				)
			);
		}

		$playlist_id = trim( $playlist_id );
		if ( '' === $playlist_id ) {
			return new WP_Error( 'fs_youtube_missing_playlist', __( 'No YouTube playlist ID is configured.', 'focused-schools-core' ) );
		}

		$url = add_query_arg(
			array(
				'part'       => 'snippet',
				'playlistId' => $playlist_id,
				'maxResults' => max( 1, min( 50, $max_videos ) ),
				'key'        => $key,
			),
			self::API_ENDPOINT
		);

		$response = wp_remote_get( $url, array( 'timeout' => 15 ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code < 200 || $code >= 300 ) {
			return self::error_from_response( $code, $body );
		}

		if ( ! is_array( $body ) || ! isset( $body['items'] ) || ! is_array( $body['items'] ) ) {
			return new WP_Error( 'fs_youtube_invalid_response', __( 'Unexpected YouTube API response shape.', 'focused-schools-core' ) );
		}

		return self::normalize_items( $body['items'] );
	}

	/**
	 * Turn a non-2xx API response into a WP_Error, naming quota and key problems.
	 *
	 * @param int   $code HTTP status.
	 * @param mixed $body Decoded JSON body, if any.
	 * @return WP_Error
	 */
	private static function error_from_response( $code, $body ) {
		$reason = '';

		if ( is_array( $body ) && isset( $body['error']['errors'][0]['reason'] ) ) {
			$reason = (string) $body['error']['errors'][0]['reason'];
		}

		if ( in_array( $reason, array( 'quotaExceeded', 'dailyLimitExceeded', 'rateLimitExceeded' ), true ) ) {
			return new WP_Error( 'fs_youtube_quota', __( 'YouTube API quota exceeded.', 'focused-schools-core' ), array( 'status' => $code ) );
		}

		$key_rejected = in_array( $reason, array( 'keyInvalid', 'keyExpired', 'ipRefererBlocked', 'accessNotConfigured' ), true )
			|| ( 400 === $code && false !== stripos( (string) wp_json_encode( $body ), 'API key' ) );

		if ( $key_rejected ) {
			return new WP_Error( 'fs_youtube_bad_key', __( 'YouTube rejected the API key.', 'focused-schools-core' ), array( 'status' => $code ) );
		}

		if ( 404 === $code ) {
			return new WP_Error( 'fs_youtube_playlist_not_found', __( 'YouTube playlist not found or not public.', 'focused-schools-core' ), array( 'status' => $code ) );
		}

		return new WP_Error(
			'fs_youtube_api_error',
			sprintf(
				/* translators: %d: HTTP response code. */
				__( 'YouTube API returned HTTP %d.', 'focused-schools-core' ),
				$code
			),
			array( 'status' => $code )
		);
	}

	/**
	 * Normalize raw playlistItems into video_id / title / thumbnail_url / published_at.
	 *
	 * Skips items without a video ID and placeholders for private or deleted videos.
	 *
	 * @param array<int, array<string, mixed>> $items Raw `items` array.
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,published_at:string}>
	 */
	private static function normalize_items( array $items ) {
		$videos = array();

		foreach ( $items as $item ) {
			$snippet  = isset( $item['snippet'] ) && is_array( $item['snippet'] ) ? $item['snippet'] : array();
			$video_id = isset( $snippet['resourceId']['videoId'] ) ? (string) $snippet['resourceId']['videoId'] : '';
			$title    = isset( $snippet['title'] ) ? sanitize_text_field( $snippet['title'] ) : '';

			if ( '' === $video_id || in_array( $title, array( 'Private video', 'Deleted video' ), true ) ) {
				continue;
			}

			$thumbnail_url = '';
			if ( isset( $snippet['thumbnails'] ) && is_array( $snippet['thumbnails'] ) ) {
				foreach ( array( 'maxres', 'standard', 'high', 'medium', 'default' ) as $size ) {
					if ( ! empty( $snippet['thumbnails'][ $size ]['url'] ) ) {
						$thumbnail_url = (string) $snippet['thumbnails'][ $size ]['url'];
						break;
					}
				}
			}

			$videos[] = array(
				'video_id'      => sanitize_text_field( $video_id ),
				'title'         => $title,
				'thumbnail_url' => esc_url_raw( $thumbnail_url ),
				'published_at'  => isset( $snippet['publishedAt'] ) ? sanitize_text_field( $snippet['publishedAt'] ) : '',
			);
		}

		return $videos;
	}
}
