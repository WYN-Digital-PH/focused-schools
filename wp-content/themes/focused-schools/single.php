<?php
/**
 * Single post.
 *
 * The Elementor guard below is the whole coexistence rule and is unchanged:
 * a post whose layout Elementor owns renders its own output and nothing
 * else — no breadcrumb, no hero, no share rail, no author box, no related
 * strip. That is deliberate. Wrapping Elementor's saved layout in this
 * design would duplicate the title it usually draws itself, constrain
 * full-width sections into a narrow column, and fight Elementor Pro's Theme
 * Builder if a condition is active. See docs/blog-coexistence.md.
 *
 * So the design below applies to native and Gutenberg posts, and the 29
 * Elementor posts keep rendering exactly as they do today. Bringing them
 * across is editorial work — rebuilding them as blocks — not a template
 * change, and it is explicitly out of scope this sprint.
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

		$fs_post = get_post();
		?>

		<main id="content" class="fs-post">
			<article <?php post_class( 'fs-post-single' ); ?> id="post-<?php the_ID(); ?>">
				<?php get_template_part( 'template-parts/components/post-hero', null, array( 'post' => $fs_post ) ); ?>

				<section class="fs-post__body-section">
					<div class="fs-container fs-container--shell fs-story-body">
						<?php get_template_part( 'template-parts/components/post-share', null, array( 'post' => $fs_post ) ); ?>

						<div class="fs-prose">
							<?php the_content(); ?>

							<?php
							wp_link_pages(
								array(
									'before' => '<nav class="fs-post-single__page-links" aria-label="' . esc_attr__( 'Page', 'focused-schools' ) . '">',
									'after'  => '</nav>',
								)
							);
							?>
						</div>
					</div>
				</section>
			</article>

			<?php get_template_part( 'template-parts/components/post-author', null, array( 'post' => $fs_post ) ); ?>

			<div class="fs-container fs-container--shell">
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
					$fs_blog = (int) get_option( 'page_for_posts' );
					?>
					<section class="fs-post__related" aria-labelledby="fs-related-posts-heading">
						<div class="fs-container fs-container--shell">
							<header class="fs-blog__head">
								<div>
									<p class="fs-eyebrow"><?php esc_html_e( 'Keep reading', 'focused-schools' ); ?></p>
									<h2 class="fs-blog__heading" id="fs-related-posts-heading"><?php esc_html_e( 'Related articles.', 'focused-schools' ); ?></h2>
								</div>
								<?php
								get_template_part(
									'template-parts/components/button',
									null,
									array(
										'label' => __( 'View All Posts', 'focused-schools' ),
										'url'   => $fs_blog ? get_permalink( $fs_blog ) : home_url( '/' ),
										'style' => 'secondary',
									)
								);
								?>
							</header>

							<div class="fs-blog__grid">
								<?php
								while ( $fs_related->have_posts() ) :
									$fs_related->the_post();
									get_template_part( 'template-parts/components/post-card', null, array( 'post' => get_post() ) );
								endwhile;
								?>
							</div>
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
