<?php
/**
 * Activation logic.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

/**
 * Activator.
 */
class Activator {

	/**
	 * Runs on plugin activation.
	 *
	 * Intentionally non-destructive: no database tables, no content writes,
	 * no data seeding or deletion. Only stores the plugin version and
	 * flushes rewrite rules so future post types register cleanly.
	 *
	 * @return void
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
			deactivate_plugins( plugin_basename( FOCUSED_SCHOOLS_CORE_FILE ) );
			wp_die(
				esc_html__( 'Focused Schools Core requires PHP 7.4 or higher.', 'focused-schools-core' ),
				esc_html__( 'Plugin activation error', 'focused-schools-core' ),
				array( 'back_link' => true )
			);
		}

		update_option( 'focused_schools_core_version', FOCUSED_SCHOOLS_CORE_VERSION );

		flush_rewrite_rules();
	}
}
