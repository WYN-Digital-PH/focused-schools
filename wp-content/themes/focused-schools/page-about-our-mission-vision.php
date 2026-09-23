<?php
/**
 * Template Name: About (Our Mission & Vision)
 *
 * Gutenberg/block-built About page.
 *
 * The page content is rendered directly inside the main content area,
 * matching the same rendering approach used by the Home page template.
 *
 * Gutenberg blocks are responsible for their own containers, spacing,
 * widths, full-bleed sections, backgrounds, and responsive layouts.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	while ( have_posts() ) :
		the_post();

		/*
		 * Render the page's Gutenberg content directly.
		 *
		 * Do not add an fs-container or other wrapper here.
		 * This allows Gutenberg blocks to render exactly as they do
		 * on the homepage, including full-width and full-bleed blocks.
		 */
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();