<?php
/**
 * Component: Header navigation structure.
 *
 * No args — pulls the 'primary' registered nav menu and Site Settings (CTA,
 * email, social links). Falls back gracefully if the focused-schools-core
 * plugin is inactive.
 *
 * Matches the approved .dc design: a sticky white pill bar (logo, horizontal
 * nav ≥1024px, CTA, an always-visible "Menu" button) plus a full "Explore"
 * overlay the Menu button opens — a two-column grid of the same 'primary'
 * nav items (rendered larger) beside a teal aside with tagline/email/socials.
 * One real WP nav menu, two presentations — no second nav location
 * registered. Accessible mechanics (focus trap, Escape-to-close, body-scroll
 * lock) are unchanged from the previous toggle implementation.
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

$fs_menu_items = wp_get_nav_menu_items( get_nav_menu_locations()['primary'] ?? 0 );
$fs_menu_items = is_array( $fs_menu_items ) ? $fs_menu_items : array();
?>
<div class="fs-container fs-container--shell">
	<div class="fs-site-header__bar">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-site-header__logo" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s — home', 'focused-schools' ), get_bloginfo( 'name' ) ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'fs-site-header__logo-img' ) ); ?>
			<?php else : ?>
				<?php bloginfo( 'name' ); ?>
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
			data-label-open="<?php esc_attr_e( 'Open menu', 'focused-schools' ); ?>"
			data-label-close="<?php esc_attr_e( 'Close menu', 'focused-schools' ); ?>"
		>
			<img src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-1.svg' ); ?>" alt="" width="22" height="22" />
			<span class="fs-site-header__toggle-label"><?php esc_html_e( 'Menu', 'focused-schools' ); ?></span>
			<span class="fs-site-header__toggle-lines" aria-hidden="true"><span></span><span></span></span>
		</button>

		<?php
		/*
		 * The menu is a panel anchored under the bar, not a full-screen
		 * overlay: it lists exactly the same 'primary' menu items as the
		 * inline nav, each with a coral arrow, and closes on outside click
		 * or Escape. One menu, two presentations — editing Primary in
		 * Appearance > Menus changes both.
		 */
		?>
		<div class="fs-site-menu" id="fs-site-menu" data-open="false" data-fs-site-menu>
			<div class="fs-site-menu__list">
				<?php
				foreach ( $fs_menu_items as $fs_item ) :
					$fs_is_current = ! empty( $fs_item->classes ) && in_array( 'current-menu-item', (array) $fs_item->classes, true );
					?>
					<a href="<?php echo esc_url( $fs_item->url ); ?>"<?php echo $fs_is_current ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $fs_item->title ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				<?php endforeach; ?>

			</div>

			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<div class="fs-site-menu__foot">
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

		<span class="fs-site-header__progress" data-fs-nav-progress aria-hidden="true"></span>
	</div>
</div>
