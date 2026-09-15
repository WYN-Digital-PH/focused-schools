<?php
/**
 * Default fallback template.
 *
 * Required by WordPress for a theme to be valid. Foundation only — no page
 * design is implemented here yet; this is the minimal generic loop needed
 * to make the skip link and template hierarchy functional.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content" class="fs-container">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<header>
					<?php the_title( '<h1>', '</h1>' ); ?>
				</header>
				<div>
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
	else :
		?>
		<p><?php esc_html_e( 'Nothing found.', 'focused-schools' ); ?></p>
		<?php
	endif;
	?>
</main>

<?php
get_footer();
