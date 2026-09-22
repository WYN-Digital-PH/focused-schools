<?php
/**
 * Home page template (static front page).
 *
 * WordPress automatically selects this template for whatever page is
 * configured as the site's static front page (Settings > Reading) — it
 * never references a specific post ID, so the real front page (e.g. Page
 * 992 in production, Page 29 in this local environment) is never replaced,
 * recreated, or hardcoded here. See docs/architecture.md §4.6.
 *
 * Design reference: https://focused-schools-rebrand.vercel.app/ (an
 * AI-generated rebrand concept — its own source marks it "north star only",
 * not a pixel spec). See docs/page-specs/home.md for the section-by-section
 * mapping, what was adapted vs. copied verbatim, and why.
 *
 * Elementor coexistence: if the front page was built with Elementor
 * (`_elementor_edit_mode` === 'builder'), this template renders only that
 * page's filtered content so Elementor's own saved output keeps rendering
 * exactly as it does today. Otherwise it renders the component-based Home
 * layout below. This detection could not be verified against real data in
 * this environment (no Elementor content exists locally) — verify against
 * the actual staging/production front page before deploying.
 *
 * Images: all `assets/img/*` paths below are placeholders per the task's
 * own instruction ("link static image placeholders to theme path") — no
 * binary image files were added (this environment/session has no rights to
 * the reference site's actual photography). Each <img> 404s harmlessly
 * until real files are dropped into those paths; alt text is already
 * correct and final.
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
	$fs_img = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
	?>
	<main id="content">
		<?php
		get_template_part(
			'template-parts/components/home-hero',
			null,
			array(
				'heading'    => __( 'We are Focused Schools', 'focused-schools' ),
				'subheading' => __( 'We help educators confidently lead the hard work and heart work of lasting school improvement.', 'focused-schools' ),
				'poster_url' => $fs_img . 'student-video.jpg',
				'youtube_id' => 'kq4YCY5eOGI',
				'cta_label'  => __( 'Our Approach', 'focused-schools' ),
				'cta_url'    => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_url' )
					? focused_schools_get_setting( 'cta_url' )
					: '#why',
			)
		);

		// Preserve whatever plain (non-Elementor) content already exists on
		// the real front page, rather than discarding it.
		if ( $fs_front_page && '' !== trim( (string) $fs_front_page->post_content ) ) :
			?>
			<div class="fs-container fs-home__intro">
				<?php echo apply_filters( 'the_content', $fs_front_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- standard the_content filter output. ?>
			</div>
			<?php
		endif;
		?>

		<div id="why">
			<?php
			get_template_part(
				'template-parts/components/content-image-split',
				null,
				array(
					'heading'        => __( 'When educators are supported, students thrive.', 'focused-schools' ),
					'body'           => __( 'Every student deserves the opportunity to succeed. That begins by supporting the people who make that success possible every day. We believe confident leaders create stronger schools, empowered educators inspire meaningful learning, and lasting school improvement happens when people, not just programs, are equipped to grow together.', 'focused-schools' ),
					'image_url'      => $fs_img . 'retreat-2.jpg',
					'image_alt'      => __( 'Two education leaders celebrating progress with a fist bump.', 'focused-schools' ),
					'image_position' => 'right',
					'cta_label'      => __( 'Get to know us', 'focused-schools' ),
					'cta_url'        => home_url( '/about-our-mission-vision/' ),
				)
			);
			?>
		</div>

		<?php
		get_template_part(
			'template-parts/components/commitment-list',
			null,
			array(
				'eyebrow'   => __( 'How we partner', 'focused-schools' ),
				'heading'   => sprintf(
					/* translators: %s: "three commitments." (emphasized). */
					__( 'Our partnerships are built on %s', 'focused-schools' ),
					'<strong>' . __( 'three commitments.', 'focused-schools' ) . '</strong>'
				),
				'intro'     => __( 'Every student gets one chance. One. Everything we do exists to help school leaders make it worth it. The true measure of our work is not what we did. It is what changed because of it.', 'focused-schools' ),
				'image_url' => $fs_img . 'retreat-1.jpg',
				'image_alt' => __( 'District and school leaders working together during a Focused Schools leadership retreat.', 'focused-schools' ),
				'items'     => array(
					array(
						'heading'   => __( 'We listen before we lead.', 'focused-schools' ),
						'body'      => __( 'Your educators know their schools best. We take the time to understand your strengths, challenges, and goals before recommending a path forward.', 'focused-schools' ),
						'cta_label' => __( 'Meet Our Team', 'focused-schools' ),
						'cta_url'   => home_url( '/team/' ),
					),
					array(
						'heading'   => __( 'We work alongside your team.', 'focused-schools' ),
						'body'      => __( "We're not here to hand you a plan and walk away. We collaborate with school and district leaders, providing coaching, guidance, and support throughout the work.", 'focused-schools' ),
						'cta_label' => __( 'Explore Our Services', 'focused-schools' ),
						'cta_url'   => home_url( '/services/' ),
					),
					array(
						'heading'   => __( 'We leave your schools stronger.', 'focused-schools' ),
						'body'      => __( 'Our goal is to build the confidence, leadership, and internal capacity that allow educators to continue the work long after our partnership ends.', 'focused-schools' ),
						'cta_label' => __( 'Our Results', 'focused-schools' ),
						'cta_url'   => home_url( '/impact-stories/' ),
					),
				),
			)
		);

		get_template_part(
			'template-parts/components/cycle-of-excellence',
			null,
			array(
				'eyebrow' => __( 'One shared focus', 'focused-schools' ),
				'heading' => __( 'A Cycle of Excellence', 'focused-schools' ),
				'body'    => __( 'High-performing districts and schools are intentional about committing to a cycle of excellence. Your Focused Schools team is prepared to support your cycle of excellence in ways that will help you to continue to build capacity, accelerate growth, and communicate your progress relentlessly.', 'focused-schools' ),
				'phases'  => array(
					array( 'label' => __( 'Build Capacity', 'focused-schools' ) ),
					array( 'label' => __( 'Accelerate Growth', 'focused-schools' ) ),
					array( 'label' => __( 'Communicate Progress', 'focused-schools' ) ),
				),
			)
		);
		?>

		<section class="fs-home__section fs-container fs-container--wide" aria-labelledby="fs-services-title">
			<?php
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'eyebrow'    => __( 'How we help', 'focused-schools' ),
					'heading'    => __( 'Every school is different. Every partnership should be, too.', 'focused-schools' ),
					'heading_id' => 'fs-services-title',
				)
			);

			$fs_services = new WP_Query(
				array(
					'post_type'      => 'fs_service',
					'posts_per_page' => 4,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
				)
			);

			if ( $fs_services->have_posts() ) :
				get_template_part( 'template-parts/components/service-list', null, array( 'posts' => $fs_services->posts ) );
				?>
				<p class="fs-home__view-all">
					<a class="fs-text-link" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">
						<?php esc_html_e( 'View all services', 'focused-schools' ); ?>
					</a>
				</p>
				<?php
			else :
				?>
				<p class="fs-home__empty"><?php esc_html_e( 'Our services list is being updated — please check back soon.', 'focused-schools' ); ?></p>
				<?php
			endif;
			wp_reset_postdata();
			?>
		</section>

		<section class="fs-home__section fs-container fs-container--wide" aria-labelledby="fs-testimonials-title">
			<?php
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'eyebrow'    => __( 'Focused Schools', 'focused-schools' ),
					'heading'    => __( 'What Leaders Are Saying', 'focused-schools' ),
					'heading_id' => 'fs-testimonials-title',
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
				get_template_part( 'template-parts/components/testimonial-carousel', null, array( 'posts' => $fs_testimonials->posts ) );
			else :
				?>
				<p class="fs-home__empty"><?php esc_html_e( 'Testimonials from the leaders we partner with are on the way.', 'focused-schools' ); ?></p>
				<?php
			endif;
			wp_reset_postdata();
			?>
		</section>

		<?php
		/*
		 * Real, current figures confirmed against the approved design
		 * reference (docs/page-specs/home.md) — not a TODO placeholder,
		 * unlike the prior draft of this template.
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
							'value'  => 2,
							'suffix' => '+ million',
							'label'  => __( 'Students Impacted', 'focused-schools' ),
						),
						array(
							'value'  => 20,
							'suffix' => '+',
							'label'  => __( 'Years Partnering with Schools', 'focused-schools' ),
						),
						array(
							'value'  => 25,
							'suffix' => '+',
							'label'  => __( 'States Served', 'focused-schools' ),
						),
					),
				)
			);
			?>
		</div>

		<section class="fs-home__contact fs-container fs-container--wide" id="contact" aria-labelledby="fs-contact-title">
			<div class="fs-home__contact-copy">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow'    => __( 'Contact', 'focused-schools' ),
						'heading'    => __( 'Get to Know Us.', 'focused-schools' ),
						'heading_id' => 'fs-contact-title',
					)
				);
				get_template_part( 'template-parts/components/contact-info' );
				?>
			</div>
			<div class="fs-home__contact-cta">
				<p><?php esc_html_e( "Ready to talk about your district's next step? Send us a message and a member of our team will follow up soon.", 'focused-schools' ); ?></p>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => __( 'Contact Us', 'focused-schools' ),
						'url'   => home_url( '/contact/' ),
						'style' => 'primary',
					)
				);
				?>
			</div>
		</section>
	</main>
	<?php
endif;

get_footer();
