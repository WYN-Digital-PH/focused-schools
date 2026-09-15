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
 * Elementor coexistence: unlike front-page.php, this template can rely on
 * the standard Loop directly — a page-{slug}.php template is always scoped
 * to that exact page via URL routing, with no "Settings > Reading"
 * ambiguity. If `_elementor_edit_mode` === 'builder' on this page, only its
 * filtered content renders; otherwise the five sections from
 * docs/page-specs/about.md render.
 *
 * Known limitation: Page 3726 does not exist in this local environment
 * (verified via direct database query), so the Elementor-detection branch
 * could not be verified against real data — verify against the actual
 * staging/production page before deploying.
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
					'heading'    => get_the_title() ? get_the_title() : __( 'About Our Mission & Vision', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( 'We partner with district and school leaders to build the systems, skills, and culture that turn ambitious goals into lasting results.', 'focused-schools' ),
					'cta_label'  => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '',
					'cta_url'    => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '',
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
				'template-parts/components/content-image-split',
				null,
				array(
					'heading'        => __( 'Our Mission', 'focused-schools' ),
					'body'           => '<p>' . esc_html__( 'Focused Schools exists to help school systems translate strategy into daily practice. We work side-by-side with leaders to strengthen instructional systems, build leadership capacity, and create the conditions every student needs to thrive.', 'focused-schools' ) . '</p>',
					'image_position' => 'right',
				)
			);

			get_template_part(
				'template-parts/components/content-image-split',
				null,
				array(
					'heading'        => __( 'Our Vision', 'focused-schools' ),
					'body'           => '<p>' . esc_html__( 'We envision a future where every school system has the leadership capacity, coherent systems, and culture of continuous improvement needed to deliver excellent outcomes for every student, in every classroom, every year.', 'focused-schools' ) . '</p>',
					'image_position' => 'left',
				)
			);
			?>

			<section class="fs-about__section fs-container fs-container--wide">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow' => __( 'Who We Are', 'focused-schools' ),
						'heading' => __( 'Our Leadership Team', 'focused-schools' ),
					)
				);

				$fs_team = new WP_Query(
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

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'heading'     => __( 'Ready to transform your school leadership?', 'focused-schools' ),
					'description' => __( "Let's talk about the challenges your district is facing and how we can help you build lasting change.", 'focused-schools' ),
					'cta_label'   => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_label' )
						? focused_schools_get_setting( 'cta_label' )
						: __( 'Contact Us', 'focused-schools' ),
					'cta_url'     => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_url' )
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
