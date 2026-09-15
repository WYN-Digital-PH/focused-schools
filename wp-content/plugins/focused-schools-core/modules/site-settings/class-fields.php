<?php
/**
 * Field schema for the Site Settings module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Site_Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Fields.
 *
 * Single source of truth for the Site Settings field list. Used by the
 * admin UI (rendering), the sanitize callback, and the theme-facing helper
 * function's defaults.
 */
class Fields {

	/**
	 * Sanitizer callback for each supported field type.
	 *
	 * @var array<string, callable>
	 */
	private static $type_sanitizers = array(
		'text'     => 'sanitize_text_field',
		'email'    => 'sanitize_email',
		'url'      => 'esc_url_raw',
		'textarea' => 'sanitize_textarea_field',
	);

	/**
	 * Section definitions, in display order.
	 *
	 * @return array<string, string> Section key => section label.
	 */
	public static function sections() {
		return array(
			'business_info' => __( 'Business Information', 'focused-schools-core' ),
			'primary_cta'   => __( 'Primary Call To Action', 'focused-schools-core' ),
			'social_links'  => __( 'Social Links', 'focused-schools-core' ),
			'footer'        => __( 'Footer', 'focused-schools-core' ),
		);
	}

	/**
	 * Field definitions, in display order.
	 *
	 * @return array<string, array{label:string,section:string,type:string,description:string}>
	 */
	public static function all() {
		return array(
			'business_name'  => array(
				'label'       => __( 'Business Name', 'focused-schools-core' ),
				'section'     => 'business_info',
				'type'        => 'text',
				'description' => '',
			),
			'phone'          => array(
				'label'       => __( 'Phone', 'focused-schools-core' ),
				'section'     => 'business_info',
				'type'        => 'text',
				'description' => '',
			),
			'email'          => array(
				'label'       => __( 'Email', 'focused-schools-core' ),
				'section'     => 'business_info',
				'type'        => 'email',
				'description' => '',
			),
			'address'        => array(
				'label'       => __( 'Address', 'focused-schools-core' ),
				'section'     => 'business_info',
				'type'        => 'textarea',
				'description' => '',
			),
			'cta_label'      => array(
				'label'       => __( 'Primary CTA Label', 'focused-schools-core' ),
				'section'     => 'primary_cta',
				'type'        => 'text',
				'description' => '',
			),
			'cta_url'        => array(
				'label'       => __( 'Primary CTA URL', 'focused-schools-core' ),
				'section'     => 'primary_cta',
				'type'        => 'url',
				'description' => '',
			),
			'facebook_url'   => array(
				'label'       => __( 'Facebook URL', 'focused-schools-core' ),
				'section'     => 'social_links',
				'type'        => 'url',
				'description' => '',
			),
			'linkedin_url'   => array(
				'label'       => __( 'LinkedIn URL', 'focused-schools-core' ),
				'section'     => 'social_links',
				'type'        => 'url',
				'description' => '',
			),
			'youtube_url'    => array(
				'label'       => __( 'YouTube URL', 'focused-schools-core' ),
				'section'     => 'social_links',
				'type'        => 'url',
				'description' => '',
			),
			'footer_text'    => array(
				'label'       => __( 'Footer Short Text', 'focused-schools-core' ),
				'section'     => 'footer',
				'type'        => 'textarea',
				'description' => '',
			),
			'copyright_name' => array(
				'label'       => __( 'Copyright Name', 'focused-schools-core' ),
				'section'     => 'footer',
				'type'        => 'text',
				'description' => '',
			),
		);
	}

	/**
	 * Default (empty) values for every known field.
	 *
	 * @return array<string, string>
	 */
	public static function defaults() {
		return array_fill_keys( array_keys( self::all() ), '' );
	}

	/**
	 * Sanitize a raw settings submission against the known field schema.
	 *
	 * Always returns exactly the known keys: unexpected input keys are
	 * dropped and missing keys default to an empty string.
	 *
	 * @param mixed $input Raw value from the settings form.
	 * @return array<string, string>
	 */
	public static function sanitize( $input ) {
		$input     = is_array( $input ) ? $input : array();
		$sanitized = array();

		foreach ( self::all() as $key => $field ) {
			$raw       = isset( $input[ $key ] ) ? $input[ $key ] : '';
			$sanitizer = isset( self::$type_sanitizers[ $field['type'] ] )
				? self::$type_sanitizers[ $field['type'] ]
				: 'sanitize_text_field';

			$sanitized[ $key ] = call_user_func( $sanitizer, $raw );
		}

		return $sanitized;
	}
}
