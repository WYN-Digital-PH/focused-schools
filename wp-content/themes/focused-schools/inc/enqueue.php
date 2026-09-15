<?php
/**
 * Frontend and editor asset enqueuing.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the main stylesheet.
 *
 * @return void
 */
function focused_schools_enqueue_assets() {
	wp_enqueue_style(
		'focused-schools-style',
		get_stylesheet_uri(),
		array(),
		FOCUSED_SCHOOLS_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'focused_schools_enqueue_assets' );
