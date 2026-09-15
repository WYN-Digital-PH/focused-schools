<?php
/**
 * Frontend and editor asset enqueuing.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

/**
 * Component stylesheet slugs, relative to assets/css/components/.
 * 'card' is the shared base used by the *-card components, so it's
 * registered first and listed as their dependency.
 *
 * @var string[]
 */
function focused_schools_component_styles() {
	return array(
		'card',
		'buttons',
		'section-heading',
		'hero',
		'content-image-split',
		'service-card',
		'team-card',
		'impact-story-card',
		'podcast-card',
		'statistics-counter',
		'partner-strip',
		'form-wrapper',
		'site-header',
		'site-footer',
	);
}

/**
 * Enqueue the main stylesheet and all reusable component styles.
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

	foreach ( focused_schools_component_styles() as $fs_component ) {
		$fs_deps = in_array( $fs_component, array( 'service-card', 'team-card', 'impact-story-card', 'podcast-card' ), true )
			? array( 'focused-schools-style', 'focused-schools-component-card' )
			: array( 'focused-schools-style' );

		wp_enqueue_style(
			'focused-schools-component-' . $fs_component,
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/components/' . $fs_component . '.css',
			$fs_deps,
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	wp_enqueue_script(
		'focused-schools-site-header',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/site-header.js',
		array(),
		FOCUSED_SCHOOLS_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'focused-schools-statistics-counter',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/statistics-counter.js',
		array(),
		FOCUSED_SCHOOLS_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'focused_schools_enqueue_assets' );
