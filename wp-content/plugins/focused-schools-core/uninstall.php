<?php
/**
 * Fires on plugin uninstall (Delete via the Plugins screen).
 *
 * Intentionally a no-op: this plugin does not remove options or content on
 * uninstall. Any future opt-in cleanup must be an explicit, documented
 * decision — never a default.
 *
 * @package FocusedSchoolsCore
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
