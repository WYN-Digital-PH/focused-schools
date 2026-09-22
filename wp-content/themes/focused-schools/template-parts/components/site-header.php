<?php
/**
 * Component: Header navigation structure.
 *
 * No args — pulls the 'primary' registered nav menu and Site Settings (CTA,
 * email, social links). Falls back gracefully if the focused-schools-core
 * plugin is inactive.
 *
 * The toggled panel (assets/js/components/site-header.js) carries both the
 * nav list and a contact/social "aside" — one accessible, already-tested
 * toggle mechanism (aria-expanded, Escape-to-close, focus trap, body-scroll
 * lock) rather than a second parallel off-canvas dialog system.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_cta_label = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '';
$fs_cta_url   = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '';
$fs_email     = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'email' ) : '';

$fs_socials = array(
	'facebook' => array(
		'url'   => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'facebook_url' ) : '',
		'label' => __( 'Facebook', 'focused-schools' ),
	),
	'linkedin' => array(
		'url'   => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'linkedin_url' ) : '',
		'label' => __( 'LinkedIn', 'focused-schools' ),
	),
	'youtube'  => array(
		'url'   => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'youtube_url' ) : '',
		'label' => __( 'YouTube', 'focused-schools' ),
	),
);
?>
<div class="fs-site-header__inner fs-container">
	<div class="fs-site-header__brand">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-site-header__logo">
			<?php bloginfo( 'name' ); ?>
		</a>
	</div>

	<button
		type="button"
		class="fs-site-header__toggle"
		aria-expanded="false"
		aria-controls="fs-primary-menu"
		data-fs-nav-toggle
	>
		<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'focused-schools' ); ?></span>
		<span class="fs-site-header__toggle-icon" aria-hidden="true"></span>
	</button>

	<nav class="fs-nav fs-nav--primary" id="fs-primary-menu" aria-label="<?php esc_attr_e( 'Primary', 'focused-schools' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => false,
				'menu_class'     => 'fs-nav__list',
			)
		);
		?>

		<?php if ( $fs_email || array_filter( wp_list_pluck( $fs_socials, 'url' ) ) ) : ?>
			<div class="fs-nav--primary__aside">
				<?php if ( $fs_email ) : ?>
					<a class="fs-nav--primary__email" href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
				<?php endif; ?>
				<?php if ( array_filter( wp_list_pluck( $fs_socials, 'url' ) ) ) : ?>
					<ul class="fs-nav--primary__socials" aria-label="<?php esc_attr_e( 'Social media', 'focused-schools' ); ?>">
						<?php
						foreach ( $fs_socials as $fs_social ) :
							if ( empty( $fs_social['url'] ) ) {
								continue;
							}
							?>
							<li>
								<a href="<?php echo esc_url( $fs_social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo esc_html( $fs_social['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</nav>

	<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
		<div class="fs-site-header__cta">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
					'style' => 'primary',
				)
			);
			?>
		</div>
	<?php endif; ?>
</div>
