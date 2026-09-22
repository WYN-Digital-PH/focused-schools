<?php
/**
 * Services module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;
use FocusedSchoolsCore\Modules\Services\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Services.
 *
 * Registers the fs_service post type: an admin-only content type (no public
 * URLs/archive) with a tagline and a strictly whitelisted accent role. No
 * ACF, no custom tables, no REST endpoints beyond the native post type REST
 * support, and no demo content is created on activation.
 */
class Services implements Module_Interface {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'fs_service';

	/**
	 * Nonce action for the meta box save.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'focused_schools_save_service_meta';

	/**
	 * Nonce field name for the meta box save.
	 *
	 * @var string
	 */
	const NONCE_NAME = 'focused_schools_service_meta_nonce';

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_meta' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'admin_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );
	}

	/**
	 * Register the fs_service post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Services', 'focused-schools-core' ),
			'singular_name'      => __( 'Service', 'focused-schools-core' ),
			'menu_name'          => __( 'Services', 'focused-schools-core' ),
			'all_items'          => __( 'Services', 'focused-schools-core' ),
			'add_new'            => __( 'Add New', 'focused-schools-core' ),
			'add_new_item'       => __( 'Add New Service', 'focused-schools-core' ),
			'edit_item'          => __( 'Edit Service', 'focused-schools-core' ),
			'new_item'           => __( 'New Service', 'focused-schools-core' ),
			'view_item'          => __( 'View Service', 'focused-schools-core' ),
			'search_items'       => __( 'Search Services', 'focused-schools-core' ),
			'not_found'          => __( 'No services found', 'focused-schools-core' ),
			'not_found_in_trash' => __( 'No services found in Trash', 'focused-schools-core' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'             => $labels,
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
				'public'             => false,
				'show_ui'            => true,
				'show_in_menu'       => Site_Settings::PAGE_SLUG,
				'show_in_rest'       => true,
				'publicly_queryable' => false,
				'has_archive'        => false,
				'rewrite'            => false,
				'query_var'          => false,
				'capability_type'    => 'post',
				'hierarchical'       => false,
			)
		);
	}

	/**
	 * Register post meta for the post type.
	 *
	 * @return void
	 */
	public function register_meta() {
		Meta::register( self::POST_TYPE );
	}

	/**
	 * Add the Service Details meta box.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'focused_schools_service_details',
			__( 'Service Details', 'focused-schools-core' ),
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the Service Details meta box.
	 *
	 * @param \WP_Post $post Current post object.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		// Every field except accent_role renders from the shared schema, so
		// adding a field to Meta::all() is all it takes to surface it here.
		foreach ( Meta::all() as $key => $field ) {
			if ( 'accent_role' === $key ) {
				continue;
			}

			$meta_key = Meta::meta_key( $key );
			$value    = get_post_meta( $post->ID, $meta_key, true );
			$id       = 'focused_schools_service_' . $key;

			echo '<p>';
			printf(
				'<label for="%1$s"><strong>%2$s</strong></label><br />',
				esc_attr( $id ),
				esc_html( $field['label'] )
			);

			if ( 'textarea' === $field['type'] ) {
				printf(
					'<textarea id="%1$s" name="%2$s" rows="6" class="large-text">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $meta_key ),
					esc_textarea( $value )
				);
			} elseif ( 'url' === $field['type'] ) {
				printf(
					'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="large-text" />',
					esc_attr( $id ),
					esc_attr( $meta_key ),
					esc_url( $value )
				);
			} else {
				printf(
					'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="large-text" />',
					esc_attr( $id ),
					esc_attr( $meta_key ),
					esc_attr( $value )
				);
			}

			if ( ! empty( $field['description'] ) ) {
				printf( '<span class="description">%s</span>', esc_html( $field['description'] ) );
			}

			echo '</p>';
		}

		$accent_key    = Meta::meta_key( 'accent_role' );
		$accent_value  = get_post_meta( $post->ID, $accent_key, true );
		$accent_value  = in_array( $accent_value, Meta::ACCENT_ROLES, true ) ? $accent_value : 'strategy';
		$accent_labels = Meta::accent_role_labels();

		echo '<p>';
		printf(
			'<label for="focused_schools_service_accent_role"><strong>%s</strong></label><br />',
			esc_html__( 'Accent Role', 'focused-schools-core' )
		);
		printf( '<select id="focused_schools_service_accent_role" name="%s">', esc_attr( $accent_key ) );
		foreach ( Meta::ACCENT_ROLES as $role ) {
			printf(
				'<option value="%1$s"%2$s>%3$s</option>',
				esc_attr( $role ),
				selected( $accent_value, $role, false ),
				esc_html( $accent_labels[ $role ] )
			);
		}
		echo '</select>';
		echo '</p>';
	}

	/**
	 * Save the Service Details meta box fields.
	 *
	 * @param int $post_id Post ID being saved.
	 * @return void
	 */
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) );

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		foreach ( array_keys( Meta::all() ) as $key ) {
			$meta_key = Meta::meta_key( $key );

			if ( ! isset( $_POST[ $meta_key ] ) ) {
				continue;
			}

			$raw       = wp_unslash( $_POST[ $meta_key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized on the next line via Meta::sanitizer_for().
			$sanitized = call_user_func( Meta::sanitizer_for( $key ), $raw );

			update_post_meta( $post_id, $meta_key, $sanitized );
		}
	}

	/**
	 * Rebuild the admin list table columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function admin_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			if ( 'date' === $key ) {
				continue;
			}

			$new_columns[ $key ] = $label;
		}

		$new_columns['fs_tagline']     = __( 'Tagline', 'focused-schools-core' );
		$new_columns['fs_accent_role'] = __( 'Accent Role', 'focused-schools-core' );
		$new_columns['fs_order']       = __( 'Order', 'focused-schools-core' );
		$new_columns['fs_modified']    = __( 'Modified Date', 'focused-schools-core' );

		return $new_columns;
	}

	/**
	 * Render a single admin list table column.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID for the current row.
	 * @return void
	 */
	public function render_admin_column( $column, $post_id ) {
		switch ( $column ) {
			case 'fs_tagline':
				echo esc_html( get_post_meta( $post_id, Meta::meta_key( 'tagline' ), true ) );
				break;

			case 'fs_accent_role':
				$role   = get_post_meta( $post_id, Meta::meta_key( 'accent_role' ), true );
				$labels = Meta::accent_role_labels();
				echo esc_html( isset( $labels[ $role ] ) ? $labels[ $role ] : '' );
				break;

			case 'fs_order':
				echo esc_html( get_post_field( 'menu_order', $post_id ) );
				break;

			case 'fs_modified':
				echo esc_html( get_the_modified_date( '', $post_id ) );
				break;
		}
	}
}
