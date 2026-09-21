<?php
/**
 * Template Name: Impact Stories
 *
 * Landing page template for Impact Stories. WordPress's native
 * page-{slug}.php template hierarchy applies this automatically to any page
 * whose slug is "impact-stories" — the ID (Page 3785) is never referenced
 * or hardcoded, so the real page is never replaced, recreated, or touched.
 *
 * Elementor coexistence: same as the other page templates — if
 * `_elementor_edit_mode` === 'builder' on this page, only its filtered
 * content renders; otherwise the sections below render.
 *
 * Known limitation: Page 3785 does not exist in this local environment
 * (verified via direct database query), and no legacy Pages are tagged
 * `_fs_legacy_impact_story` here either — the unified query below could
 * not be verified against real merged content. Verify against staging/
 * production before deploying.
 *
 * See docs/page-specs/impact-stories.md for the full spec.
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
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'heading'    => get_the_title() ? get_the_title() : __( 'Impact Stories', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( 'Real districts, real results — see how school and system leaders are putting bold ideas into practice.', 'focused-schools' ),
					'cta_label'  => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '',
					'cta_url'    => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '',
				)
			);

			// Preserve any Gutenberg/native content an editor has added
			// directly to this page, rather than discarding it.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-impact-stories__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;
			?>

			<section class="fs-impact-stories__section fs-container fs-container--wide" aria-labelledby="fs-impact-stories-heading">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'heading'       => __( 'Impact Stories', 'focused-schools' ),
						'heading_level' => 2,
						'heading_id'    => 'fs-impact-stories-heading',
					)
				);

				/*
				 * Unified query: fs_impact_story CPT posts + approved legacy
				 * Pages (tagged _fs_legacy_impact_story = 1 by the Legacy
				 * Page Bridge WP-CLI command — docs/migration-qa-rules.md
				 * §9). A single WP_Query can't apply a meta_query to only
				 * one post type within a multi-post-type query, so these
				 * are two separate queries merged in PHP. See
				 * docs/page-specs/impact-stories.md §3.
				 */
				$fs_cpt_query = new WP_Query(
					array(
						'post_type'      => 'fs_impact_story',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'no_found_rows'  => true,
					)
				);

				$fs_legacy_query = new WP_Query(
					array(
						'post_type'      => 'page',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'no_found_rows'  => true,
						'meta_key'       => '_fs_legacy_impact_story', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small landing-page query, acceptable at this scale.
						'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- see above.
					)
				);

				$fs_stories = array_merge( $fs_cpt_query->posts, $fs_legacy_query->posts );

				// Featured CPT stories first, then everything else — both
				// groups ordered newest-first by post_date, the one field
				// every post type shares. A stated design decision, not a
				// specified requirement.
				usort(
					$fs_stories,
					function ( $fs_a, $fs_b ) {
						$fs_a_featured = 'fs_impact_story' === $fs_a->post_type
							&& get_post_meta( $fs_a->ID, '_fs_impact_story_featured', true );
						$fs_b_featured = 'fs_impact_story' === $fs_b->post_type
							&& get_post_meta( $fs_b->ID, '_fs_impact_story_featured', true );

						if ( $fs_a_featured !== $fs_b_featured ) {
							return $fs_a_featured ? -1 : 1;
						}

						return strtotime( $fs_b->post_date ) - strtotime( $fs_a->post_date );
					}
				);

				if ( ! empty( $fs_stories ) ) :
					?>
					<div class="fs-card-grid">
						<?php
						foreach ( $fs_stories as $fs_story ) :
							get_template_part( 'template-parts/components/impact-story-card', null, array( 'post' => $fs_story ) );
						endforeach;
						?>
					</div>
					<?php
				else :
					?>
					<p class="fs-impact-stories__empty"><?php esc_html_e( 'New impact stories are on the way — check back soon.', 'focused-schools' ); ?></p>
					<?php
				endif;
				?>
			</section>

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'heading'   => __( 'Ready to write your own success story?', 'focused-schools' ),
					'cta_label' => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_label' )
						? focused_schools_get_setting( 'cta_label' )
						: __( 'Contact Us', 'focused-schools' ),
					'cta_url'   => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_url' )
						? focused_schools_get_setting( 'cta_url' )
						: '#',
				)
			);
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
