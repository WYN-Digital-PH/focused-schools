<?php
/**
 * Theme setup: theme supports, nav menus, content width.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation menus.
 *
 * @return void
 */
function focused_schools_setup() {
	load_theme_textdomain( 'focused-schools', FOCUSED_SCHOOLS_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );

	/*
	 * The approved .dc design renders the header/footer brand mark as a real
	 * logo image (full-logo.svg), not text — WordPress's native Site
	 * Identity mechanism (Appearance > Customize, or Site Editor > Design >
	 * Site Identity) is the correct, dynamic way to manage that without
	 * hardcoding a file path here. site-header.php/site-footer.php already
	 * fall back to the site title text when no logo is set, so this has zero
	 * effect until an editor uploads one.
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 46,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	add_editor_style( 'assets/css/editor-style.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'focused-schools' ),
			'footer'  => __( 'Footer Menu', 'focused-schools' ),
		)
	);

	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 800;
	}
}
add_action( 'after_setup_theme', 'focused_schools_setup' );

/**
 * Preconnect to Google Fonts, since inc/enqueue.php loads DM Sans from there.
 *
 * @param array  $urls          Existing resource hint URLs.
 * @param string $relation_type Relation type ('preconnect', 'dns-prefetch', etc.).
 * @return array
 */
function focused_schools_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'focused_schools_resource_hints', 10, 2 );

/**
 * Register widget areas.
 *
 * A footer widget area — previously missing entirely from this theme. If
 * the real site's Footer Contact Form / Newsletter Sign Up is implemented
 * as a widget (Text/HTML, or an Elementor widget dropped into a sidebar),
 * this is the safe, standard WP extension point for it to keep rendering
 * after migrating to this theme. Renders nothing unless a widget is
 * actually assigned to it — no content is guessed or hardcoded here.
 * See docs/forms-audit.md.
 *
 * @return void
 */
function focused_schools_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widget Area', 'focused-schools' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Displayed in the site footer, above the copyright line.', 'focused-schools' ),
			'before_widget' => '<div id="%1$s" class="fs-site-footer__widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="fs-site-footer__widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'focused_schools_widgets_init' );
