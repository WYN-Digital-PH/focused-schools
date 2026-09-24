<?php
/**
 * Meta field schema for the Impact Stories module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Impact_Stories;

defined( 'ABSPATH' ) || exit;

/**
 * Meta.
 *
 * Single source of truth for fs_impact_story post meta. Used by the meta
 * box (rendering), the save handler (sanitization), and
 * register_post_meta().
 */
class Meta {

	/**
	 * Meta key prefix. Underscore-prefixed so these fields do not also show
	 * up in WordPress's default "Custom Fields" metabox.
	 *
	 * @var string
	 */
	const PREFIX = '_fs_impact_story_';

	/**
	 * Sanitizer callback for each supported field type.
	 *
	 * @var array<string, callable>
	 */
	private static $type_sanitizers = array(
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'boolean'  => 'rest_sanitize_boolean',
	);

	/**
	 * Field definitions, in display order.
	 *
	 * @return array<string, array{label:string,type:string}>
	 */
	public static function all() {
		return array(
			'district_or_school' => array(
				'label' => __( 'District or School', 'focused-schools-core' ),
				'type'  => 'text',
			),
			'state'              => array(
				'label' => __( 'State', 'focused-schools-core' ),
				'type'  => 'text',
			),
			'year'               => array(
				'label' => __( 'Year', 'focused-schools-core' ),
				'type'  => 'text',
			),
			'featured'           => array(
				'label' => __( 'Featured', 'focused-schools-core' ),
				'type'  => 'boolean',
			),
			'service_lane'       => array(
				'label'       => __( 'Service Lane', 'focused-schools-core' ),
				'type'        => 'text',
				'description' => __( 'e.g. Strategy and Vision. Also used to match related stories.', 'focused-schools-core' ),
			),
			'partnership_length' => array(
				'label'       => __( 'Partnership Length', 'focused-schools-core' ),
				'type'        => 'text',
				'description' => __( 'e.g. Three years.', 'focused-schools-core' ),
			),
			'results'            => array(
				'label'       => __( 'At a Glance Results', 'focused-schools-core' ),
				'type'        => 'textarea',
				'description' => __( 'One result per line as value | unit | label, e.g. 400+ | Educators | Coached across eight campuses. Up to four; leave empty to omit the band.', 'focused-schools-core' ),
			),
		);
	}

	/**
	 * Parse the stored results textarea into rows.
	 *
	 * Each line is `value | unit | label`; unit and label are optional. Lines
	 * without a value are dropped, and the list is capped at four because the
	 * approved design only lays out one to four results.
	 *
	 * @param string $value Raw stored meta value.
	 * @return array<int, array{value:string,unit:string,label:string}>
	 */
	public static function results_list( $value ) {
		if ( ! is_string( $value ) || '' === trim( $value ) ) {
			return array();
		}

		$rows = array();

		foreach ( preg_split(
			'/

|
|
/',
			$value
		) as $line ) {
			$parts = array_map( 'trim', explode( '|', $line ) );

			if ( '' === $parts[0] ) {
				continue;
			}

			$rows[] = array(
				'value' => $parts[0],
				'unit'  => isset( $parts[1] ) ? $parts[1] : '',
				'label' => isset( $parts[2] ) ? $parts[2] : '',
			);

			if ( count( $rows ) >= 4 ) {
				break;
			}
		}

		return $rows;
	}

	/**
	 * Get the prefixed post meta key for a field.
	 *
	 * @param string $key Unprefixed field key, e.g. 'state'.
	 * @return string
	 */
	public static function meta_key( $key ) {
		return self::PREFIX . $key;
	}

	/**
	 * Get the sanitizer callback for a field type.
	 *
	 * @param string $type Field type ('text' or 'boolean').
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
			$args = array(
				'type'              => 'boolean' === $field['type'] ? 'boolean' : 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => self::sanitizer_for( $field['type'] ),
				'auth_callback'     => array( self::class, 'auth_callback' ),
			);

			if ( 'boolean' === $field['type'] ) {
				$args['default'] = false;
			}

			register_post_meta( $post_type, self::meta_key( $key ), $args );
		}
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
