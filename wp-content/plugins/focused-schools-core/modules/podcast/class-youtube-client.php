<?php
/**
 * YouTube Data API v3 client for the Podcast module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Podcast;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Youtube_Client.
 *
 * Pure fetch + normalize: no caching, no options/transients read or written
 * here (see Podcast::refresh_cache()). Never called on a standard frontend
 * page load — only from the admin manual-refresh action.
 */
class Youtube_Client {

	/**
	 * YouTube Data API v3 playlistItems endpoint.
	 *
	 * @var string
	 */
	const API_ENDPOINT = 'https://www.googleapis.com/youtube/v3/playlistItems';

	/**
	 * Fetch and normalize the videos in a YouTube playlist.
	 *
	 * @param string $playlist_id YouTube playlist ID.
	 * @param int    $max_videos  Maximum number of videos to return (1-50).
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,publish_date:string}>|WP_Error
	 */
	public static function fetch_playlist_videos( $playlist_id, $max_videos ) {
		if ( ! defined( 'FS_YOUTUBE_API_KEY' ) || '' === trim( (string) FS_YOUTUBE_API_KEY ) ) {
			return new WP_Error(
				'fs_podcast_missing_api_key',
				__( 'FS_YOUTUBE_API_KEY is not defined in wp-config.php.', 'focused-schools-core' )
			);
		}

		$playlist_id = trim( (string) $playlist_id );
		if ( '' === $playlist_id ) {
			return new WP_Error(
				'fs_podcast_missing_playlist_id',
				__( 'No YouTube playlist ID is configured.', 'focused-schools-core' )
			);
		}

		$max_videos = max( 1, min( 50, (int) $max_videos ) );

		$url = add_query_arg(
			array(
				'part'       => 'snippet',
				'playlistId' => rawurlencode( $playlist_id ),
				'maxResults' => $max_videos,
				'key'        => rawurlencode( FS_YOUTUBE_API_KEY ),
			),
			self::API_ENDPOINT
		);

		$response = wp_remote_get( $url, array( 'timeout' => 15 ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) {
			return new WP_Error(
				'fs_podcast_api_error',
				sprintf(
					/* translators: %d: HTTP response code. */
					__( 'YouTube API returned HTTP %d.', 'focused-schools-core' ),
					$code
				),
				array( 'status' => $code )
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $body ) || ! isset( $body['items'] ) || ! is_array( $body['items'] ) ) {
			return new WP_Error(
				'fs_podcast_invalid_response',
				__( 'Unexpected YouTube API response shape.', 'focused-schools-core' )
			);
		}

		return self::normalize_items( $body['items'] );
	}

	/**
	 * Normalize raw playlistItems API items into a stable shape.
	 *
	 * @param array<int, array<string, mixed>> $items Raw `items` array from the API response.
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,publish_date:string}>
	 */
	private static function normalize_items( array $items ) {
		$videos = array();

		foreach ( $items as $item ) {
			$snippet  = isset( $item['snippet'] ) && is_array( $item['snippet'] ) ? $item['snippet'] : array();
			$video_id = isset( $snippet['resourceId']['videoId'] ) ? (string) $snippet['resourceId']['videoId'] : '';

			if ( '' === $video_id ) {
				continue;
			}

			$thumbnail_url = '';
			if ( isset( $snippet['thumbnails'] ) && is_array( $snippet['thumbnails'] ) ) {
				foreach ( array( 'high', 'medium', 'default' ) as $size ) {
					if ( ! empty( $snippet['thumbnails'][ $size ]['url'] ) ) {
						$thumbnail_url = (string) $snippet['thumbnails'][ $size ]['url'];
						break;
					}
				}
			}

			$videos[] = array(
				'video_id'      => sanitize_text_field( $video_id ),
				'title'         => isset( $snippet['title'] ) ? sanitize_text_field( $snippet['title'] ) : '',
				'thumbnail_url' => esc_url_raw( $thumbnail_url ),
				'publish_date'  => isset( $snippet['publishedAt'] ) ? sanitize_text_field( $snippet['publishedAt'] ) : '',
			);
		}

		return $videos;
	}
}
