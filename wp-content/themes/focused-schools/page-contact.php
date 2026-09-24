<?php
/**
 * Contact page.
 *
 * This template is presentation only. It does not generate, validate,
 * submit, route or redirect a form, and it must not: Elementor Pro owns the
 * form on this page, along with its saved submissions, its notification
 * emails and its redirect to /thanks/.
 *
 * Two ways the form reaches the page, both of them untouched pass-throughs:
 *
 * - if the page is Elementor-built, the whole page renders Elementor's own
 *   output and none of the design below applies;
 * - otherwise the page's own content — whatever shortcode, widget or block
 *   it holds — is rendered into the form column exactly as stored.
 *
 * So the form's fields, its recipients and its actions cannot be altered
 * from here. See docs/blog-coexistence.md for the same rule on posts.
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

		$fs_img  = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
		$fs_form = trim( (string) get_the_content() );
		?>

		<main id="content" class="fs-contact">
			<section class="fs-contact__hero">
				<div class="fs-container fs-container--shell fs-rail">
					<div class="fs-rail__label">
						<img src="<?php echo esc_url( $fs_img . 'mark-1.svg' ); ?>" alt="" width="28" height="28" />
						<p class="fs-eyebrow"><?php esc_html_e( 'Contact us', 'focused-schools' ); ?></p>
					</div>
					<div>
						<h1 class="fs-contact__title"><?php esc_html_e( "Let's start a conversation.", 'focused-schools' ); ?></h1>
						<p class="fs-contact__lead"><?php esc_html_e( 'Tell us where your district is headed and what is getting in the way. We will read every word, and a real person from our team will reply — usually within two business days.', 'focused-schools' ); ?></p>
					</div>
				</div>
			</section>

			<section class="fs-contact__form-section" id="form">
				<div class="fs-container fs-container--shell fs-contact-grid">
					<div class="fs-form-col">
						<?php
						if ( '' !== $fs_form ) :
							/*
							 * The page's own content, rendered through
							 * the_content so the form plugin's shortcode or
							 * block runs exactly as it does today.
							 */
							get_template_part(
								'template-parts/components/form-wrapper',
								null,
								array( 'inner' => apply_filters( 'the_content', $fs_form ) )
							);
						else :
							?>
							<div class="fs-panel fs-contact__empty">
								<p><?php esc_html_e( 'Our contact form is being updated — please check back soon.', 'focused-schools' ); ?></p>
								<p class="fs-mini"><?php esc_html_e( 'In the meantime, the details on the right reach us directly.', 'focused-schools' ); ?></p>
							</div>
							<?php
						endif;
						?>
					</div>

					<?php
					get_template_part(
						'template-parts/components/contact-aside',
						null,
						array(
							'badge_url' => $fs_img . 'chamber-badge.jpg',
							'badge_alt' => __( '2026 Proud Member — Manatee Chamber of Commerce', 'focused-schools' ),
						)
					);
					?>
				</div>
			</section>

			<section class="fs-contact__reach" aria-labelledby="fs-contact-reach-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-blog__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'Not ready to write yet?', 'focused-schools' ); ?></p>
							<h2 class="fs-blog__heading" id="fs-contact-reach-title"><?php esc_html_e( 'Get to know how we work first.', 'focused-schools' ); ?></h2>
						</div>
					</header>

					<?php
					get_template_part(
						'template-parts/components/reach-cards',
						null,
						array(
							'cards' => array(
								array(
									'title' => __( 'Impact Stories', 'focused-schools' ),
									'body'  => __( 'What changed in districts that partnered with us.', 'focused-schools' ),
									'label' => __( 'Read stories', 'focused-schools' ),
									'url'   => home_url( '/impact-stories/' ),
									'rule'  => 'cerulean',
								),
								array(
									'title' => __( 'Our Services', 'focused-schools' ),
									'body'  => __( 'Three lanes, one cycle of inquiry.', 'focused-schools' ),
									'label' => __( 'Explore services', 'focused-schools' ),
									'url'   => home_url( '/services/' ),
									'rule'  => '',
								),
								array(
									'title' => __( 'The Podcast', 'focused-schools' ),
									'body'  => __( 'Conversations with leaders doing this work now.', 'focused-schools' ),
									'label' => __( 'Listen', 'focused-schools' ),
									'url'   => home_url( '/podcast/' ),
									'rule'  => 'rasp',
								),
								array(
									'title' => __( 'Meet the Team', 'focused-schools' ),
									'body'  => __( 'Educators first — the people who would carry the work.', 'focused-schools' ),
									'label' => __( 'Meet the team', 'focused-schools' ),
									'url'   => home_url( '/team/' ),
									'rule'  => 'lime',
								),
							),
						)
					);
					?>
				</div>
			</section>
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
