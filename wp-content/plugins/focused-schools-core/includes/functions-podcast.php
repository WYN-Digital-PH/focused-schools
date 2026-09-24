<?php
/**
 * Theme-facing helper functions — Buzzsprout latest episode. The YouTube playlist
 * helper, focused_schools_get_podcast_playlist(), lives in functions.php.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( __NAMESPACE__ . '\get_podcast_latest_episode' ) ) {
	/**
	 * Get the newest Buzzsprout episode for the Podcast page's Latest Episode panel.
	 *
	 * Cache-only, like the YouTube helper: never makes an HTTP request on a
	 * page load. Returns null when no Buzzsprout ID is set or nothing has been
	 * fetched yet (a background refresh is scheduled), which callers should
	 * treat as "hide the panel".
	 *
	 * @return array{podcast_id:string,episode_id:string,title:string,summary:string,duration:string,episode_number:string,publish_date:string,image_url:string}|null
	 */
	function get_podcast_latest_episode() {
		return Modules\Podcast\Buzzsprout_Feed::get_latest();
	}
}
