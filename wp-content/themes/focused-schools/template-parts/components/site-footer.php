<?php
/**
 * Component: Footer navigation structure.
 *
 * No args — pulls the 'footer' registered nav menu and Site Settings
 * (contact info, social URLs, footer text, copyright name), falling back
 * gracefully if the focused-schools-core plugin is inactive.
 *
 * 4-column layout per the approved .dc design (Brand / Explore / Resources /
 * Connect): "Explore" is the existing 'footer' nav menu (whatever the admin
 * has assigned). "Resources" (Podcast/Blog/Contact) is internal cross-links
 * via home_url() — matching how every other internal CTA in this theme is
 * already built (Home/About templates), not a second nav menu location.
 * "Connect" is Site Settings contact info + socials.
 *
 * The footer-widgets sidebar (inc/setup.php) is still registered but no
 * longer rendered here — the approved design's Resources column is
 * content, not admin-widget-driven. Left registered rather than removed, in
 * case a future task needs it.
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
<img class="fs-site-footer__watermark" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg' ); ?>" alt="" aria-hidden="true" />

<div class="fs-site-footer__grid fs-container fs-container--shell">
	<div class="fs-site-footer__brand">
		<?php if ( has_custom_logo() ) : ?>
			<?php
			echo wp_get_attachment_image(
				get_theme_mod( 'custom_logo' ),
				'full',
				false,
				array(
					'class' => 'fs-site-footer__logo-img',
					'alt'   => get_bloginfo( 'name' ),
				)
			);
			?>
		<?php else : ?>
			<p class="fs-site-footer__brand-name"><?php bloginfo( 'name' ); ?></p>
		<?php endif; ?>
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

	<nav class="fs-site-footer__col fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Footer navigation', 'focused-schools' ); ?>">
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

	<nav class="fs-site-footer__col fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Resources', 'focused-schools' ); ?>">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Resources', 'focused-schools' ); ?></p>
		<?php
		if ( has_nav_menu( 'footer_resources' ) ) :
			wp_nav_menu(
				array(
					'theme_location' => 'footer_resources',
					'container'      => '',
					'depth'          => 1,
					'menu_class'     => 'fs-nav__list',
					'fallback_cb'    => false,
				)
			);
		else :
			/*
			 * No menu assigned yet, so the column keeps the links it has
			 * always shown. Assigning a Footer Resources menu in
			 * Appearance > Menus takes over from here, exactly as Explore
			 * works — nothing changes until someone chooses to change it.
			 */
			?>
			<div class="fs-nav__list">
				<a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><?php esc_html_e( 'Podcast', 'focused-schools' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'focused-schools' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'focused-schools' ); ?></a>
			</div>
			<?php
		endif;
		?>
	</nav>

	<div class="fs-site-footer__col fs-site-footer__connect">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Connect', 'focused-schools' ); ?></p>
		<?php if ( $fs_email ) : ?>
			<a class="fs-site-footer__line" href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
		<?php endif; ?>
		<?php if ( $fs_phone ) : ?>
			<a class="fs-site-footer__line" href="<?php echo esc_url( 'tel:' . $fs_phone_href ); ?>"><?php echo esc_html( $fs_phone ); ?></a>
		<?php endif; ?>
		<?php if ( array_filter( wp_list_pluck( $fs_socials, 'url' ) ) ) : ?>
			<div class="fs-site-footer__social" aria-label="<?php esc_attr_e( 'Social media', 'focused-schools' ); ?>">
				<?php
				foreach ( $fs_socials as $fs_key => $fs_social ) :
					if ( empty( $fs_social['url'] ) ) {
						continue;
					}
					?>
					<a href="<?php echo esc_url( $fs_social['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $fs_social['label'] ); ?>">
						<?php echo esc_html( 'facebook' === $fs_key ? 'f' : ( 'linkedin' === $fs_key ? 'in' : substr( $fs_social['label'], 0, 1 ) ) ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="fs-site-footer__base">
	<div class="fs-container fs-container--shell fs-site-footer__base-inner">
		<p class="fs-site-footer__copyright">
			&copy;<?php echo esc_html( gmdate( 'Y' ) ); ?>
			<?php echo esc_html( $fs_copyright ? $fs_copyright : get_bloginfo( 'name' ) ); ?>.
			<?php esc_html_e( 'All Rights Reserved.', 'focused-schools' ); ?>
		</p>
		<a class="fs-site-footer__back-to-top" href="#top"><?php esc_html_e( 'Back to top', 'focused-schools' ); ?> &uarr;</a>
	</div>
</div>
