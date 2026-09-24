<?php
/**
 * Impact Stories module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;
use FocusedSchoolsCore\Modules\Impact_Stories\Meta;
use FocusedSchoolsCore\Modules\Impact_Stories\CLI_Command;

defined( 'ABSPATH' ) || exit;

/**
 * Impact_Stories.
 *
 * Registers the fs_impact_story post type: a public content type with
 * individual story URLs under /impact-stories/{slug}/, but no archive page
 * (the existing /impact-stories/ Page remains the landing page). Also
 * registers a dry-run-by-default WP-CLI command for tagging approved
 * legacy Pages as impact stories. No ACF, no custom tables, no custom REST
 * endpoints beyond native post type REST support.
 */
class Impact_Stories implements Module_Interface {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'fs_impact_story';

	/**
	 * Nonce action for the meta box save.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'focused_schools_save_impact_story_meta';

	/**
	 * Nonce field name for the meta box save.
	 *
	 * @var string
	 */
	const NONCE_NAME = 'focused_schools_impact_story_meta_nonce';

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

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'focused-schools tag-legacy-impact-stories', new CLI_Command() );
		}
	}

	/**
	 * Register the fs_impact_story post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Impact Stories', 'focused-schools-core' ),
			'singular_name'      => __( 'Impact Story', 'focused-schools-core' ),
			'menu_name'          => __( 'Impact Stories', 'focused-schools-core' ),
			'all_items'          => __( 'Impact Stories', 'focused-schools-core' ),
			'add_new'            => __( 'Add New', 'focused-schools-core' ),
			'add_new_item'       => __( 'Add New Impact Story', 'focused-schools-core' ),
			'edit_item'          => __( 'Edit Impact Story', 'focused-schools-core' ),
			'new_item'           => __( 'New Impact Story', 'focused-schools-core' ),
			'view_item'          => __( 'View Impact Story', 'focused-schools-core' ),
			'search_items'       => __( 'Search Impact Stories', 'focused-schools-core' ),
			'not_found'          => __( 'No impact stories found', 'focused-schools-core' ),
			'not_found_in_trash' => __( 'No impact stories found in Trash', 'focused-schools-core' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'             => $labels,
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => Site_Settings::PAGE_SLUG,
				'show_in_rest'       => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'rewrite'            => array(
					'slug'       => 'impact-stories',
					'with_front' => false,
				),
				'query_var'          => true,
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
	 * Add the Impact Story Details meta box.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'focused_schools_impact_story_details',
			__( 'Impact Story Details', 'focused-schools-core' ),
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the Impact Story Details meta box.
	 *
	 * @param \WP_Post $post Current post object.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		foreach ( Meta::all() as $key => $field ) {
			$meta_key = Meta::meta_key( $key );
			$value    = get_post_meta( $post->ID, $meta_key, true );
			$id       = 'focused_schools_impact_story_' . $key;

			if ( 'boolean' === $field['type'] ) {
				echo '<p>';
				printf(
					'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s /> %4$s</label>',
					esc_attr( $id ),
					esc_attr( $meta_key ),
					checked( (bool) $value, true, false ),
					esc_html( $field['label'] )
				);
				echo '</p>';
				continue;
			}

			echo '<p>';
			printf(
				'<label for="%1$s"><strong>%2$s</strong></label><br />',
				esc_attr( $id ),
				esc_html( $field['label'] )
			);

			if ( 'textarea' === $field['type'] ) {
				printf(
					'<textarea id="%1$s" name="%2$s" rows="5" class="large-text">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $meta_key ),
					esc_textarea( $value )
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
	}

	/**
	 * Save the Impact Story Details meta box fields.
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

		foreach ( Meta::all() as $key => $field ) {
			$meta_key = Meta::meta_key( $key );

			if ( 'boolean' === $field['type'] ) {
				// Unchecked checkboxes are absent from $_POST entirely, so
				// treat "not present" as an explicit false, not "skip".
				update_post_meta( $post_id, $meta_key, isset( $_POST[ $meta_key ] ) ? 1 : 0 );
				continue;
			}

			if ( ! isset( $_POST[ $meta_key ] ) ) {
				continue;
			}

			$raw       = wp_unslash( $_POST[ $meta_key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized on the next line via Meta::sanitizer_for().
			$sanitized = call_user_func( Meta::sanitizer_for( $field['type'] ), $raw );

			update_post_meta( $post_id, $meta_key, $sanitized );
		}
	}
}
