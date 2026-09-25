<?php
/**
 * Server render for focused-schools/podcast-subscribe.
 *
 * The tiles come from the podcast URLs in Site Settings, so a platform
 * appears by filling its field in and disappears by clearing it.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_get = function ( $key ) {
	return function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( $key ) : '';
};

get_template_part(
	'template-parts/components/podcast-subscribe',
	null,
	array(
		'label' => isset( $attributes['label'] ) ? $attributes['label'] : '',
		'links' => array(
			array(
				'label' => __( 'Apple Podcasts', 'focused-schools' ),
				'url'   => $fs_get( 'podcast_apple_url' ),
			),
			array(
				'label' => __( 'Spotify', 'focused-schools' ),
				'url'   => $fs_get( 'podcast_spotify_url' ),
			),
			array(
				'label' => __( 'Buzzsprout', 'focused-schools' ),
				'url'   => $fs_get( 'podcast_buzzsprout_url' ),
			),
			array(
				'label' => __( 'YouTube', 'focused-schools' ),
				'url'   => $fs_get( 'youtube_url' ),
			),
		),
	)
);
