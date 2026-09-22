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
 * See docs/page-specs/team.md for the full spec.
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
					'heading'    => get_the_title() ? get_the_title() : __( 'Our Team', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( 'The people behind the partnership — leaders who bring experience, care, and a relentless focus on students to every district we work with.', 'focused-schools' ),
					'cta_label'  => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '',
					'cta_url'    => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '',
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
			?>

			<section class="fs-team__section fs-container fs-container--shell" aria-labelledby="fs-team-heading">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'heading'       => __( 'Our Team', 'focused-schools' ),
						'heading_level' => 2,
						'heading_id'    => 'fs-team-heading',
					)
				);

				// Every published team member renders automatically here —
				// editors manage the list entirely via the fs_team_member
				// CPT admin screen; nothing on this page is ever manually
				// duplicated. See docs/page-specs/team.md §3.
				$fs_team = new WP_Query(
					array(
						'post_type'      => 'fs_team_member',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'no_found_rows'  => true,
					)
				);

				if ( $fs_team->have_posts() ) :
					?>
					<div class="fs-card-grid">
						<?php
						while ( $fs_team->have_posts() ) :
							$fs_team->the_post();
							get_template_part(
								'template-parts/components/team-card',
								null,
								array(
									'post'     => get_post(),
									'show_bio' => true,
								)
							);
						endwhile;
						?>
					</div>
					<?php
				else :
					?>
					<p class="fs-team__empty"><?php esc_html_e( 'Our team page is being updated — check back soon.', 'focused-schools' ); ?></p>
					<?php
				endif;
				wp_reset_postdata();
				?>
			</section>

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'heading'   => __( 'Want to meet the team?', 'focused-schools' ),
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
