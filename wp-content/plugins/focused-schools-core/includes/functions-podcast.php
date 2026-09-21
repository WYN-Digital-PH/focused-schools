<?php
/**
 * Theme-facing helper functions — YouTube Podcast playlist integration.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( __NAMESPACE__ . '\\get_podcast_youtube_videos' ) ) {
	/**
	 * Get the normalized, cached YouTube playlist videos for the Podcast page.
	 *
	 * Cache-only: never performs a live HTTP request on a standard page
	 * load. Serves the transient cache while fresh, then falls back to the
	 * last-known-good persisted payload once it expires (see
	 * Modules\Podcast::get_cached_videos()). Returns an empty array when the
	 * feature flag is off or nothing has ever been fetched successfully —
	 * callers should treat that the same as "no episodes".
	 *
	 * Each video: ['video_id' => string, 'title' => string,
	 * 'thumbnail_url' => string, 'publish_date' => string (ISO 8601)].
	 *
	 * @return array<int, array{video_id:string,title:string,thumbnail_url:string,publish_date:string}>
	 */
	function get_podcast_youtube_videos() {
		return Modules\Podcast::get_cached_videos();
	}
}
