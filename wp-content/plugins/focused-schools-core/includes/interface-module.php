<?php
/**
 * Contract implemented by every feature module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore;

defined( 'ABSPATH' ) || exit;

/**
 * Module_Interface.
 */
interface Module_Interface {

	/**
	 * Register the module's own WordPress hooks.
	 *
	 * Called once during Plugin::init(). Implementations should attach their
	 * own actions/filters here rather than doing work immediately.
	 *
	 * @return void
	 */
	public function register();
}
