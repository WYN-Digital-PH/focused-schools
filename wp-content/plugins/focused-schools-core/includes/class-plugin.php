<?php
/**
 * Core plugin bootstrap.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

use FocusedSchoolsCore\Modules\Site_Settings;
use FocusedSchoolsCore\Modules\Team;
use FocusedSchoolsCore\Modules\Services;
use FocusedSchoolsCore\Modules\Impact_Stories;
use FocusedSchoolsCore\Modules\Podcast;
use FocusedSchoolsCore\Modules\Testimonials;
use FocusedSchoolsCore\Modules\Partners;
use FocusedSchoolsCore\Modules\Provisioning;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin.
 */
class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Registered module instances.
	 *
	 * @var Module_Interface[]
	 */
	private $modules = array();

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor. Private to enforce singleton usage.
	 */
	private function __construct() {}

	/**
	 * Boot the plugin. Hooked to `plugins_loaded`.
	 *
	 * @return void
	 */
	public function init() {
		load_plugin_textdomain(
			'focused-schools-core',
			false,
			dirname( plugin_basename( FOCUSED_SCHOOLS_CORE_FILE ) ) . '/languages'
		);

		$this->modules = array(
			'site-settings'  => new Site_Settings(),
			'team'           => new Team(),
			'services'       => new Services(),
			'impact-stories' => new Impact_Stories(),
			'podcast'        => new Podcast(),
			'testimonials'   => new Testimonials(),
			'partners'       => new Partners(),
			'provisioning'   => new Provisioning(),
		);

		foreach ( $this->modules as $module ) {
			$module->register();
		}
	}

	/**
	 * Get a registered module by key.
	 *
	 * @param string $key Module key (e.g. 'team').
	 * @return Module_Interface|null
	 */
	public function get_module( $key ) {
		return isset( $this->modules[ $key ] ) ? $this->modules[ $key ] : null;
	}
}
