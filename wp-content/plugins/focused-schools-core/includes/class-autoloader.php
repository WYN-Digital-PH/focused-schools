<?php
/**
 * Class autoloader.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

/**
 * Maps the FocusedSchoolsCore namespace to plugin files using WPCS file naming.
 */
class Autoloader {

	/**
	 * Namespace-to-directory map, most specific first.
	 *
	 * @var array<string, string>
	 */
	private static $namespace_map = array(
		__NAMESPACE__ . '\\Modules\\' => 'modules',
		__NAMESPACE__ . '\\'          => 'includes',
	);

	/**
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Attempt to load a class file for the given fully-qualified class name.
	 *
	 * @param string $class_name Fully-qualified class name.
	 * @return void
	 */
	public static function autoload( $class_name ) {
		foreach ( self::$namespace_map as $namespace_prefix => $relative_dir ) {
			if ( 0 !== strpos( $class_name, $namespace_prefix ) ) {
				continue;
			}

			$relative_class = substr( $class_name, strlen( $namespace_prefix ) );
			$path_parts     = explode( '\\', $relative_class );
			$short_class    = array_pop( $path_parts );

			if ( '_Interface' === substr( $short_class, -10 ) ) {
				$file_prefix = 'interface';
				$short_class = substr( $short_class, 0, -10 );
			} else {
				$file_prefix = 'class';
			}

			$file_name = $file_prefix . '-' . self::to_kebab_case( $short_class ) . '.php';

			$sub_path = '';
			if ( ! empty( $path_parts ) ) {
				$sub_path = strtolower( implode( '/', array_map( array( __CLASS__, 'to_kebab_case' ), $path_parts ) ) ) . '/';
			} elseif ( 'modules' === $relative_dir ) {
				// Each top-level module class lives in its own directory,
				// e.g. Modules\Site_Settings => modules/site-settings/class-site-settings.php.
				$sub_path = self::to_kebab_case( $short_class ) . '/';
			}

			$file = FOCUSED_SCHOOLS_CORE_DIR . $relative_dir . '/' . $sub_path . $file_name;

			if ( file_exists( $file ) ) {
				require_once $file;
			}

			return;
		}
	}

	/**
	 * Convert a Class_Name or Module_Interface style name to kebab-case.
	 *
	 * @param string $name Class or namespace segment name.
	 * @return string
	 */
	private static function to_kebab_case( $name ) {
		return strtolower( str_replace( '_', '-', $name ) );
	}
}
