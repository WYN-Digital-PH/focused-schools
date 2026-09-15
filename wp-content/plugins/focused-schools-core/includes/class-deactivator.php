<?php
/**
 * Deactivation logic.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

/**
 * Deactivator.
 */
class Deactivator {

	/**
	 * Runs on plugin deactivation.
	 *
	 * Intentionally non-destructive: flushes rewrite rules only. Options and
	 * content are left untouched so reactivating the plugin is safe.
	 *
	 * @return void
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		flush_rewrite_rules();
	}
}
