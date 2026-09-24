<?php
/**
 * Template Name: Team
 *
 * Page template for the Team page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "team" — the ID (Page 1198) is never referenced or hardcoded, so the
 * real page is never replaced, recreated, or touched. If the real page's
 * slug ever changes, this template simply stops applying rather than
 * silently targeting the wrong page.
 *
 * Elementor coexistence: same as page-services.php / page-about-our-mission-vision.php
 * — a page-{slug}.php template is always scoped to that exact page via URL
 * routing, so the standard Loop is reliable. If `_elementor_edit_mode`
 * === 'builder' on this page, only its filtered content renders;
 * otherwise the sections below render.
 *
 * Known limitation: Page 1198 does not exist in this local environment
 * (verified via direct database query), so the Elementor-detection branch
 * could not be verified against real data — verify against the actual
 * staging/production page before deploying.
 *
 * Design reference: the approved mockup's /#/team route. Every section maps
 * to a component the Home/About rebuilds already established — no new
 * component was needed. See docs/page-specs/team.md for the full spec.
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

		$fs_img = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';

		// The mockup's grid paginates at six, with the count shown above it.
		$fs_team_visible_count = 6;
		$fs_team               = new WP_Query(
			array(
				'post_type'      => 'fs_team_member',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);

		$fs_team_total   = (int) $fs_team->post_count;
		$fs_team_showing = min( $fs_team_visible_count, $fs_team_total );
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'eyebrow'    => __( 'Meet our team', 'focused-schools' ),
					'heading'    => __( "We're educators first. That's what makes us different.", 'focused-schools' ),
					'subheading' => __( 'Before we became consultants, we were principals, teachers, district leaders, and instructional coaches.', 'focused-schools' ),
					'image_url'  => $fs_img . 'retreat-3.jpg',
					'image_alt'  => __( 'The Focused Schools team working alongside district leaders.', 'focused-schools' ),
					'cta_label'  => __( 'See the Team', 'focused-schools' ),
					'cta_url'    => '#team-grid',
				)
			);

			// Preserve any Gutenberg/native content an editor has added
			// directly to this page, rather than discarding it.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-team__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;

			get_template_part(
				'template-parts/components/rail-text',
				null,
				array(
					'eyebrow'        => __( "Who you'll work with", 'focused-schools' ),
					'icon_url'       => $fs_img . 'mark-1.svg',
					'heading'        => __( 'We have sat in the seat you are sitting in.', 'focused-schools' ),
					'heading_max_ch' => 15,
					'body'           => array(
						__( "We've celebrated student successes, supported educators through difficult seasons, and made the tough decisions that come with leading schools and districts. That experience is why we listen before we lead — and why the people you meet on day one are the people who stay with the work.", 'focused-schools' ),
					),
					'cta_label'      => __( 'Explore Our Services', 'focused-schools' ),
					'cta_url'        => home_url( '/services/' ),
				)
			);
		?>

			<section class="fs-team__section fs-team__grid-section" id="team-grid" aria-labelledby="fs-team-heading">
				<div class="fs-container fs-container--shell">
					<header class="fs-team__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'The team', 'focused-schools' ); ?></p>
							<h2 id="fs-team-heading"><?php esc_html_e( 'Meet Our Team', 'focused-schools' ); ?></h2>
						</div>
						<?php
						if ( $fs_team_total > 0 ) :
							/* translators: 1: number of members shown, 2: total number of members. */
							$fs_count_format = __( 'Showing %1$d of %2$d', 'focused-schools' );
							$fs_count_now    = sprintf( $fs_count_format, $fs_team_showing, $fs_team_total );

							// The value the "Load more" script swaps in once
							// every remaining card has been revealed.
							$fs_count_all = sprintf( $fs_count_format, $fs_team_total, $fs_team_total );
							?>
							<p class="fs-team__count" aria-live="polite" data-fs-team-count data-fs-team-count-all="<?php echo esc_attr( $fs_count_all ); ?>">
								<?php echo esc_html( $fs_count_now ); ?>
							</p>
							<?php
						endif;
						?>
					</header>

					<?php if ( $fs_team->have_posts() ) : ?>
						<div class="fs-card-grid" id="fs-team-grid">
							<?php
							$fs_team_index = 0;
							while ( $fs_team->have_posts() ) :
								$fs_team->the_post();
								?>
								<div<?php echo $fs_team_index >= $fs_team_visible_count ? ' hidden' : ''; ?>>
									<?php
									get_template_part(
										'template-parts/components/team-card',
										null,
										array(
											'post'      => get_post(),
											'bio_modal' => true,
											'placeholder_mark_url' => $fs_img . 'mark-white.svg',
										)
									);
									?>
								</div>
								<?php
								++$fs_team_index;
							endwhile;
							?>
						</div>

						<?php if ( $fs_team_total > $fs_team_visible_count ) : ?>
							<div class="fs-loadmore">
								<button class="fs-btn fs-btn--secondary" type="button" data-fs-team-load-more aria-controls="fs-team-grid">
									<?php esc_html_e( 'Load more', 'focused-schools' ); ?>
									<span aria-hidden="true">&darr;</span>
								</button>
							</div>
						<?php endif; ?>

						<?php get_template_part( 'template-parts/components/team-bio-modal' ); ?>
					<?php else : ?>
						<p class="fs-team__empty"><?php esc_html_e( 'Our team page is being updated — check back soon.', 'focused-schools' ); ?></p>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'eyebrow'     => __( 'Say hello', 'focused-schools' ),
					'heading'     => __( 'Get to Know Us.', 'focused-schools' ),
					'description' => __( 'Tell us where your district is headed and we will introduce you to the people who would carry the work with you.', 'focused-schools' ),
					'cta_label'   => __( "Let's Talk", 'focused-schools' ),
					'cta_url'     => home_url( '/contact/' ),
					'cta2_label'  => __( 'See Impact Stories', 'focused-schools' ),
					'cta2_url'    => home_url( '/impact-stories/' ),
					'image_url'   => $fs_img . 'retreat-2.jpg',
					'image_alt'   => __( 'Two education leaders celebrating progress together.', 'focused-schools' ),
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
