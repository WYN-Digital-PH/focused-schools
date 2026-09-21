<?php
/**
 * Single post template.
 *
 * Elementor coexistence: checks the current post's own _elementor_edit_mode.
 * If 'builder', renders only the_content() — Elementor's own saved output,
 * completely untouched, preserving its content wrapper exactly as today.
 * Otherwise: full native presentation using custom theme typography,
 * featured image, author/date meta, post navigation, and related posts.
 *
 * Yoast SEO compatibility (unverifiable locally — Yoast isn't installed
 * here): standard Loop, wp_head() (already called in header.php), and
 * title-tag support (already registered) — nothing custom that would
 * fight Yoast's own hooks/canonical/schema output.
 *
 * Known limitation: could not be verified against real Elementor content
 * or Elementor Pro's Theme Builder (a separate, site-wide template-override
 * system distinct from this per-post check) in this local environment —
 * verify against staging/production. See docs/blog-coexistence.md.
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
			<article <?php post_class( 'fs-post-single' ); ?> id="post-<?php the_ID(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="fs-post-single__media">
						<?php the_post_thumbnail( 'large', array( 'class' => 'fs-post-single__image' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="fs-container fs-post-single__content">
					<header class="fs-post-single__header">
						<?php the_title( '<h1 class="fs-post-single__title">', '</h1>' ); ?>
						<p class="fs-post-single__meta">
							<?php
							printf(
								/* translators: 1: author display name, 2: publish date. */
								esc_html__( 'By %1$s on %2$s', 'focused-schools' ),
								esc_html( get_the_author() ),
								esc_html( get_the_date() )
							);
							?>
						</p>
					</header>

					<div class="fs-post-single__body">
						<?php the_content(); ?>
					</div>

					<?php
					wp_link_pages(
						array(
							'before' => '<nav class="fs-post-single__page-links" aria-label="' . esc_attr__( 'Page', 'focused-schools' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</article>

			<div class="fs-container fs-container--wide">
				<?php
				the_post_navigation(
					array(
						'prev_text' => '<span class="fs-post-nav__label">' . esc_html__( 'Previous', 'focused-schools' ) . '</span><span class="fs-post-nav__title">%title</span>',
						'next_text' => '<span class="fs-post-nav__label">' . esc_html__( 'Next', 'focused-schools' ) . '</span><span class="fs-post-nav__title">%title</span>',
					)
				);
				?>
			</div>

			<?php
			$fs_categories = get_the_category();

			if ( ! empty( $fs_categories ) ) :
				$fs_related = new WP_Query(
					array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => 3,
						'post__not_in'   => array( get_the_ID() ),
						'category__in'   => wp_list_pluck( $fs_categories, 'term_id' ),
						'no_found_rows'  => true,
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);

				if ( $fs_related->have_posts() ) :
					?>
					<section class="fs-post-single__related fs-container fs-container--wide" aria-labelledby="fs-related-posts-heading">
						<?php
						get_template_part(
							'template-parts/components/section-heading',
							null,
							array(
								'heading'       => __( 'Related Posts', 'focused-schools' ),
								'heading_level' => 2,
								'heading_id'    => 'fs-related-posts-heading',
							)
						);
						?>
						<div class="fs-card-grid">
							<?php
							while ( $fs_related->have_posts() ) :
								$fs_related->the_post();
								get_template_part( 'template-parts/components/post-card', null, array( 'post' => get_post() ) );
							endwhile;
							?>
						</div>
					</section>
					<?php
				endif;
				wp_reset_postdata();
			endif;
			?>
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
