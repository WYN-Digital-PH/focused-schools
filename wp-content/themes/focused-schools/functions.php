<?php
/**
 * Theme bootstrap.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FOCUSED_SCHOOLS_THEME_VERSION' ) ) {
	define( 'FOCUSED_SCHOOLS_THEME_VERSION', '0.1.0' );
}

if ( ! defined( 'FOCUSED_SCHOOLS_THEME_DIR' ) ) {
	define( 'FOCUSED_SCHOOLS_THEME_DIR', get_template_directory() );
}

if ( ! defined( 'FOCUSED_SCHOOLS_THEME_URI' ) ) {
	define( 'FOCUSED_SCHOOLS_THEME_URI', get_template_directory_uri() );
}

require_once FOCUSED_SCHOOLS_THEME_DIR . '/inc/setup.php';
require_once FOCUSED_SCHOOLS_THEME_DIR . '/inc/enqueue.php';
