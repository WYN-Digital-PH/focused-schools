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
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'admin_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );
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
