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
	<?php get_template_part( 'template-parts/components/site-header' ); ?>
</header>
