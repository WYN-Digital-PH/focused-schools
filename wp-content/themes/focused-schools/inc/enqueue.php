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
		'story-card',
		'story-filters',
		'story-spotlight',
		'podcast-card',
		'podcast-subscribe',
		'podcast-latest',
		'podcast-player',
		'statistics-counter',
		'partner-strip',
		'form-wrapper',
		'site-header',
		'site-footer',
		'cta-banner',
		'card-grid',
		'contact-info',
		'contact-aside',
		'link-cards',
		'post-card',
		'home-hero',
		'commitment-list',
		'cycle-of-excellence',
		'cycle-reference',
		'service-list',
		'service-index',
		'service-lane',
		'testimonial-carousel',
		'rail-text',
		'partner-districts',
		'partner-map',
		'where-we-work',
		'team-bio-modal',
		'cycle-teaser',
	);
}

/**
 * Cache-busting version for a theme asset.
 *
 * Returns the file's modification time, so the ?ver= query changes by itself
 * whenever the file changes. A fixed version string cannot do this: assets are
 * served with a long max-age, so an edge or browser cache keyed on an
 * unchanged ?ver= keeps serving the old file after a deploy — which is exactly
 * how staging ended up rendering new markup against an old stylesheet twice.
 *
 * Falls back to the theme version if the file is unreadable, so a missing file
 * can never take the page down.
 *
 * @param string $relative_path Path relative to the theme root, e.g. '/assets/css/components/site-header.css'.
 * @return string
 */
function focused_schools_asset_version( $relative_path ) {
	$file = FOCUSED_SCHOOLS_THEME_DIR . $relative_path;
	$time = is_readable( $file ) ? filemtime( $file ) : false;

	return $time ? (string) $time : FOCUSED_SCHOOLS_THEME_VERSION;
}

/**
 * Whether the current request renders the homepage layout.
 *
 * True for the front page, and for any page built with the homepage blocks —
 * they render the same components, so they need the same stylesheet and the
 * same scroll/video behaviour. Without this, a block-built Home page rendered
 * its markup with none of its CSS or JS.
 *
 * @return bool
 */
function focused_schools_is_home_layout() {
	if ( is_front_page() ) {
		return true;
	}

	if ( ! is_singular() ) {
		return false;
	}

	$post = get_post();

	return $post instanceof WP_Post
		&& false !== strpos( (string) $post->post_content, '<!-- wp:focused-schools/' );
}

/**
 * Whether the current request renders a partner map.
 *
 * True when the page content carries the Partner Districts block. Keeps
 * Leaflet off every other page rather than loading it site-wide.
 *
 * @return bool
 */
function focused_schools_has_partner_map() {
	if ( ! is_singular() ) {
		return false;
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	// Both the About directory and the homepage's Where We Work draw the map.
	return has_block( 'focused-schools/partner-districts', $post )
		|| has_block( 'focused-schools/where-we-work', $post );
}

/**
 * Enqueue the main stylesheet and all reusable component styles.
 *
 * @return void
 */
function focused_schools_enqueue_assets() {
	wp_enqueue_style(
		'focused-schools-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700;900&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- external Google Fonts URL; versioning/caching is Google's own to manage, not ours.
	);

	wp_enqueue_style(
		'focused-schools-style',
		get_stylesheet_uri(),
		array( 'focused-schools-fonts' ),
		focused_schools_asset_version( '/style.css' )
	);

	foreach ( focused_schools_component_styles() as $fs_component ) {
		$fs_deps = in_array( $fs_component, array( 'service-card', 'team-card', 'impact-story-card', 'podcast-card' ), true )
			? array( 'focused-schools-style', 'focused-schools-component-card' )
			: array( 'focused-schools-style' );

		wp_enqueue_style(
			'focused-schools-component-' . $fs_component,
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/components/' . $fs_component . '.css',
			$fs_deps,
			focused_schools_asset_version( '/assets/css/components/' . $fs_component . '.css' )
		);
	}

	wp_enqueue_script(
		'focused-schools-site-header',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/site-header.js',
		array(),
		focused_schools_asset_version( '/assets/js/components/site-header.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'focused-schools-statistics-counter',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/statistics-counter.js',
		array(),
		focused_schools_asset_version( '/assets/js/components/statistics-counter.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'focused-schools-podcast-video',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/podcast-video.js',
		array(),
		focused_schools_asset_version( '/assets/js/components/podcast-video.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( focused_schools_is_home_layout() ) {
		wp_enqueue_style(
			'focused-schools-page-home',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-home.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-home.css' )
		);

		foreach ( array( 'home-hero', 'home-hold', 'cycle-of-excellence', 'testimonial-carousel' ) as $fs_home_script ) {
			wp_enqueue_script(
				'focused-schools-' . $fs_home_script,
				FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/' . $fs_home_script . '.js',
				array(),
				focused_schools_asset_version( '/assets/js/components/' . $fs_home_script . '.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}

	/*
	 * Leaflet is vendored into the theme rather than loaded from a CDN, so the
	 * site makes no third-party request for the library. It is enqueued only
	 * where a partner map is actually on the page — never site-wide.
	 */
	if ( focused_schools_has_partner_map() ) {
		wp_enqueue_style(
			'leaflet',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/vendor/leaflet/leaflet.css',
			array(),
			'1.9.4'
		);

		wp_enqueue_script(
			'leaflet',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/vendor/leaflet/leaflet.js',
			array(),
			'1.9.4',
			array( 'in_footer' => true )
		);

		wp_enqueue_script(
			'focused-schools-partner-map',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/partner-map.js',
			array( 'leaflet' ),
			focused_schools_asset_version( '/assets/js/components/partner-map.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_page( 'about-our-mission-vision' ) ) {
		wp_enqueue_style(
			'focused-schools-page-about',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-about.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-about.css' )
		);

		foreach ( array( 'team-bio-modal', 'team-load-more' ) as $fs_about_script ) {
			wp_enqueue_script(
				'focused-schools-' . $fs_about_script,
				FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/' . $fs_about_script . '.js',
				array(),
				focused_schools_asset_version( '/assets/js/components/' . $fs_about_script . '.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}

	if ( is_page( 'services' ) ) {
		wp_enqueue_style(
			'focused-schools-page-services',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-services.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-services.css' )
		);

		// The closing testimonial section needs the same carousel script
		// Home uses — was missing here entirely, so the prev/next/dot
		// controls rendered but did nothing.
		wp_enqueue_script(
			'focused-schools-testimonial-carousel',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/testimonial-carousel.js',
			array(),
			focused_schools_asset_version( '/assets/js/components/testimonial-carousel.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_page( 'team' ) ) {
		wp_enqueue_style(
			'focused-schools-page-team',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-team.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-team.css' )
		);

		// Same two behaviours the About page's team grid uses: the shared
		// bio dialog and the "Load more" reveal.
		foreach ( array( 'team-bio-modal', 'team-load-more' ) as $fs_team_script ) {
			wp_enqueue_script(
				'focused-schools-' . $fs_team_script,
				FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/' . $fs_team_script . '.js',
				array(),
				focused_schools_asset_version( '/assets/js/components/' . $fs_team_script . '.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}

	if ( is_page( 'impact-stories' ) ) {
		wp_enqueue_style(
			'focused-schools-page-impact-stories',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-impact-stories.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-impact-stories.css' )
		);

		wp_enqueue_script(
			'focused-schools-story-filters',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/story-filters.js',
			array(),
			focused_schools_asset_version( '/assets/js/components/story-filters.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_singular( 'fs_impact_story' ) ) {
		wp_enqueue_style(
			'focused-schools-single-impact-story',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/single-impact-story.css',
			array( 'focused-schools-style', 'focused-schools-component-card' ),
			focused_schools_asset_version( '/assets/css/single-impact-story.css' )
		);
	}

	if ( is_page( 'podcast' ) ) {
		wp_enqueue_style(
			'focused-schools-page-podcast',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-podcast.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-podcast.css' )
		);

		// The video grid's "Load more episodes" reuses the team grid's
		// reveal-hidden-children behaviour (it only needs the button's
		// aria-controls grid), rather than duplicating it.
		wp_enqueue_script(
			'focused-schools-team-load-more',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/js/components/team-load-more.js',
			array(),
			focused_schools_asset_version( '/assets/js/components/team-load-more.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_page( 'contact' ) ) {
		wp_enqueue_style(
			'focused-schools-page-contact',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-contact.css',
			array( 'focused-schools-style', 'focused-schools-component-contact-info' ),
			focused_schools_asset_version( '/assets/css/page-contact.css' )
		);
	}

	if ( is_page( 'thanks' ) ) {
		wp_enqueue_style(
			'focused-schools-page-thanks',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/page-thanks.css',
			array( 'focused-schools-style' ),
			focused_schools_asset_version( '/assets/css/page-thanks.css' )
		);
	}

	if ( is_home() || is_archive() ) {
		wp_enqueue_style(
			'focused-schools-blog-archive',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/blog-archive.css',
			array( 'focused-schools-style', 'focused-schools-component-post-card' ),
			focused_schools_asset_version( '/assets/css/blog-archive.css' )
		);
	}

	// is_single() alone also matches other public post types (e.g.
	// fs_impact_story) — scope explicitly to native `post` singles, since
	// fs_impact_story already has its own single-fs_impact_story.php +
	// single-impact-story.css.
	if ( is_single() && 'post' === get_post_type() ) {
		wp_enqueue_style(
			'focused-schools-blog-single',
			FOCUSED_SCHOOLS_THEME_URI . '/assets/css/blog-single.css',
			array( 'focused-schools-style', 'focused-schools-component-post-card' ),
			focused_schools_asset_version( '/assets/css/blog-single.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'focused_schools_enqueue_assets' );
