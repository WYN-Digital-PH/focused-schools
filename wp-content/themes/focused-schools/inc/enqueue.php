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
		'cta-banner',
		'card-grid',
		'contact-info',
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

	wp_enqueue_script(
		'focused-schools-podcast-video',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/podcast-video.js',
		array(),
		FOCUSED_SCHOOLS_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'focused-schools-page-home',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-home.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'about-our-mission-vision' ) ) {
		wp_enqueue_style(
			'focused-schools-page-about',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-about.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'services' ) ) {
		wp_enqueue_style(
			'focused-schools-page-services',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-services.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'team' ) ) {
		wp_enqueue_style(
			'focused-schools-page-team',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-team.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'impact-stories' ) ) {
		wp_enqueue_style(
			'focused-schools-page-impact-stories',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-impact-stories.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_singular( 'fs_impact_story' ) ) {
		wp_enqueue_style(
			'focused-schools-single-impact-story',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/single-impact-story.css',
			array( 'focused-schools-style', 'focused-schools-component-card' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'podcast' ) ) {
		wp_enqueue_style(
			'focused-schools-page-podcast',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-podcast.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'contact' ) ) {
		wp_enqueue_style(
			'focused-schools-page-contact',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-contact.css',
			array( 'focused-schools-style', 'focused-schools-component-contact-info' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}

	if ( is_page( 'thanks' ) ) {
		wp_enqueue_style(
			'focused-schools-page-thanks',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-thanks.css',
			array( 'focused-schools-style' ),
			FOCUSED_SCHOOLS_THEME_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'focused_schools_enqueue_assets' );
