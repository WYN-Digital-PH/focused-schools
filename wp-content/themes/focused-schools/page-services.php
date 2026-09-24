<?php
/**
 * Template Name: Services
 *
 * Page template for the Services page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "services" — the ID (Page 1187) is never referenced or hardcoded, so the
 * real page is never replaced, recreated, or touched. If the real page's
 * slug ever changes, this template simply stops applying rather than
 * silently targeting the wrong page.
 *
 * Elementor coexistence: same as page-about-our-mission-vision.php — a
 * page-{slug}.php template is always scoped to that exact page via URL
 * routing, so the standard Loop is reliable. If `_elementor_edit_mode`
 * === 'builder' on this page, only its filtered content renders;
 * otherwise the sections below render.
 *
 * Known limitation: Page 1187 does not exist in this local environment
 * (verified via direct database query), so the Elementor-detection branch
 * could not be verified against real data — verify against the actual
 * staging/production page before deploying.
 *
 * Design reference: the approved mockup's /#/services route. Section order
 * and copy are taken from it verbatim; see docs/page-specs/services.md for
 * the section-by-section spec and the data each one reads.
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

		// One query drives both the numbered index and the detail lanes, so
		// the two can never disagree about which services exist or their
		// order. Editors manage the list entirely via the fs_service CPT.
		$fs_services = new WP_Query(
			array(
				'post_type'      => 'fs_service',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'eyebrow'    => __( 'Our services', 'focused-schools' ),
					'heading'    => __( 'Support shaped around where your district actually is.', 'focused-schools' ),
					'subheading' => __( "No two districts face the same challenges, which is why we don't believe in one-size-fits-all support.", 'focused-schools' ),
					'image_url'  => $fs_img . 'retreat-1.jpg',
					'image_alt'  => __( 'District leaders working together in a Focused Schools session.', 'focused-schools' ),
					'cta_label'  => __( 'See How We Help', 'focused-schools' ),
					'cta_url'    => '#how-we-help',
				)
			);

			// Preserve any Gutenberg/native content an editor has added
			// directly to this page, rather than discarding it.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-services__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;
			?>

			<section class="fs-services__section fs-container fs-container--shell" id="how-we-help" aria-labelledby="fs-services-heading">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow'       => __( 'How we help', 'focused-schools' ),
						'heading'       => __( 'Every school is different. So is every plan.', 'focused-schools' ),
						'description'   => __( 'Three lanes of partnered support, all running the same cycle of inquiry underneath. Most districts start in one and grow into another.', 'focused-schools' ),
						'heading_level' => 2,
						'heading_id'    => 'fs-services-heading',
					)
				);

				if ( $fs_services->have_posts() ) :
					get_template_part(
						'template-parts/components/service-list',
						null,
						array(
							'posts'     => $fs_services->posts,
							'variant'   => 'index',
							'nav_label' => __( 'Jump to a service', 'focused-schools' ),
						)
					);
				else :
					?>
					<p class="fs-services__empty"><?php esc_html_e( 'Our services list is being updated — check back soon.', 'focused-schools' ); ?></p>
					<?php
				endif;
				?>
			</section>

			<?php
			// Detail lanes. Backgrounds and sides alternate down the page,
			// matching the mockup: every second lane sits on the paper tint
			// with its photo on the opposite side.
			if ( $fs_services->have_posts() ) :
				$fs_lane_index = 0;

				// Theme-bundled fallbacks, used only until each service has
				// its own featured image set in wp-admin. Same photo set the
				// Home and About pages already use.
				$fs_lane_photos = array( 'retreat-1.jpg', 'retreat-3.jpg', 'retreat-2.jpg' );

				foreach ( $fs_services->posts as $fs_service_post ) :
					++$fs_lane_index;
					$fs_alt   = ( 0 === $fs_lane_index % 2 );
					$fs_photo = $fs_lane_photos[ ( $fs_lane_index - 1 ) % count( $fs_lane_photos ) ];
					?>
					<section class="fs-services__lane<?php echo $fs_alt ? ' fs-services__lane--paper' : ''; ?>" aria-labelledby="fs-lane-<?php echo esc_attr( $fs_service_post->post_name ); ?>">
						<div class="fs-container fs-container--shell">
							<?php
							get_template_part(
								'template-parts/components/service-lane',
								null,
								array(
									'post'      => $fs_service_post,
									'index'     => $fs_lane_index,
									'flip'      => $fs_alt,
									'image_url' => $fs_img . $fs_photo,
									'image_alt' => sprintf(
										/* translators: %s: service name. */
										__( 'Focused Schools partners at work: %s.', 'focused-schools' ),
										$fs_service_post->post_title
									),
								)
							);
							?>
						</div>
					</section>
					<?php
				endforeach;
			endif;
			wp_reset_postdata();

			get_template_part(
				'template-parts/components/cycle-of-excellence',
				null,
				array(
					'eyebrow'  => __( 'The method underneath', 'focused-schools' ),
					'heading'  => __( 'A Cycle of Excellence', 'focused-schools' ),
					'body'     => __( 'High-performing districts and schools are intentional about committing to a cycle of excellence. Your Focused Schools team is prepared to support your cycle of excellence in ways that will help you to continue to build capacity, accelerate growth, and communicate your progress relentlessly.', 'focused-schools' ),
					'mark_url' => $fs_img . 'mark-cycle.svg',
					'phases'   => array(
						array( 'label' => __( 'Build Capacity', 'focused-schools' ) ),
						array( 'label' => __( 'Accelerate Growth', 'focused-schools' ) ),
						array( 'label' => __( 'Communicate Progress', 'focused-schools' ) ),
					),
				)
			);

			$fs_testimonials = new WP_Query(
				array(
					'post_type'      => 'fs_testimonial',
					'posts_per_page' => 6,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
				)
			);

		if ( $fs_testimonials->have_posts() ) :
			?>
				<section class="fs-services__quote">
					<div class="fs-container fs-container--shell">
					<?php get_template_part( 'template-parts/components/testimonial-carousel', null, array( 'posts' => $fs_testimonials->posts ) ); ?>
					</div>
				</section>
				<?php
			endif;
			wp_reset_postdata();

			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'eyebrow'     => __( 'Start somewhere', 'focused-schools' ),
					'heading'     => __( 'Not sure which lane you need?', 'focused-schools' ),
					'description' => __( "Most districts don't, at first. Tell us what is getting in the way and we will tell you honestly whether and how we can help — before anyone writes a proposal.", 'focused-schools' ),
					'cta_label'   => __( "Let's Talk", 'focused-schools' ),
					'cta_url'     => home_url( '/contact/' ),
					'cta2_label'  => __( 'See Impact Stories', 'focused-schools' ),
					'cta2_url'    => home_url( '/impact-stories/' ),
					'image_url'   => $fs_img . 'retreat-2.jpg',
					'image_alt'   => __( 'Two education leaders in conversation.', 'focused-schools' ),
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
