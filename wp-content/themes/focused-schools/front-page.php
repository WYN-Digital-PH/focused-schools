<?php
/**
 * Home page template (static front page).
 *
 * WordPress automatically selects this template for whatever page is
 * configured as the site's static front page (Settings > Reading) — it
 * never references a specific post ID, so the real front page (e.g. Page
 * 992) is never replaced, recreated, or hardcoded here.
 *
 * Elementor coexistence: if the front page was built with Elementor
 * (`_elementor_edit_mode` === 'builder'), this template renders only that
 * page's filtered content so Elementor's own saved output keeps rendering
 * exactly as it does today. Otherwise it renders the component-based Home
 * layout below. This detection could not be verified against real data in
 * this environment (no Elementor content exists locally) — verify against
 * the actual staging/production front page before deploying.
 *
 * The front page is resolved explicitly via the page_on_front option
 * rather than the main query loop. The main query is whatever
 * Settings > Reading's "Your homepage displays" is currently set to — in
 * this local environment that's "Your latest posts", so have_posts() here
 * would iterate blog posts (e.g. the default "Hello world!") instead of
 * the actual front page. Resolving the ID directly is correct regardless
 * of that setting, and never depends on/alters it.
 *
 * TODO: the Statistics, Partner Strip, and Podcast sections below use
 * literal placeholder content — no live data source exists yet for real
 * stats, partner logos, or podcast episodes. Replace before launch.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fs_front_page_id = ( 'page' === get_option( 'show_on_front' ) ) ? (int) get_option( 'page_on_front' ) : 0;
$fs_front_page    = $fs_front_page_id ? get_post( $fs_front_page_id ) : null;

$fs_is_elementor = $fs_front_page instanceof WP_Post
	&& 'builder' === get_post_meta( $fs_front_page->ID, '_elementor_edit_mode', true );

if ( $fs_is_elementor ) :
	?>
	<main id="content">
		<?php echo apply_filters( 'the_content', $fs_front_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- standard the_content filter output (Elementor's own rendering), same trust level as the_content(). ?>
	</main>
	<?php
else :
	$fs_hero_heading    = $fs_front_page ? get_the_title( $fs_front_page ) : get_bloginfo( 'name' );
	$fs_hero_subheading = $fs_front_page ? get_the_excerpt( $fs_front_page ) : get_bloginfo( 'description' );
	?>
	<main id="content">
		<?php
		get_template_part(
			'template-parts/components/hero',
			null,
			array(
				'heading'    => $fs_hero_heading,
				'subheading' => $fs_hero_subheading,
				'cta_label'  => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '',
				'cta_url'    => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '',
			)
		);

		// Preserve whatever plain (non-Elementor) content already exists on
		// the real front page, rather than discarding it. Nothing to show
		// when there is no configured front page (this environment).
		if ( $fs_front_page && '' !== trim( (string) $fs_front_page->post_content ) ) :
			?>
			<div class="fs-container fs-home__intro">
				<?php echo apply_filters( 'the_content', $fs_front_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- standard the_content filter output. ?>
			</div>
			<?php
		endif;
		?>

			<section class="fs-home__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow' => __( 'What We Do', 'focused-schools' ),
						'heading' => __( 'Our Services', 'focused-schools' ),
					)
				);

				$fs_services = new WP_Query(
					array(
						'post_type'      => 'fs_service',
						'posts_per_page' => 6,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'no_found_rows'  => true,
					)
				);

				if ( $fs_services->have_posts() ) :
					?>
					<div class="fs-card-grid">
						<?php
						while ( $fs_services->have_posts() ) :
							$fs_services->the_post();
							get_template_part( 'template-parts/components/service-card', null, array( 'post' => get_post() ) );
						endwhile;
						?>
					</div>
					<?php
				endif;
				wp_reset_postdata();
				?>
			</section>

			<section class="fs-home__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow' => __( 'Meet the Team', 'focused-schools' ),
						'heading' => __( 'Our Team', 'focused-schools' ),
					)
				);

				$fs_team = new WP_Query(
					array(
						'post_type'      => 'fs_team_member',
						'posts_per_page' => 3,
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
							get_template_part( 'template-parts/components/team-card', null, array( 'post' => get_post() ) );
						endwhile;
						?>
					</div>
					<?php
				endif;
				wp_reset_postdata();
				?>
			</section>

			<section class="fs-home__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow' => __( 'Real Results', 'focused-schools' ),
						'heading' => __( 'Impact Stories', 'focused-schools' ),
					)
				);

				$fs_stories = new WP_Query(
					array(
						'post_type'      => 'fs_impact_story',
						'posts_per_page' => 3,
						'no_found_rows'  => true,
						'meta_key'       => '_fs_impact_story_featured', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small homepage teaser query, acceptable for this scale.
						'orderby'        => array(
							'meta_value_num' => 'DESC',
							'date'           => 'DESC',
						),
					)
				);

				if ( $fs_stories->have_posts() ) :
					?>
					<div class="fs-card-grid">
						<?php
						while ( $fs_stories->have_posts() ) :
							$fs_stories->the_post();
							get_template_part( 'template-parts/components/impact-story-card', null, array( 'post' => get_post() ) );
						endwhile;
						?>
					</div>
					<?php
				endif;
				wp_reset_postdata();
				?>
			</section>

			<?php
			/*
			 * TODO: placeholder values below — no Statistics data source
			 * exists yet (no Site Settings fields, no CPT). Replace with
			 * real numbers before launch.
			 *
			 * statistics-counter.php and podcast-card.php render bare
			 * (no internal container, unlike Hero/CTA Banner), so the
			 * calling template is responsible for their width — wrapped
			 * here in the same wide container as the card-grid sections
			 * for visual consistency across the page.
			 */
			?>
			<div class="fs-home__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/statistics-counter',
					null,
					array(
						'stats' => array(
							array(
								'value'  => 0,
								'suffix' => '+',
								'label'  => __( 'TODO: replace with real stat', 'focused-schools' ),
							),
							array(
								'value'  => 0,
								'suffix' => '+',
								'label'  => __( 'TODO: replace with real stat', 'focused-schools' ),
							),
							array(
								'value'  => 0,
								'suffix' => '%',
								'label'  => __( 'TODO: replace with real stat', 'focused-schools' ),
							),
						),
					)
				);
				?>
			</div>

			<?php
			/*
			 * TODO: placeholder — no fs_podcast post type exists yet
			 * (see docs/architecture.md §3.5). Replace with a real query
			 * once that module is built.
			 */
			?>
			<div class="fs-home__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/podcast-card',
					null,
					array(
						'title'       => __( 'TODO: latest episode title', 'focused-schools' ),
						'description' => __( 'TODO: episode description, once the Podcast module is built.', 'focused-schools' ),
					)
				);
				?>
			</div>

			<?php
			/*
			 * TODO: placeholder — no partner logos exist in this
			 * environment's media library, and none may be added here
			 * (wp-content/uploads is out of scope for code changes).
			 * Add real entries once logos are uploaded, e.g.:
			 * array( 'image_id' => 123, 'name' => 'Partner Name', 'url' => 'https://...' ).
			 */
			get_template_part(
				'template-parts/components/partner-strip',
				null,
				array(
					'heading' => __( 'Trusted By', 'focused-schools' ),
					'logos'   => array(),
				)
			);

			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'heading'   => __( 'Ready to get started?', 'focused-schools' ),
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
endif;

get_footer();
