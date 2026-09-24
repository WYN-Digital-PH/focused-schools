<?php
/**
 * Buzzsprout RSS reader for the Podcast module: newest-episode data.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Podcast;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Buzzsprout_Feed.
 *
 * Reads the show's public Buzzsprout RSS feed and keeps the newest episode
 * cached. Follows the YouTube helper's rules: the theme-facing read is
 * cache-only and never makes an HTTP request during a page load. When the
 * short-lived transient has expired, a single wp-cron event refreshes it in
 * the background while the last-known-good copy keeps serving. A failed
 * fetch never overwrites that copy.
 */
class Buzzsprout_Feed {

	/**
	 * Transient holding the fresh episode payload.
	 *
	 * @var string
	 */
	const CACHE_TRANSIENT = 'focused_schools_podcast_latest_episode';

	/**
	 * Option holding the last successful payload (autoload=no).
	 *
	 * @var string
	 */
	const LAST_GOOD_OPTION = 'focused_schools_podcast_latest_episode_last_good';

	/**
	 * Cron hook that refreshes the cache in the background.
	 *
	 * @var string
	 */
	const CRON_HOOK = 'focused_schools_podcast_refresh_latest_episode';

	/**
	 * Seconds the transient stays fresh.
	 *
	 * @var int
	 */
	const CACHE_TTL = 3 * HOUR_IN_SECONDS;

	/**
	 * Feed base URL; the numeric podcast ID and ".rss" are appended.
	 *
	 * @var string
	 */
	const FEED_BASE = 'https://feeds.buzzsprout.com/';

	/**
	 * Register the background refresh handler.
	 *
	 * @return void
	 */
	public static function register() {
		add_action( self::CRON_HOOK, array( __CLASS__, 'refresh' ) );
	}

	/**
	 * The configured Buzzsprout podcast ID (digits only), or ''.
	 *
	 * @return string
	 */
	private static function podcast_id() {
		if ( ! function_exists( 'focused_schools_get_setting' ) ) {
			return '';
		}

		return preg_replace( '/[^0-9]/', '', (string) focused_schools_get_setting( 'podcast_buzzsprout_id', '' ) );
	}

	/**
	 * Theme-facing read: the newest episode, cache-only.
	 *
	 * Returns the fresh transient, else the last-known-good payload while
	 * scheduling a background refresh, else null (nothing fetched yet, or no
	 * Buzzsprout ID configured). A payload saved for a different podcast ID is
	 * ignored.
	 *
	 * @return array{podcast_id:string,episode_id:string,title:string,summary:string,duration:string,episode_number:string,publish_date:string,image_url:string}|null
	 */
	public static function get_latest() {
		$podcast_id = self::podcast_id();

		if ( '' === $podcast_id ) {
			return null;
		}

		$cached = get_transient( self::CACHE_TRANSIENT );
		if ( is_array( $cached ) && isset( $cached['podcast_id'] ) && $podcast_id === $cached['podcast_id'] ) {
			return $cached;
		}

		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_single_event( time(), self::CRON_HOOK );
		}

		$last_good = get_option( self::LAST_GOOD_OPTION, array() );

		if ( isset( $last_good['episode']['podcast_id'] ) && $podcast_id === $last_good['episode']['podcast_id'] ) {
			return $last_good['episode'];
		}

		return null;
	}

	/**
	 * Fetch the feed and, on success, update both caches.
	 *
	 * @return array<string, string>|WP_Error
	 */
	public static function refresh() {
		$podcast_id = self::podcast_id();

		if ( '' === $podcast_id ) {
			return new WP_Error( 'fs_podcast_missing_buzzsprout_id', __( 'No Buzzsprout podcast ID is configured.', 'focused-schools-core' ) );
		}

		$response = wp_safe_remote_get(
			self::FEED_BASE . rawurlencode( $podcast_id ) . '.rss',
			array(
				'timeout'             => 10,
				'limit_response_size' => 2 * MB_IN_BYTES,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) {
			return new WP_Error(
				'fs_podcast_feed_error',
				sprintf(
					/* translators: %d: HTTP response code. */
					__( 'Buzzsprout feed returned HTTP %d.', 'focused-schools-core' ),
					$code
				)
			);
		}

		$episode = self::parse_latest( wp_remote_retrieve_body( $response ), $podcast_id );

		if ( is_wp_error( $episode ) ) {
			return $episode;
		}

		set_transient( self::CACHE_TRANSIENT, $episode, self::CACHE_TTL );
		update_option(
			self::LAST_GOOD_OPTION,
			array(
				'episode'    => $episode,
				'fetched_at' => time(),
			),
			false
		);

		return $episode;
	}

	/**
	 * Parse the newest <item> out of a Buzzsprout RSS document.
	 *
	 * @param string $xml        Raw feed XML.
	 * @param string $podcast_id Numeric podcast ID the feed belongs to.
	 * @return array<string, string>|WP_Error
	 */
	public static function parse_latest( $xml, $podcast_id ) {
		$previous = libxml_use_internal_errors( true );
		$feed     = simplexml_load_string( (string) $xml, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA );
		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		if ( false === $feed || ! isset( $feed->channel->item[0] ) ) {
			return new WP_Error( 'fs_podcast_feed_parse', __( 'The Buzzsprout feed could not be read or has no episodes.', 'focused-schools-core' ) );
		}

		$item   = $feed->channel->item[0];
		$itunes = $item->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' );

		$episode_id = '';
		if ( preg_match( '/(\d{4,})/', (string) $item->guid, $match ) ) {
			$episode_id = $match[1];
		} elseif ( isset( $item->enclosure['url'] ) && preg_match( '#/episodes/(\d+)#', (string) $item->enclosure['url'], $match ) ) {
			$episode_id = $match[1];
		}

		$title = trim( wp_strip_all_tags( (string) $item->title ) );

		if ( '' === $episode_id || '' === $title ) {
			return new WP_Error( 'fs_podcast_feed_parse', __( 'The newest Buzzsprout episode is missing an ID or title.', 'focused-schools-core' ) );
		}

		$summary = trim( wp_strip_all_tags( '' !== (string) $itunes->summary ? (string) $itunes->summary : (string) $item->description ) );

		$image_url = '';
		if ( isset( $itunes->image ) && isset( $itunes->image->attributes()->href ) ) {
			$image_url = esc_url_raw( (string) $itunes->image->attributes()->href );
		} elseif ( isset( $feed->channel->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' )->image ) ) {
			$image_url = esc_url_raw( (string) $feed->channel->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' )->image->attributes()->href );
		}

		$timestamp = strtotime( (string) $item->{'pubDate'} );

		return array(
			'podcast_id'     => (string) $podcast_id,
			'episode_id'     => $episode_id,
			'title'          => $title,
			'summary'        => wp_trim_words( $summary, 48 ),
			'duration'       => self::format_duration( (string) $itunes->duration ),
			'episode_number' => preg_replace( '/[^0-9]/', '', (string) $itunes->episode ),
			'publish_date'   => $timestamp ? gmdate( 'c', $timestamp ) : '',
			'image_url'      => $image_url,
		);
	}

	/**
	 * Normalize an iTunes duration (seconds, mm:ss or hh:mm:ss) to m:ss / h:mm:ss.
	 *
	 * @param string $raw Raw itunes:duration value.
	 * @return string Empty string when the value can't be read.
	 */
	private static function format_duration( $raw ) {
		$raw = trim( $raw );

		if ( '' === $raw ) {
			return '';
		}

		if ( ctype_digit( $raw ) ) {
			$seconds = (int) $raw;
		} elseif ( preg_match( '/^(?:(\d+):)?(\d{1,2}):(\d{2})$/', $raw, $match ) ) {
			$seconds = ( (int) $match[1] * 3600 ) + ( (int) $match[2] * 60 ) + (int) $match[3];
		} else {
			return '';
		}

		$hours   = intdiv( $seconds, 3600 );
		$minutes = intdiv( $seconds % 3600, 60 );
		$secs    = $seconds % 60;

		return $hours > 0
			? sprintf( '%d:%02d:%02d', $hours, $minutes, $secs )
			: sprintf( '%d:%02d', $minutes, $secs );
	}
}
