<?php
/**
 * Provisioning module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;
use FocusedSchoolsCore\Modules\Provisioning\CLI_Command;

defined( 'ABSPATH' ) || exit;

/**
 * Provisioning.
 *
 * Registers the dry-run-by-default WP-CLI command that creates the Pages the
 * theme's page-{slug}.php templates attach to, plus the header and footer
 * navigation menus. Adds no hooks and no admin UI: it exists only so a fresh
 * environment (staging, a new local site) can be brought up to the structure
 * the theme expects without hand-creating each page.
 */
class Provisioning implements Module_Interface {

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'focused-schools seed-site', new CLI_Command() );
		}
	}
}
