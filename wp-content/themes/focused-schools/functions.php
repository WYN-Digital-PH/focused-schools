<?php
/**
 * Focused Schools theme bootstrap.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FOCUSED_SCHOOLS_THEME_VERSION' ) ) {
	define( 'FOCUSED_SCHOOLS_THEME_VERSION', '0.1.0' );
}

/**
 * Registers baseline theme supports.
 *
 * @return void
 */
function focused_schools_setup() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'focused_schools_setup' );
