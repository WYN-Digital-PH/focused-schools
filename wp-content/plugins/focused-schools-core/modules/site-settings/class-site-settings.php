<?php
/**
 * Site Settings module.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Module_Interface;
use FocusedSchoolsCore\Modules\Site_Settings\Fields;

defined( 'ABSPATH' ) || exit;

/**
 * Site_Settings.
 *
 * Registers a single grouped options page ("Focused Schools" -> "Site
 * Settings") backed by one namespaced option array, using the native
 * WordPress Settings API and Options API. No ACF, no custom tables.
 */
class Site_Settings implements Module_Interface {

	/**
	 * Option name storing the full settings array.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'focused_schools_site_settings';

	/**
	 * Settings API option group.
	 *
	 * @var string
	 */
	const SETTINGS_GROUP = 'focused_schools_site_settings_group';

	/**
	 * Admin page slug (also used as the parent menu slug).
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'focused-schools-site-settings';

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add the "Focused Schools" top-level menu and "Site Settings" page.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		add_menu_page(
			__( 'Focused Schools', 'focused-schools-core' ),
			__( 'Focused Schools', 'focused-schools-core' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' ),
			'dashicons-welcome-learn-more',
			80
		);

		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Site Settings', 'focused-schools-core' ),
			__( 'Site Settings', 'focused-schools-core' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the setting, sections, and fields with the Settings API.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			self::SETTINGS_GROUP,
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( Fields::class, 'sanitize' ),
				'default'           => Fields::defaults(),
			)
		);

		foreach ( Fields::sections() as $section_key => $section_label ) {
			add_settings_section(
				'focused_schools_section_' . $section_key,
				esc_html( $section_label ),
				'__return_false',
				self::PAGE_SLUG
			);
		}

		foreach ( Fields::all() as $field_key => $field ) {
			add_settings_field(
				'focused_schools_field_' . $field_key,
				esc_html( $field['label'] ),
				array( $this, 'render_field' ),
				self::PAGE_SLUG,
				'focused_schools_section_' . $field['section'],
				array(
					'field_key' => $field_key,
					'field'     => $field,
				)
			);
		}
	}

	/**
	 * Render the settings page shell.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'focused-schools-core' ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Site Settings', 'focused-schools-core' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::SETTINGS_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a single settings field.
	 *
	 * @param array $args {
	 *     @type string $field_key Field key within the option array.
	 *     @type array  $field     Field definition from Fields::all().
	 * }
	 * @return void
	 */
	public function render_field( $args ) {
		$field_key = $args['field_key'];
		$field     = $args['field'];
		$settings  = wp_parse_args( get_option( self::OPTION_NAME, array() ), Fields::defaults() );
		$value     = $settings[ $field_key ];
		$name      = sprintf( '%s[%s]', self::OPTION_NAME, $field_key );
		$id        = sprintf( 'focused_schools_field_%s', $field_key );

		switch ( $field['type'] ) {
			case 'textarea':
				printf(
					'<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_textarea( $value )
				);
				break;

			case 'url':
				printf(
					'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_url( $value )
				);
				break;

			case 'email':
				printf(
					'<input type="email" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value )
				);
				break;

			default:
				printf(
					'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value )
				);
				break;
		}

		if ( ! empty( $field['description'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $field['description'] ) );
		}
	}
}
