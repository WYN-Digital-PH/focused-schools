<?php
/**
 * Podcast module: feature-flagged YouTube playlist integration.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules;

use FocusedSchoolsCore\Integrations\Youtube_Playlist;
use FocusedSchoolsCore\Module_Interface;
use FocusedSchoolsCore\Modules\Podcast\Buzzsprout_Feed;
use FocusedSchoolsCore\Modules\Podcast\Fields;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Podcast.
 *
 * Owns the YouTube playlist settings (feature flag, playlist ID, max videos,
 * cache duration via the Settings API) and the admin-only manual refresh. The
 * fetching, normalizing and caching live in
 * FocusedSchoolsCore\Integrations\Youtube_Playlist; the theme reads the result
 * through focused_schools_get_podcast_playlist() (includes/functions.php). Does
 * not register a post type or render any markup.
 *
 * Never modifies Buzzsprout embed handling; entirely separate data source.
 */
class Podcast implements Module_Interface {

	/**
	 * Option name storing the settings array (flag, playlist ID, max videos, cache duration).
	 *
	 * @var string
	 */
	const OPTION_NAME = 'focused_schools_podcast_settings';

	/**
	 * Settings API option group.
	 *
	 * @var string
	 */
	const SETTINGS_GROUP = 'focused_schools_podcast_settings_group';

	/**
	 * Admin submenu page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'focused-schools-podcast';

	/**
	 * Admin-post action name for the manual refresh button.
	 *
	 * @var string
	 */
	const REFRESH_ACTION = 'fs_podcast_refresh_cache';

	/**
	 * Nonce field name for the manual refresh form.
	 *
	 * @var string
	 */
	const REFRESH_NONCE_FIELD = 'fs_podcast_refresh_nonce';

	/**
	 * Short-lived transient holding the last manual refresh's error message, if any.
	 *
	 * Avoids round-tripping arbitrary error text through a redirect URL.
	 *
	 * @var string
	 */
	const LAST_ERROR_TRANSIENT = 'focused_schools_podcast_last_error';

	/**
	 * Register module hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_post_' . self::REFRESH_ACTION, array( $this, 'handle_manual_refresh' ) );
		add_action( 'admin_notices', array( $this, 'render_admin_notices' ) );

		Buzzsprout_Feed::register();
		Youtube_Playlist::register();
	}

	/**
	 * Get the sanitized settings, merged with defaults.
	 *
	 * @return array{enabled:bool,playlist_id:string,max_videos:int,cache_duration:int}
	 */
	private static function get_settings() {
		return wp_parse_args( get_option( self::OPTION_NAME, array() ), Fields::defaults() );
	}

	/**
	 * Add the "Podcast (YouTube)" submenu page under the shared Focused Schools menu.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		add_submenu_page(
			Site_Settings::PAGE_SLUG,
			__( 'Podcast (YouTube)', 'focused-schools-core' ),
			__( 'Podcast (YouTube)', 'focused-schools-core' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the setting, section, and fields with the Settings API.
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

		add_settings_section(
			'focused_schools_podcast_section',
			esc_html__( 'YouTube Playlist', 'focused-schools-core' ),
			'__return_false',
			self::PAGE_SLUG
		);

		foreach ( Fields::all() as $field_key => $field ) {
			add_settings_field(
				'focused_schools_podcast_field_' . $field_key,
				esc_html( $field['label'] ),
				array( $this, 'render_field' ),
				self::PAGE_SLUG,
				'focused_schools_podcast_section',
				array(
					'field_key' => $field_key,
					'field'     => $field,
				)
			);
		}
	}

	/**
	 * Render the settings page: the Settings API form plus a manual refresh action.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'focused-schools-core' ) );
		}

		$fetched_at = Youtube_Playlist::get_last_fetched_at();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Podcast (YouTube Playlist)', 'focused-schools-core' ); ?></h1>

			<?php if ( '' === Youtube_Playlist::get_api_key() ) : ?>
				<div class="notice notice-warning">
					<p>
						<?php esc_html_e( 'FOCUSED_SCHOOLS_YOUTUBE_API_KEY is not defined in wp-config.php or the environment. Live fetches will fail until it is set; cached/last-known-good data (if any) will keep serving on the frontend.', 'focused-schools-core' ); ?>
					</p>
				</div>
			<?php endif; ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( self::SETTINGS_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button( __( 'Save Settings', 'focused-schools-core' ) );
				?>
			</form>

			<hr />

			<h2><?php esc_html_e( 'Cache', 'focused-schools-core' ); ?></h2>
			<p>
				<?php
				if ( $fetched_at ) {
					printf(
						/* translators: %s: human-readable date/time of the last successful fetch. */
						esc_html__( 'Last successful fetch: %s', 'focused-schools-core' ),
						esc_html( wp_date( 'Y-m-d H:i:s', $fetched_at ) )
					);
				} else {
					esc_html_e( 'No successful fetch yet.', 'focused-schools-core' );
				}
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="<?php echo esc_attr( self::REFRESH_ACTION ); ?>" />
				<?php wp_nonce_field( self::REFRESH_ACTION, self::REFRESH_NONCE_FIELD ); ?>
				<?php submit_button( __( 'Refresh Now', 'focused-schools-core' ), 'secondary' ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a single settings field.
	 *
	 * @param array $args Field render arguments: 'field_key' (string, the field key within
	 *                    the option array) and 'field' (array, the field definition from
	 *                    Fields::all()).
	 * @return void
	 */
	public function render_field( $args ) {
		$field_key = $args['field_key'];
		$field     = $args['field'];
		$settings  = self::get_settings();
		$value     = $settings[ $field_key ];
		$name      = sprintf( '%s[%s]', self::OPTION_NAME, $field_key );
		$id        = sprintf( 'focused_schools_podcast_field_%s', $field_key );

		switch ( $field['type'] ) {
			case 'checkbox':
				printf(
					'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> %4$s</label>',
					esc_attr( $id ),
					esc_attr( $name ),
					checked( $value, true, false ),
					esc_html__( 'Enabled', 'focused-schools-core' )
				);
				break;

			case 'number':
				printf(
					'<input type="number" id="%1$s" name="%2$s" value="%3$s" class="small-text" min="0" />',
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

	/**
	 * Handle the admin-post "Refresh Now" action.
	 *
	 * Strict capability + nonce checks, per the task's explicit requirement.
	 *
	 * @return void
	 */
	public function handle_manual_refresh() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'focused-schools-core' ), 403 );
		}

		$nonce = isset( $_POST[ self::REFRESH_NONCE_FIELD ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::REFRESH_NONCE_FIELD ] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, self::REFRESH_ACTION ) ) {
			wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'focused-schools-core' ), 403 );
		}

		Buzzsprout_Feed::refresh();

		Youtube_Playlist::purge_cache();
		$result = Youtube_Playlist::refresh();

		$redirect_args = array( 'page' => self::PAGE_SLUG );
		if ( is_wp_error( $result ) ) {
			$redirect_args['fs_podcast_refresh'] = 'error';
			set_transient( self::LAST_ERROR_TRANSIENT, $result->get_error_message(), 60 );
		} else {
			$redirect_args['fs_podcast_refresh'] = 'success';
			$redirect_args['fs_podcast_count']   = count( $result );
		}

		wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Show a one-time admin notice reflecting the last manual refresh's outcome.
	 *
	 * Reads GET params set by our own wp_safe_redirect() in
	 * handle_manual_refresh() immediately after that nonce-verified POST —
	 * this is a read-only display, not a state-changing action.
	 *
	 * @return void
	 */
	public function render_admin_notices() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display after our own nonce-verified redirect in handle_manual_refresh(); no state change happens here.
		if ( ! isset( $_GET['page'], $_GET['fs_podcast_refresh'] ) || self::PAGE_SLUG !== $_GET['page'] ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display, see above.
		$status = sanitize_text_field( wp_unslash( $_GET['fs_podcast_refresh'] ) );

		if ( 'success' === $status ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display, see above.
			$count = isset( $_GET['fs_podcast_count'] ) ? (int) $_GET['fs_podcast_count'] : 0;
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %d: number of videos fetched. */
						_n( 'Refreshed successfully: %d video cached.', 'Refreshed successfully: %d videos cached.', $count, 'focused-schools-core' ),
						$count
					)
				)
			);
		} elseif ( 'error' === $status ) {
			$message = (string) get_transient( self::LAST_ERROR_TRANSIENT );
			delete_transient( self::LAST_ERROR_TRANSIENT );
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %s: error message. */
						__( 'Refresh failed: %s Existing cached data (if any) is unaffected.', 'focused-schools-core' ),
						$message
					)
				)
			);
		}
	}
}
