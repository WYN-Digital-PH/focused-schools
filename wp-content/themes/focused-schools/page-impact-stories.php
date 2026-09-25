<?php
/**
 * Gutenberg/block-built impact-stories page.
 *
 * The Elementor guard is unchanged: a page built with Elementor renders its
 * own output and none of the blocks below apply. Otherwise every section is
 * a block, so the order and copy live in the editor rather than here.
 *
 * Blocks own their containers, spacing, widths, backgrounds and responsive
 * layouts, so no wrapper is added around the content.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) :
			?>
			<main id="content">
				<?php the_content(); ?>
			</main>
			<?php
			continue;
		endif;
		?>

		<main id="content">
			<?php the_content(); ?>
		</main>
		<?php
	endwhile;
else :
	?>
	<main id="content" class="fs-container">
		<p><?php esc_html_e( 'Nothing found.', 'focused-schools' ); ?></p>
	</main>
	<?php
endif;

get_footer();
