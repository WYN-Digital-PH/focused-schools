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
			data-fs-nav-toggle
		>
			<span class="fs-site-header__toggle-label"><?php esc_html_e( 'Menu', 'focused-schools' ); ?></span>
			<span class="fs-site-header__toggle-lines" aria-hidden="true"><span></span><span></span></span>
		</button>

		<span class="fs-site-header__progress" data-fs-nav-progress aria-hidden="true"></span>
	</div>
</div>

<div class="fs-site-menu" id="fs-site-menu" data-fs-site-menu role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Explore Focused Schools', 'focused-schools' ); ?>" hidden>
	<div class="fs-site-menu__panel">
		<div class="fs-site-menu__top">
			<p class="fs-site-menu__eyebrow"><?php esc_html_e( 'Explore Focused Schools', 'focused-schools' ); ?></p>
			<button type="button" class="fs-site-menu__close" data-fs-nav-close aria-label="<?php esc_attr_e( 'Close menu', 'focused-schools' ); ?>">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="fs-site-menu__body">
			<nav class="fs-site-menu__grid" aria-label="<?php esc_attr_e( 'Expanded navigation', 'focused-schools' ); ?>">
				<?php
				$fs_index = 0;
				foreach ( $fs_menu_items as $fs_item ) :
					++$fs_index;
					?>
					<a href="<?php echo esc_url( $fs_item->url ); ?>" class="fs-site-menu__cell">
						<span class="fs-site-menu__cell-label"><?php echo esc_html( $fs_item->title ); ?></span>
						<span class="fs-site-menu__cell-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>
			<aside class="fs-site-menu__aside">
				<p class="fs-site-menu__tagline">
					<?php
					echo esc_html(
						function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'footer_text' )
							? focused_schools_get_setting( 'footer_text' )
							: get_bloginfo( 'description' )
					);
					?>
				</p>
				<?php if ( $fs_email ) : ?>
					<a class="fs-site-menu__email" href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
				<?php endif; ?>
				<?php if ( array_filter( wp_list_pluck( $fs_socials, 'url' ) ) ) : ?>
					<div class="fs-site-menu__socials">
						<?php
						foreach ( $fs_socials as $fs_social ) :
							if ( empty( $fs_social['url'] ) ) {
								continue;
							}
							?>
							<a href="<?php echo esc_url( $fs_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $fs_social['label'] ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</div>
