<?php
/**
 * Field schema for the Podcast (YouTube Playlist) module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Podcast;

defined( 'ABSPATH' ) || exit;

/**
 * Fields.
 *
 * Single source of truth for the Podcast settings field list. Note: the
 * YouTube API key itself is never a field here — it's read directly from the
 * FS_YOUTUBE_API_KEY constant (defined in wp-config.php), never stored in
 * this plugin's options.
 */
class Fields {

	/**
	 * Field definitions, in display order.
	 *
	 * @return array<string, array{label:string,type:string,description:string}>
	 */
	public static function all() {
		return array(
			'enabled'        => array(
				'label'       => __( 'Enable YouTube Playlist Integration', 'focused-schools-core' ),
				'type'        => 'checkbox',
				'description' => __( 'When disabled, no data is fetched from YouTube and the theme helper always returns an empty list.', 'focused-schools-core' ),
			),
			'playlist_id'    => array(
				'label'       => __( 'YouTube Playlist ID', 'focused-schools-core' ),
				'type'        => 'text',
				'description' => __( 'e.g. PLxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx (found in the playlist\'s URL).', 'focused-schools-core' ),
			),
			'max_videos'     => array(
				'label'       => __( 'Max Videos', 'focused-schools-core' ),
				'type'        => 'number',
				'description' => __( 'Maximum number of videos to fetch per refresh (1-50).', 'focused-schools-core' ),
			),
			'cache_duration' => array(
				'label'       => __( 'Cache Duration (seconds)', 'focused-schools-core' ),
				'type'        => 'number',
				'description' => __( 'How long a fetched result is served before it is considered stale. Default 21600 (6 hours). A manual refresh always fetches live regardless of this value.', 'focused-schools-core' ),
			),
		);
	}

	/**
	 * Default values for every known field.
	 *
	 * @return array{enabled:bool,playlist_id:string,max_videos:int,cache_duration:int}
	 */
	public static function defaults() {
		return array(
			'enabled'        => false,
			'playlist_id'    => '',
			'max_videos'     => 12,
			'cache_duration' => 21600,
		);
	}

	/**
	 * Sanitize a raw settings submission against the known field schema.
	 *
	 * @param mixed $input Raw value from the settings form.
	 * @return array{enabled:bool,playlist_id:string,max_videos:int,cache_duration:int}
	 */
	public static function sanitize( $input ) {
		$input    = is_array( $input ) ? $input : array();
		$defaults = self::defaults();

		return array(
			'enabled'        => ! empty( $input['enabled'] ),
			'playlist_id'    => isset( $input['playlist_id'] ) ? sanitize_text_field( $input['playlist_id'] ) : $defaults['playlist_id'],
			'max_videos'     => isset( $input['max_videos'] ) ? max( 1, min( 50, (int) $input['max_videos'] ) ) : $defaults['max_videos'],
			'cache_duration' => isset( $input['cache_duration'] ) ? max( 60, (int) $input['cache_duration'] ) : $defaults['cache_duration'],
		);
	}
}
