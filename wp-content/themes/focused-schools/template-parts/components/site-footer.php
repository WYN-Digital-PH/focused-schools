<?php
/**
 * Component: Footer navigation structure.
 *
 * No args — pulls the 'footer' registered nav menu and Site Settings
 * (contact info, social URLs, footer text, copyright name), falling back
 * gracefully if the focused-schools-core plugin is inactive.
 *
 * 4-column layout per the approved .dc design (Brand / Explore / Resources /
 * Connect): "Explore" is the 'footer' nav menu if one is assigned, falling
 * back to 'primary' so this column is never an empty-looking blank space —
 * the approved design's Explore list mirrors the primary nav anyway.
 * "Resources" (Podcast/Blog/Contact) is internal cross-links via home_url()
 * — matching how every other internal CTA in this theme is already built
 * (Home/About templates), not a second nav menu location. "Connect" is Site
 * Settings contact info + socials. The brand CTA ("Contact Us" -> /contact/)
 * is this component's own fixed copy per the .dc source, not the shared
 * Site Settings CTA the header/hero use (which has different text there).
 *
 * The footer-widgets sidebar (inc/setup.php) is still registered but no
 * longer rendered here — the approved design's Resources column is fixed
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
$fs_phone_href   = $fs_phone ? preg_replace( '/[^0-9+]/', '', $fs_phone ) : '';

// The footer's own brand CTA ("Contact Us" -> /contact/) is fixed per the
// approved .dc design, distinct from the header's/hero's separately
// configurable Site Settings CTA — same "hardcode page-chrome-specific
// copy" convention used for every other component's fixed button text.
$fs_footer_cta_label = __( 'Contact Us', 'focused-schools' );
$fs_footer_cta_url   = home_url( '/contact/' );

// "Explore" (nav) order matches the .dc source's social row: Facebook,
// YouTube, LinkedIn (there is no X/Twitter Site Settings field yet).
$fs_socials = array(
	'facebook' => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'facebook_url' ) : '',
		'label' => __( 'Facebook', 'focused-schools' ),
		'glyph' => 'f',
	),
	'youtube'  => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'youtube_url' ) : '',
		'label' => __( 'YouTube', 'focused-schools' ),
		'glyph' => '▶',
	),
	'linkedin' => array(
		'url'   => $fs_has_settings ? focused_schools_get_setting( 'linkedin_url' ) : '',
		'label' => __( 'LinkedIn', 'focused-schools' ),
		'glyph' => 'in',
	),
);

// Fall back to the 'primary' menu when no 'footer' menu is assigned, so
// "Explore" never renders as an empty column — the approved design's
// Explore list mirrors the primary nav anyway.
$fs_footer_menu_location = has_nav_menu( 'footer' ) ? 'footer' : 'primary';
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
			<img class="fs-site-footer__logo-img" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/full-logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
		<?php endif; ?>
		<?php if ( $fs_footer_text ) : ?>
			<p class="fs-site-footer__text"><?php echo esc_html( $fs_footer_text ); ?></p>
		<?php endif; ?>
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'label' => $fs_footer_cta_label,
				'url'   => $fs_footer_cta_url,
				'style' => 'primary',
			)
		);
		?>
	</div>

	<nav class="fs-site-footer__col fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Footer navigation', 'focused-schools' ); ?>">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Explore', 'focused-schools' ); ?></p>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => $fs_footer_menu_location,
				'container'      => false,
				'fallback_cb'    => false,
				'menu_class'     => 'fs-nav__list',
			)
		);
		?>
	</nav>

	<nav class="fs-site-footer__col fs-nav" aria-label="<?php esc_attr_e( 'Resources', 'focused-schools' ); ?>">
		<p class="fs-site-footer__col-heading"><?php esc_html_e( 'Resources', 'focused-schools' ); ?></p>
		<div class="fs-nav__list">
			<a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><?php esc_html_e( 'Podcast', 'focused-schools' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'focused-schools' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'focused-schools' ); ?></a>
		</div>
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
				foreach ( $fs_socials as $fs_social ) :
					if ( empty( $fs_social['url'] ) ) {
						continue;
					}
					?>
					<a href="<?php echo esc_url( $fs_social['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $fs_social['label'] ); ?>">
						<?php echo esc_html( $fs_social['glyph'] ); ?>
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
