<?php
/**
 * Generic archive template (category/tag/date/author).
 *
 * Distinct from home.php (the dedicated /blog/ Posts Page template) — this
 * governs other archive-type views that would otherwise fall back to the
 * bare index.php. There's no single associated post/page to check for
 * Elementor content here (unlike home.php's Posts Page or single.php's
 * individual post), so this always renders natively.
 *
 * Same known limitation as home.php/single.php regarding Elementor Pro's
 * Theme Builder possibly overriding archive views globally — see
 * docs/blog-coexistence.md.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<div class="fs-container fs-container--wide fs-blog__header">
		<h1 class="fs-blog__title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h1>
		<?php
		$fs_archive_description = get_the_archive_description();
		if ( $fs_archive_description ) :
			?>
			<div class="fs-blog__description"><?php echo wp_kses_post( $fs_archive_description ); ?></div>
			<?php
		endif;
		?>
	</div>

	<div class="fs-container fs-container--wide fs-blog__list">
		<?php if ( have_posts() ) : ?>
			<div class="fs-card-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/components/post-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="fs-blog__empty"><?php esc_html_e( 'No posts found.', 'focused-schools' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
