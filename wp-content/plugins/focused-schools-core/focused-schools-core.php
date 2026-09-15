<?php
/**
 * Plugin Name:       Focused Schools Core
 * Description:       Site-specific core functionality for the focused-schools WordPress site.
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            WYN Digital
 * Text Domain:       focused-schools-core
 * Domain Path:       /languages
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FOCUSED_SCHOOLS_CORE_VERSION' ) ) {
	define( 'FOCUSED_SCHOOLS_CORE_VERSION', '0.1.0' );
}

if ( ! defined( 'FOCUSED_SCHOOLS_CORE_FILE' ) ) {
	define( 'FOCUSED_SCHOOLS_CORE_FILE', __FILE__ );
}

if ( ! defined( 'FOCUSED_SCHOOLS_CORE_DIR' ) ) {
	define( 'FOCUSED_SCHOOLS_CORE_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'FOCUSED_SCHOOLS_CORE_URL' ) ) {
	define( 'FOCUSED_SCHOOLS_CORE_URL', plugin_dir_url( __FILE__ ) );
}

require_once FOCUSED_SCHOOLS_CORE_DIR . 'includes/class-autoloader.php';

FocusedSchoolsCore\Autoloader::register();

require_once FOCUSED_SCHOOLS_CORE_DIR . 'includes/functions.php';

register_activation_hook( __FILE__, array( 'FocusedSchoolsCore\\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FocusedSchoolsCore\\Deactivator', 'deactivate' ) );

add_action( 'plugins_loaded', array( FocusedSchoolsCore\Plugin::instance(), 'init' ) );
