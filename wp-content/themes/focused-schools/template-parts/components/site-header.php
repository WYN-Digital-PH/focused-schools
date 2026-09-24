<?php
/**
 * Component: Header navigation structure.
 *
 * No args — pulls the 'primary' registered nav menu and Site Settings (CTA,
 * email, social links). Falls back gracefully if the focused-schools-core
 * plugin is inactive.
 *
 * Sticky white pill bar (logo, horizontal nav ≥1024px, CTA, an
 * always-visible "Menu" button) plus a dropdown panel the Menu button
 * opens beneath the bar: the same 'primary' nav menu as a flat list (one
 * row per item, hairline dividers, arrow), plus the same Site Settings CTA
 * full-width at the bottom. One real WP nav menu, two presentations — no
 * second nav location registered. Superseded an earlier full-screen
 * "Explore" modal design (2-column numbered grid + teal aside) per the
 * project owner's explicit direction to move to this dropdown instead.
 * Panel semantics (focus trap, Escape-to-close, click-outside-to-close,
 * focus-return to the toggle) match the `hidden`-attribute pattern used
 * elsewhere in this theme, minus body-scroll-lock — a compact dropdown,
 * unlike the video/team-bio/former Explore modals, doesn't cover the
 * viewport and doesn't need scrolling disabled behind it.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_cta_label = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '';
$fs_cta_url   = function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '';

$fs_menu_items = wp_get_nav_menu_items( get_nav_menu_locations()['primary'] ?? 0 );
$fs_menu_items = is_array( $fs_menu_items ) ? $fs_menu_items : array();
?>
<div class="fs-container fs-container--shell">
	<div class="fs-site-header__bar">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-site-header__logo" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s — home', 'focused-schools' ), get_bloginfo( 'name' ) ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'fs-site-header__logo-img' ) ); ?>
			<?php else : ?>
				<img class="fs-site-header__logo-img" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/full-logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
			<?php endif; ?>
		</a>

		<nav class="fs-nav fs-nav--primary" aria-label="<?php esc_attr_e( 'Primary navigation', 'focused-schools' ); ?>">
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

		<button
			type="button"
			class="fs-site-header__toggle"
			aria-expanded="false"
			aria-controls="fs-site-menu"
			aria-label="<?php esc_attr_e( 'Open menu', 'focused-schools' ); ?>"
			data-fs-nav-toggle
		>
			<img src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-1.svg' ); ?>" alt="" width="22" height="22" />
			<span class="fs-site-header__toggle-label"><?php esc_html_e( 'Menu', 'focused-schools' ); ?></span>
			<span class="fs-site-header__toggle-lines" aria-hidden="true"><span></span><span></span></span>
		</button>

		<span class="fs-site-header__progress" data-fs-nav-progress aria-hidden="true"></span>

		<div class="fs-site-nav-panel" id="fs-site-menu" hidden>
			<nav class="fs-site-nav-panel__list" aria-label="<?php esc_attr_e( 'Expanded navigation', 'focused-schools' ); ?>">
				<?php
				foreach ( $fs_menu_items as $fs_item ) :
					$fs_is_current = ! empty( $fs_item->classes ) && in_array( 'current-menu-item', (array) $fs_item->classes, true );
					?>
					<a href="<?php echo esc_url( $fs_item->url ); ?>" class="fs-site-nav-panel__item"<?php echo $fs_is_current ? ' aria-current="page"' : ''; ?>>
						<span><?php echo esc_html( $fs_item->title ); ?></span>
						<span aria-hidden="true">&rarr;</span>
					</a>
				<?php endforeach; ?>
			</nav>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<div class="fs-site-nav-panel__cta">
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
	</div>
</div>
