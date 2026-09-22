<?php
/**
 * Component: Footer navigation structure.
 *
 * No args — pulls the 'footer' registered nav menu, the footer-widgets
 * sidebar (inc/setup.php), and Site Settings (contact info, social URLs,
 * footer text, copyright name), falling back gracefully if the
 * focused-schools-core plugin is inactive.
 *
 * 3-column layout (Explore / Resources / Connect): "Explore" is the
 * existing 'footer' nav menu (whatever the admin has assigned — never
 * hardcoded), "Resources" is the existing footer-widgets sidebar (renders
 * only if a widget is actually assigned to it), and "Connect" is Site
 * Settings contact info + socials. All three reuse infrastructure that
 * already existed before this task — no new nav menu location was
 * registered.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_has_settings = function_exists( 'focused_schools_get_setting' );
$fs_footer_text  = $fs_has_settings ? focused_schools_get_setting( 'footer_text' ) : '';
$fs_copyright    = $fs_has_settings ? focused_schools_get_setting( 'copyright_name' ) : '';
$fs_email        = $fs_has_settings ? focused_schools_get_setting( 'email' ) : '';
$fs_phone        = $fs_has_settings ? focused_schools_get_setting( 'phone' ) : '';
$fs_cta_label    = $fs_has_settings ? focused_schools_get_setting( 'cta_label' ) : '';
$fs_cta_url      = $fs_has_settings ? focused_schools_get_setting( 'cta_url' ) : '';
$fs_phone_href   = $fs_phone ? preg_replace( '/[^0-9+]/', '', $fs_phone ) : '';

$fs_socials = array(
	'facebook' => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'facebook_url' ) : '',
		'label' => __( 'Facebook', 'focused-schools' ),
	),
	'linkedin' => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'linkedin_url' ) : '',
		'label' => __( 'LinkedIn', 'focused-schools' ),
	),
	'youtube'  => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'youtube_url' ) : '',
		'label' => __( 'YouTube', 'focused-schools' ),
	),
);
?>
<div class="fs-site-footer__grid fs-container">
	<div class="fs-site-footer__brand">
		<p class="fs-site-footer__brand-name"><?php bloginfo( 'name' ); ?></p>
		<?php if ( $fs_footer_text ) : ?>
			<p class="fs-site-footer__text"><?php echo esc_html( $fs_footer_text ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
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
		<?php endif; ?>
	</div>

	<nav class="fs-site-footer__col fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Footer', 'focused-schools' ); ?>">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Explore', 'focused-schools' ); ?></p>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'fallback_cb'    => false,
				'menu_class'     => 'fs-nav__list',
			)
		);
		?>
	</nav>

	<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
		<div class="fs-site-footer__col fs-site-footer__widgets">
			<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Resources', 'focused-schools' ); ?></p>
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
		</div>
	<?php endif; ?>

	<div class="fs-site-footer__col fs-site-footer__connect">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Connect', 'focused-schools' ); ?></p>
		<?php if ( $fs_email ) : ?>
			<a class="fs-site-footer__line" href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
		<?php endif; ?>
		<?php if ( $fs_phone ) : ?>
			<a class="fs-site-footer__line" href="<?php echo esc_url( 'tel:' . $fs_phone_href ); ?>"><?php echo esc_html( $fs_phone ); ?></a>
		<?php endif; ?>
		<?php if ( array_filter( wp_list_pluck( $fs_socials, 'url' ) ) ) : ?>
			<ul class="fs-site-footer__social">
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
</div>

<div class="fs-site-footer__base fs-container">
	<p class="fs-site-footer__copyright">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
		<?php echo esc_html( $fs_copyright ? $fs_copyright : get_bloginfo( 'name' ) ); ?>.
		<?php esc_html_e( 'All rights reserved.', 'focused-schools' ); ?>
	</p>
	<a class="fs-site-footer__back-to-top" href="#top"><?php esc_html_e( 'Back to top', 'focused-schools' ); ?> &uarr;</a>
</div>
