<?php
/**
 * Theme-facing helper functions.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'focused_schools_get_setting' ) ) {
	/**
	 * Get a single value from the Focused Schools site settings.
	 *
	 * Returns the sanitized (at save time) value as stored in the options
	 * table. Callers are still responsible for escaping the value for the
	 * context they output it in (esc_html(), esc_url(), esc_attr(), etc.).
	 *
	 * No capability check: this is a public read used by front-end
	 * templates, not an admin-only operation.
	 *
	 * @param string $key      Field key, e.g. 'business_name', 'phone', 'cta_url'.
	 * @param string $fallback Value to return if the key is unknown or unset.
	 * @return string
	 */
	function focused_schools_get_setting( $key, $fallback = '' ) {
		$settings = wp_parse_args(
			get_option( \FocusedSchoolsCore\Modules\Site_Settings::OPTION_NAME, array() ),
			\FocusedSchoolsCore\Modules\Site_Settings\Fields::defaults()
		);

		if ( ! array_key_exists( $key, $settings ) ) {
			return $fallback;
		}

		return $settings[ $key ];
	}
}
