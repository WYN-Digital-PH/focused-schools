<?php
/**
 * Header template.
 *
 * Foundation only — no visual page design. Provides the skip link target,
 * the required document scaffold, and the Primary navigation menu.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'focused-schools' ); ?></a>

<header class="fs-site-header">
	<div class="fs-container">
		<nav class="fs-nav fs-nav--primary" aria-label="<?php esc_attr_e( 'Primary', 'focused-schools' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
