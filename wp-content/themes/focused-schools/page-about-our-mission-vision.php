<?php
/**
 * Template Name: About (Our Mission & Vision)
 *
 * Page template for the About page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "about-our-mission-vision" — the ID (Page 3726) is never referenced or
 * hardcoded, so the real page is never replaced, recreated, or touched. If
 * the real page's slug ever changes, this template simply stops applying
 * rather than silently targeting the wrong page.
 *
 * A "Template Name" header is also included so this can alternatively be
 * assigned manually via Page Attributes if ever needed on a differently
 * slugged page — the slug-based filename is the primary mechanism.
 *
 * Design reference: the approved `.dc` source files (`Design System v1.dc.html`,
 * `Focused Schools About.dc.html`) in this theme's root directory — the
 * exact visual/structural/token source of truth. See docs/page-specs/about.md
 * for the full section-by-section spec and what changed when this template
 * was rebuilt against them (notably: Partner Districts became CPT-driven
 * via the new fs_partner post type + fs_partner_state taxonomy, and the
 * Impact stats now read from Site Settings instead of being hardcoded here
 * and on the Home page separately).
 *
 * Elementor coexistence: unlike front-page.php, this template can rely on
 * the standard Loop directly — a page-{slug}.php template is always scoped
 * to that exact page via URL routing, with no "Settings > Reading"
 * ambiguity. If `_elementor_edit_mode` === 'builder' on this page, only its
 * filtered content renders; otherwise the seven sections from
 * docs/page-specs/about.md render.
 *
 * Known limitation: Page 3726 does not exist in this local environment
 * (verified via direct database query), so the Elementor-detection branch
 * could not be verified against real data — verify against the actual
 * staging/production page before deploying.
 *
 * Images: all `assets/img/*` paths below are placeholders per established
 * project convention (see docs/page-specs/home.md §8) — no binary image
 * files were added. Reuses the same photography as the Home page
 * (retreat-*.jpg, chamber-badge.jpg) since both designs share one photo set.
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
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'eyebrow'    => __( 'About Focused Schools', 'focused-schools' ),
					// Always the approved .dc source's literal hero copy —
					// not get_the_title()/get_the_excerpt(). This page's own
					// WordPress title ("About Our Mission & Vision") still
					// drives the <title> tag/SEO via title-tag support; the
					// two are independent, so this doesn't touch that.
					'heading'    => __( 'From the boardroom to the classroom, every educator has a cape that they wear.', 'focused-schools' ),
					'subheading' => __( 'Every student deserves the opportunity to learn, grow, and succeed. We believe the best way to make that possible is by supporting the superheroes who make it happen every day.', 'focused-schools' ),
					'image_url'  => $fs_img . 'retreat-1.jpg',
					'image_alt'  => __( 'Educators and district leaders working together in a Focused Schools session.', 'focused-schools' ),
					'cta_label'  => __( 'Meet Our Team', 'focused-schools' ),
					'cta_url'    => home_url( '/team/' ),
				)
			);

			// Preserve any Gutenberg/native content an editor has added
			// directly to this page, rather than discarding it.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-about__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;
			?>

			<?php
			get_template_part(
				'template-parts/components/rail-text',
				null,
				array(
					'eyebrow'        => __( 'Who we are', 'focused-schools' ),
					'icon_url'       => $fs_img . 'mark-1.svg',
					'heading'        => __( 'Every student, every classroom, every day — no exceptions.', 'focused-schools' ),
					'heading_max_ch' => 15,
					'body'           => array(
						__( "Since 2000, Focused Schools has partnered with districts and schools across the country to strengthen leadership, support educators, and create learning environments where every student can thrive. Founded by educators and still led by educators today, we provide professional development, IMPACT coaching, technical assistance, and strategic support tailored to each community's unique goals and challenges.", 'focused-schools' ),
						__( "Over the past two decades, we've partnered with hundreds of schools across urban, rural, public, private, and charter settings, helping educators build the confidence, systems, and leadership needed to create lasting change.", 'focused-schools' ),
					),
					'cta_label'      => __( 'Explore Our Services', 'focused-schools' ),
					'cta_url'        => home_url( '/services/' ),
				)
			);

			get_template_part(
				'template-parts/components/commitment-list',
				null,
				array(
					'eyebrow'              => __( 'What guides us', 'focused-schools' ),
					'heading'              => sprintf(
						/* translators: %s: "matters as much as the outcomes." (emphasized). */
						__( 'How we work %s', 'focused-schools' ),
						'<strong>' . __( 'matters as much as the outcomes.', 'focused-schools' ) . '</strong>'
					),
					'heading_max_ch'       => 11,
					'intro'                => __( 'Our values keep us grounded in every partnership, guiding us to listen first, lead with purpose, and leave schools stronger than we found them.', 'focused-schools' ),
					'image_url'            => $fs_img . 'retreat-3.jpg',
					'image_alt'            => __( 'A Focused Schools facilitator working through a system map with school leaders.', 'focused-schools' ),
					'image_caption_kicker' => __( 'Educators first', 'focused-schools' ),
					'image_caption_text'   => __( 'Founded by educators, still led by educators', 'focused-schools' ),
					'items'                => array(
						array(
							'heading'   => __( 'Our Mission', 'focused-schools' ),
							'body'      => __( "Our mission is to support the educators who shape students' lives every day. By strengthening leadership, developing people, and building sustainable systems, we help schools create lasting change so every student can reach their full potential.", 'focused-schools' ),
							'cta_label' => __( 'See Our Impact', 'focused-schools' ),
							'cta_url'   => home_url( '/impact-stories/' ),
						),
						array(
							'heading'   => __( 'Our Values', 'focused-schools' ),
							'body'      => __( 'How we work matters just as much as the outcomes we achieve. Our values keep us grounded in every partnership, guiding us to listen first, lead with purpose, and leave schools stronger than we found them.', 'focused-schools' ),
							'cta_label' => __( 'Meet Our Team', 'focused-schools' ),
							'cta_url'   => home_url( '/team/' ),
						),
						array(
							'heading'   => __( 'Our Approach', 'focused-schools' ),
							'body'      => __( 'Using our Focused Leadership Framework as the foundation, we collaborate with districts and schools to design a plan of partnered support that aligns with their improvement efforts.', 'focused-schools' ),
							'cta_label' => __( 'Explore Our Services', 'focused-schools' ),
							'cta_url'   => home_url( '/services/' ),
						),
					),
				)
			);
			?>

			<?php $fs_has_settings = function_exists( 'focused_schools_get_setting' ); ?>
			<section class="fs-about__stats-band" aria-labelledby="fs-about-stats-title">
				<img class="fs-about__stats-watermark" src="<?php echo esc_url( $fs_img . 'mark-white.svg' ); ?>" alt="" aria-hidden="true" />
				<div class="fs-container fs-container--shell fs-about__stats-head">
					<div>
						<p class="fs-eyebrow fs-eyebrow--on-dark"><?php esc_html_e( 'Two decades of partnership', 'focused-schools' ); ?></p>
						<h2 id="fs-about-stats-title"><?php esc_html_e( 'The measure of our work is what changed because of it.', 'focused-schools' ); ?></h2>
					</div>
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => __( 'View Impact Stories', 'focused-schools' ),
							'url'   => home_url( '/impact-stories/' ),
							'style' => 'white',
						)
					);
					?>
				</div>
				<div class="fs-container fs-container--shell">
					<?php
					get_template_part(
						'template-parts/components/statistics-counter',
						null,
						array(
							'on_teal' => true,
							'stats'   => array(
								array(
									'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_students', 2 ) : 2,
									'suffix' => '+',
									'unit'   => __( 'Million', 'focused-schools' ),
									'label'  => __( 'Students impacted', 'focused-schools' ),
								),
								array(
									'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_years', 20 ) : 20,
									'suffix' => '+',
									'unit'   => __( 'Years', 'focused-schools' ),
									'label'  => __( 'Partnering with schools', 'focused-schools' ),
								),
								array(
									'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_states', 25 ) : 25,
									'suffix' => '+',
									'unit'   => __( 'States', 'focused-schools' ),
									'label'  => __( 'Served', 'focused-schools' ),
								),
							),
						)
					);
					?>
				</div>
			</section>

			<?php
			// Real dynamic query against fs_partner + its fs_partner_state
			// taxonomy (see FocusedSchoolsCore\Modules\Partners) — "adding a
			// sixth state adds a row, no template change" per the approved
			// design's own spec (docs/page-specs/about.md §5).
			$fs_partner_states = array();
			$fs_state_terms    = get_terms(
				array(
					'taxonomy'   => 'fs_partner_state',
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => true,
				)
			);

			if ( ! is_wp_error( $fs_state_terms ) ) {
				foreach ( $fs_state_terms as $fs_state_term ) {
					$fs_district_query = new WP_Query(
						array(
							'post_type'      => 'fs_partner',
							'posts_per_page' => -1,
							'orderby'        => 'menu_order',
							'order'          => 'ASC',
							'no_found_rows'  => true,
							'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- small, admin-managed taxonomy; acceptable at this scale.
								array(
									'taxonomy' => 'fs_partner_state',
									'field'    => 'term_id',
									'terms'    => $fs_state_term->term_id,
								),
							),
						)
					);

					if ( $fs_district_query->have_posts() ) {
						$fs_partner_states[] = array(
							'name'      => $fs_state_term->name,
							'districts' => wp_list_pluck( $fs_district_query->posts, 'post_title' ),
						);
					}
				}
			}

			if ( ! empty( $fs_partner_states ) ) :
				get_template_part(
					'template-parts/components/partner-districts',
					null,
					array(
						'eyebrow'      => __( 'Our partners', 'focused-schools' ),
						'heading'      => sprintf(
							/* translators: %s: "these districts." (emphasized). */
							__( "We're proud to partner with %s", 'focused-schools' ),
							'<strong>' . __( 'these districts.', 'focused-schools' ) . '</strong>'
						),
						'intro'        => __( 'Urban, rural, public, private, and charter — during the 2024–2025 school year we worked alongside these communities across five states.', 'focused-schools' ),
						'states'       => $fs_partner_states,
						'badge_url'    => $fs_img . 'chamber-badge.jpg',
						'badge_alt'    => __( '2026 Proud Member — Manatee Chamber of Commerce', 'focused-schools' ),
						'note'         => sprintf(
							/* translators: %s: "Approved Provider." (emphasized). */
							__( 'Massachusetts Department of Elementary and Secondary Education %s', 'focused-schools' ),
							'<strong>' . __( 'Approved Provider.', 'focused-schools' ) . '</strong>'
						),
						'closing_text' => __( "Let's put you on the map.", 'focused-schools' ),
						'cta_label'    => __( "Let's Talk", 'focused-schools' ),
						'cta_url'      => home_url( '/contact/' ),
					)
				);
			else :
				?>
				<section class="fs-about__section fs-container fs-container--shell">
					<p class="fs-about__empty"><?php esc_html_e( 'Our partner district directory is being populated — please check back soon.', 'focused-schools' ); ?></p>
				</section>
				<?php
			endif;
			?>

			<section class="fs-about__section fs-about__team fs-container fs-container--shell" id="team" aria-labelledby="fs-about-team-title">
				<?php
				/*
				 * Bespoke 2-column header (heading left, intro right), not
				 * section-heading.php — that component is single-column/
				 * stacked, correct for Services/Team pages but not a match
				 * for this section's approved layout, which mirrors the
				 * same 2-col + mixed-weight-heading pattern already used by
				 * "What Guides Us" and "Partner Districts" on this page.
				 */
				?>
				<header class="fs-about__team-intro">
					<div>
						<p class="fs-eyebrow"><?php esc_html_e( 'Meet our team', 'focused-schools' ); ?></p>
						<h2 id="fs-about-team-title">
							<?php esc_html_e( "We're educators first.", 'focused-schools' ); ?>
							<strong><?php esc_html_e( "That's what makes us different.", 'focused-schools' ); ?></strong>
						</h2>
					</div>
					<p class="fs-about__team-lead"><?php esc_html_e( "Before we became consultants, we were principals, teachers, district leaders, and instructional coaches. We've celebrated student successes, supported educators through difficult seasons, and made the tough decisions that come with leading schools and districts.", 'focused-schools' ); ?></p>
				</header>
				<?php

				// 9, not 6 — the approved .dc design's own spec: "Cards
				// paginate at nine with a Load more secondary button."
				$fs_team_visible_count = 9;
				$fs_team               = new WP_Query(
					array(
						'post_type'      => 'fs_team_member',
						'posts_per_page' => -1,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'no_found_rows'  => true,
					)
				);

				if ( $fs_team->have_posts() ) :
					?>
					<div class="fs-card-grid" id="fs-about-team-grid">
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
										'compact'   => true,
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
					<?php if ( $fs_team_index > $fs_team_visible_count ) : ?>
						<div class="fs-loadmore">
							<button class="fs-btn fs-btn--secondary" type="button" data-fs-team-load-more aria-controls="fs-about-team-grid">
								<?php esc_html_e( 'Load more', 'focused-schools' ); ?>
								<span aria-hidden="true">&darr;</span>
							</button>
						</div>
					<?php endif; ?>
					<?php get_template_part( 'template-parts/components/team-bio-modal' ); ?>
					<?php
				else :
					?>
					<p class="fs-about__empty"><?php esc_html_e( 'Our full leadership team profiles are on the way — please check back soon.', 'focused-schools' ); ?></p>
					<?php
				endif;
				wp_reset_postdata();
				?>
				<p class="fs-mini">
					<a class="fs-text-link" href="<?php echo esc_url( home_url( '/team/' ) ); ?>">
						<?php esc_html_e( 'See the full team', 'focused-schools' ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				</p>
			</section>

			<?php
			get_template_part(
				'template-parts/components/content-image-split',
				null,
				array(
					'eyebrow'        => __( 'Our mission', 'focused-schools' ),
					'heading'        => __( "Support the educators who shape students' lives.", 'focused-schools' ),
					'body'           => '<p>' . esc_html__( 'By strengthening leadership, developing people, and building sustainable systems, we help schools create lasting change so every student can reach their full potential.', 'focused-schools' ) . '</p>',
					'image_url'      => $fs_img . 'retreat-2.jpg',
					'image_alt'      => __( 'Two education leaders celebrating progress together.', 'focused-schools' ),
					'image_position' => 'right',
					'cta_label'      => __( 'Get to Know Us', 'focused-schools' ),
					'cta_url'        => home_url( '/contact/' ),
					'cta2_label'     => __( 'Explore Our Services', 'focused-schools' ),
					'cta2_url'       => home_url( '/services/' ),
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
