<?php
/**
 * Template Name: Home (block-built)
 *
 * Applies to any page with the slug "home" via WordPress's native
 * page-{slug}.php hierarchy.
 *
 * It renders the page's blocks bare — no container, no page title — because
 * the homepage sections are full-bleed by design. The generic index.php
 * fallback would wrap them in the 720px content container and print the page
 * title above them, which is why /home/ did not look like the front page.
 *
 * This is the editable twin of front-page.php: the same blocks render the
 * same components, so a page built here can simply be set as the static front
 * page when its content is ready.
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
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
