<?php
/**
 * Gutenberg/block-built team page.
 *
 * The page content is rendered directly inside the main content area, the
 * same approach the Home and About templates use: every section on this page
 * is a block, so the order and copy live in the editor rather than here.
 *
 * Blocks are responsible for their own containers, spacing, widths,
 * full-bleed sections, backgrounds and responsive layouts.
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
		 * No wrapper here: full-width and full-bleed blocks render exactly as
		 * they do on the Home page.
		 */
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
