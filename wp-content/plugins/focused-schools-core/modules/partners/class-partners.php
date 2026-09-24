<?php
/**
 * Partners module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Partners.
 *
 * Registers the fs_partner post type (a single partner district) and its
 * fs_partner_state taxonomy, per the approved .dc design's own spec for the
 * About page's Partner Districts directory: "fs_partner CPT with a state
 * taxonomy; the template loops states alphabetically, then districts within
 * each... adding a sixth state adds a row, no template change." Admin-only,
 * no public URLs — the same pattern as Team/Services. No custom meta:
 * post_title is the district name, menu_order orders districts within a
 * state, and the state taxonomy term supplies both the grouping and its
 * display name.
 */
class Partners implements Module_Interface {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'fs_partner';

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	const TAXONOMY = 'fs_partner_state';

	/**
	 * Post meta holding whether a district is a current or previous partner.
	 *
	 * @var string
	 */
	const STATUS_META = '_fs_partner_status';

	/**
	 * Term meta holding the state's map coordinates.
	 *
	 * @var string
	 */
	const LAT_META = '_fs_partner_state_lat';

	/**
	 * Term meta holding the state's map coordinates.
	 *
	 * @var string
	 */
	const LNG_META = '_fs_partner_state_lng';

	/**
	 * Nonce action for the status meta box.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'focused_schools_save_partner_status';

	/**
	 * Nonce field name for the status meta box.
	 *
	 * @var string
	 */
	const NONCE_NAME = 'focused_schools_partner_status_nonce';

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_meta' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'admin_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );

		// Coordinates live on the state term, so one edit moves every pin for
		// that state and nothing has to be repeated per district.
		add_action( self::TAXONOMY . '_add_form_fields', array( $this, 'render_term_fields' ) );
		add_action( self::TAXONOMY . '_edit_form_fields', array( $this, 'render_term_fields' ) );
		add_action( 'created_' . self::TAXONOMY, array( $this, 'save_term_fields' ) );
		add_action( 'edited_' . self::TAXONOMY, array( $this, 'save_term_fields' ) );
	}

	/**
	 * Register the partner status meta.
	 *
	 * @return void
	 */
	public function register_meta() {
		register_post_meta(
			self::POST_TYPE,
			self::STATUS_META,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => 'current',
				'show_in_rest'      => array(
					'schema' => array(
						'type' => 'string',
						'enum' => array( 'current', 'previous' ),
					),
				),
				'sanitize_callback' => array( self::class, 'sanitize_status' ),
				'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
	}

	/**
	 * Strictly whitelist the partner status.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_status( $value ) {
		return in_array( $value, array( 'current', 'previous' ), true ) ? $value : 'current';
	}

	/**
	 * Add the partner status meta box.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'focused_schools_partner_status',
			__( 'Partnership Status', 'focused-schools-core' ),
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'side'
		);
	}

	/**
	 * Render the partner status meta box.
	 *
	 * @param \WP_Post $post Current post.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		$current = self::sanitize_status( get_post_meta( $post->ID, self::STATUS_META, true ) );
		$labels  = array(
			'current'  => __( 'Current partner', 'focused-schools-core' ),
			'previous' => __( 'Previous partner', 'focused-schools-core' ),
		);

		printf( '<select name="%s" class="widefat">', esc_attr( self::STATUS_META ) );

		foreach ( $labels as $value => $label ) {
			printf(
				'<option value="%1$s"%2$s>%3$s</option>',
				esc_attr( $value ),
				selected( $current, $value, false ),
				esc_html( $label )
			);
		}

		echo '</select>';
		printf(
			'<p class="description">%s</p>',
			esc_html__( 'Drives the partner map legend. Previous partners still appear, in the muted pin colour.', 'focused-schools-core' )
		);
	}

	/**
	 * Save the partner status.
	 *
	 * @param int $post_id Post being saved.
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

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST[ self::STATUS_META ] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_POST[ self::STATUS_META ] ) );
			update_post_meta( $post_id, self::STATUS_META, self::sanitize_status( $raw ) );
		}
	}

	/**
	 * Render the latitude/longitude fields on the state term screens.
	 *
	 * @param \WP_Term|string $term Term being edited, or the taxonomy slug on the add form.
	 * @return void
	 */
	public function render_term_fields( $term ) {
		$is_edit = $term instanceof \WP_Term;
		$lat     = $is_edit ? get_term_meta( $term->term_id, self::LAT_META, true ) : '';
		$lng     = $is_edit ? get_term_meta( $term->term_id, self::LNG_META, true ) : '';

		$fields = array(
			self::LAT_META => array( __( 'Latitude', 'focused-schools-core' ), $lat ),
			self::LNG_META => array( __( 'Longitude', 'focused-schools-core' ), $lng ),
		);

		foreach ( $fields as $key => $field ) {
			list( $label, $value ) = $field;

			if ( $is_edit ) {
				printf(
					'<tr class="form-field"><th scope="row"><label for="%1$s">%2$s</label></th><td><input type="text" id="%1$s" name="%1$s" value="%3$s" /><p class="description">%4$s</p></td></tr>',
					esc_attr( $key ),
					esc_html( $label ),
					esc_attr( $value ),
					esc_html__( 'Decimal degrees. Leave both empty to keep this state off the map.', 'focused-schools-core' )
				);
			} else {
				printf(
					'<div class="form-field"><label for="%1$s">%2$s</label><input type="text" id="%1$s" name="%1$s" value="" /><p>%3$s</p></div>',
					esc_attr( $key ),
					esc_html( $label ),
					esc_html__( 'Decimal degrees. Leave both empty to keep this state off the map.', 'focused-schools-core' )
				);
			}
		}
	}

	/**
	 * Save the term coordinates.
	 *
	 * @param int $term_id Term being saved.
	 * @return void
	 */
	public function save_term_fields( $term_id ) {
		if ( ! current_user_can( 'manage_categories' ) ) {
			return;
		}

		foreach ( array( self::LAT_META, self::LNG_META ) as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verifies the term-form nonce before these hooks fire.
				continue;
			}

			$raw = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- see above.

			if ( '' === $raw ) {
				delete_term_meta( $term_id, $key );
				continue;
			}

			// Coordinates only: a float, nothing else.
			update_term_meta( $term_id, $key, (float) $raw );
		}
	}

	/**
	 * Register the fs_partner post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Partner Districts', 'focused-schools-core' ),
			'singular_name'      => __( 'Partner District', 'focused-schools-core' ),
			'menu_name'          => __( 'Partner Districts', 'focused-schools-core' ),
			'all_items'          => __( 'Partner Districts', 'focused-schools-core' ),
			'add_new'            => __( 'Add New', 'focused-schools-core' ),
			'add_new_item'       => __( 'Add New Partner District', 'focused-schools-core' ),
			'edit_item'          => __( 'Edit Partner District', 'focused-schools-core' ),
			'new_item'           => __( 'New Partner District', 'focused-schools-core' ),
			'view_item'          => __( 'View Partner District', 'focused-schools-core' ),
			'search_items'       => __( 'Search Partner Districts', 'focused-schools-core' ),
			'not_found'          => __( 'No partner districts found', 'focused-schools-core' ),
			'not_found_in_trash' => __( 'No partner districts found in Trash', 'focused-schools-core' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'             => $labels,
				'supports'           => array( 'title', 'page-attributes' ),
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
	 * Register the fs_partner_state taxonomy.
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		register_taxonomy(
			self::TAXONOMY,
			self::POST_TYPE,
			array(
				'labels'            => array(
					'name'          => __( 'States', 'focused-schools-core' ),
					'singular_name' => __( 'State', 'focused-schools-core' ),
					'menu_name'     => __( 'States', 'focused-schools-core' ),
				),
				'hierarchical'      => true,
				'public'            => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => false,
				'query_var'         => false,
			)
		);
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

			if ( 'title' === $key ) {
				$new_columns[ $key ] = __( 'District', 'focused-schools-core' );
				continue;
			}

			$new_columns[ $key ] = $label;
		}

		$new_columns['fs_order'] = __( 'Order', 'focused-schools-core' );

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
		if ( 'fs_order' === $column ) {
			echo esc_html( get_post_field( 'menu_order', $post_id ) );
		}
	}
}
