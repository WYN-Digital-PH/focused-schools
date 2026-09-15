<?php
/**
 * Meta field schema for the Services module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Services;

defined( 'ABSPATH' ) || exit;

/**
 * Meta.
 *
 * Single source of truth for fs_service post meta. Used by the meta box
 * (rendering), the save handler (sanitization), and register_post_meta().
 */
class Meta {

	/**
	 * Meta key prefix. Underscore-prefixed so these fields do not also show
	 * up in WordPress's default "Custom Fields" metabox.
	 *
	 * @var string
	 */
	const PREFIX = '_fs_service_';

	/**
	 * Strict whitelist of allowed accent_role values. No arbitrary strings
	 * or hex colors are ever accepted.
	 *
	 * @var string[]
	 */
	const ACCENT_ROLES = array( 'strategy', 'leadership', 'capacity' );

	/**
	 * Field definitions, in display order.
	 *
	 * @return array<string, array{label:string,type:string,options?:string[]}>
	 */
	public static function all() {
		return array(
			'tagline'     => array(
				'label' => __( 'Tagline', 'focused-schools-core' ),
				'type'  => 'text',
			),
			'accent_role' => array(
				'label'   => __( 'Accent Role', 'focused-schools-core' ),
				'type'    => 'select',
				'options' => self::ACCENT_ROLES,
			),
		);
	}

	/**
	 * Human-readable labels for the accent_role options.
	 *
	 * @return array<string, string>
	 */
	public static function accent_role_labels() {
		return array(
			'strategy'   => __( 'Strategy', 'focused-schools-core' ),
			'leadership' => __( 'Leadership', 'focused-schools-core' ),
			'capacity'   => __( 'Capacity', 'focused-schools-core' ),
		);
	}

	/**
	 * Get the prefixed post meta key for a field.
	 *
	 * @param string $key Unprefixed field key, e.g. 'tagline'.
	 * @return string
	 */
	public static function meta_key( $key ) {
		return self::PREFIX . $key;
	}

	/**
	 * Get the sanitizer callback for a given field key.
	 *
	 * @param string $key Unprefixed field key.
	 * @return callable
	 */
	public static function sanitizer_for( $key ) {
		if ( 'accent_role' === $key ) {
			return array( self::class, 'sanitize_accent_role' );
		}

		return 'sanitize_text_field';
	}

	/**
	 * Strictly whitelist accent_role: only strategy, leadership, or capacity.
	 *
	 * @param mixed $value Raw submitted value.
	 * @return string
	 */
	public static function sanitize_accent_role( $value ) {
		return in_array( $value, self::ACCENT_ROLES, true ) ? $value : '';
	}

	/**
	 * Register post meta for every known field.
	 *
	 * @param string $post_type Post type to register meta against.
	 * @return void
	 */
	public static function register( $post_type ) {
		register_post_meta(
			$post_type,
			self::meta_key( 'tagline' ),
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => array( self::class, 'auth_callback' ),
			)
		);

		register_post_meta(
			$post_type,
			self::meta_key( 'accent_role' ),
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => 'strategy',
				'show_in_rest'      => array(
					'schema' => array(
						'type' => 'string',
						'enum' => self::ACCENT_ROLES,
					),
				),
				'sanitize_callback' => array( self::class, 'sanitize_accent_role' ),
				'auth_callback'     => array( self::class, 'auth_callback' ),
			)
		);
	}

	/**
	 * Per-post capability check used as the register_post_meta auth_callback.
	 *
	 * @param bool   $allowed  Whether the value is allowed to be edited.
	 * @param string $meta_key Meta key being checked.
	 * @param int    $post_id  Post ID.
	 * @return bool
	 */
	public static function auth_callback( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	}
}
