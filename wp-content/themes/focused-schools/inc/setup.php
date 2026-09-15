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
