<?php
/**
 * Meta field schema for the Team module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Team;

defined( 'ABSPATH' ) || exit;

/**
 * Meta.
 *
 * Single source of truth for fs_team_member post meta. Used by the meta box
 * (rendering), the save handler (sanitization), and register_post_meta().
 */
class Meta {

	/**
	 * Meta key prefix. Underscore-prefixed so these fields do not also show
	 * up in WordPress's default "Custom Fields" metabox.
	 *
	 * @var string
	 */
	const PREFIX = '_fs_team_';

	/**
	 * Sanitizer callback for each supported field type.
	 *
	 * @var array<string, callable>
	 */
	private static $type_sanitizers = array(
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'esc_url_raw',
	);

	/**
	 * Field definitions, in display order.
	 *
	 * @return array<string, array{label:string,type:string}>
	 */
	public static function all() {
		return array(
			'position'     => array(
				'label' => __( 'Position', 'focused-schools-core' ),
				'type'  => 'text',
			),
			'quote'        => array(
				'label' => __( 'Quote', 'focused-schools-core' ),
				'type'  => 'textarea',
			),
			'linkedin_url' => array(
				'label' => __( 'LinkedIn URL', 'focused-schools-core' ),
				'type'  => 'url',
			),
		);
	}

	/**
	 * Get the prefixed post meta key for a field.
	 *
	 * @param string $key Unprefixed field key, e.g. 'position'.
	 * @return string
	 */
	public static function meta_key( $key ) {
		return self::PREFIX . $key;
	}

	/**
	 * Get the sanitizer callback for a field type.
	 *
	 * @param string $type Field type ('text', 'textarea', 'url').
	 * @return callable
	 */
	public static function sanitizer_for( $type ) {
		return isset( self::$type_sanitizers[ $type ] ) ? self::$type_sanitizers[ $type ] : 'sanitize_text_field';
	}

	/**
	 * Register post meta for every known field.
	 *
	 * @param string $post_type Post type to register meta against.
	 * @return void
	 */
	public static function register( $post_type ) {
		foreach ( self::all() as $key => $field ) {
			register_post_meta(
				$post_type,
				self::meta_key( $key ),
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => self::sanitizer_for( $field['type'] ),
					'auth_callback'     => array( self::class, 'auth_callback' ),
				)
			);
		}
	}

	/**
	 * Per-post capability check used as the register_post_meta auth_callback.
	 *
	 * @param bool   $allowed Whether the value is allowed to be edited.
	 * @param string $meta_key Meta key being checked.
	 * @param int    $post_id  Post ID.
	 * @return bool
	 */
	public static function auth_callback( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	}
}
