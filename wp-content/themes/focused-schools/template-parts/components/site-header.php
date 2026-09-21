<?php
/**
 * Component: Header navigation structure.
 *
 * No args — pulls the 'primary' registered nav menu and the Site Settings
 * primary CTA (business-name-independent; falls back gracefully if the
 * focused-schools-core plugin is inactive).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_cta_label = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '';
$fs_cta_url   = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '';
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
