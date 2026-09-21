<?php
/**
 * Component: Footer navigation structure.
 *
 * No args — pulls the 'footer' registered nav menu plus Site Settings
 * (social URLs, footer text, copyright name), falling back gracefully if
 * the focused-schools-core plugin is inactive.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_has_settings = function_exists( 'focused_schools_get_setting' );
$fs_footer_text  = $fs_has_settings ? focused_schools_get_setting( 'footer_text' ) : '';
$fs_copyright    = $fs_has_settings ? focused_schools_get_setting( 'copyright_name' ) : '';

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
<div class="fs-site-footer__inner fs-container">
	<nav class="fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Footer', 'focused-schools' ); ?>">
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
		<div class="fs-site-footer__widgets">
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
		</div>
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

	<?php if ( $fs_footer_text ) : ?>
		<p class="fs-site-footer__text"><?php echo esc_html( $fs_footer_text ); ?></p>
	<?php endif; ?>

	<p class="fs-site-footer__copyright">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
		<?php echo esc_html( $fs_copyright ? $fs_copyright : get_bloginfo( 'name' ) ); ?>.
		<?php esc_html_e( 'All rights reserved.', 'focused-schools' ); ?>
	</p>
</div>
