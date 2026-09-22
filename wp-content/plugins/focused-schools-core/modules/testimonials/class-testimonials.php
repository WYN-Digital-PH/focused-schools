<?php
/**
 * Testimonials module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Testimonials.
 *
 * Registers the fs_testimonial post type: an admin-only content type (no
 * public URLs/archive) for the Home page's testimonial carousel. No custom
 * meta fields — the post title is the attribution (e.g. "Superintendent"),
 * and the post content (editor) is the quote itself, so this needs no
 * meta box, only the standard post editor. No ACF, no custom tables.
 */
class Testimonials implements Module_Interface {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'fs_testimonial';

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'admin_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );
	}

	/**
	 * Register the fs_testimonial post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Testimonials', 'focused-schools-core' ),
			'singular_name'      => __( 'Testimonial', 'focused-schools-core' ),
			'menu_name'          => __( 'Testimonials', 'focused-schools-core' ),
			'all_items'          => __( 'Testimonials', 'focused-schools-core' ),
			'add_new'            => __( 'Add New', 'focused-schools-core' ),
			'add_new_item'       => __( 'Add New Testimonial', 'focused-schools-core' ),
			'edit_item'          => __( 'Edit Testimonial', 'focused-schools-core' ),
			'new_item'           => __( 'New Testimonial', 'focused-schools-core' ),
			'view_item'          => __( 'View Testimonial', 'focused-schools-core' ),
			'search_items'       => __( 'Search Testimonials', 'focused-schools-core' ),
			'not_found'          => __( 'No testimonials found', 'focused-schools-core' ),
			'not_found_in_trash' => __( 'No testimonials found in Trash', 'focused-schools-core' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'             => $labels,
				'supports'           => array( 'title', 'editor', 'page-attributes' ),
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
				$new_columns[ $key ] = __( 'Attribution', 'focused-schools-core' );
				continue;
			}

			$new_columns[ $key ] = $label;
		}

		$new_columns['fs_quote']    = __( 'Quote', 'focused-schools-core' );
		$new_columns['fs_order']    = __( 'Order', 'focused-schools-core' );
		$new_columns['fs_modified'] = __( 'Modified Date', 'focused-schools-core' );

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
			case 'fs_quote':
				echo esc_html( wp_trim_words( get_post_field( 'post_content', $post_id ), 12 ) );
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
