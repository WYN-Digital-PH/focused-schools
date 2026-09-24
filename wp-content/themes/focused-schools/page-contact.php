<?php
/**
 * Template Name: Contact
 *
 * Page template for the Contact page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "contact" (the Page ID is never referenced, so the real page keeps its ID,
 * slug and URL).
 *
 * Form preservation (the central constraint): the real contact form — an
 * Elementor Pro form on the live site — is never generated, modified or
 * replaced here. Whatever the page's own content renders (the_content(),
 * which for an Elementor page is Elementor's saved output) is placed
 * unmodified inside the design's form card and only restyled. Fields,
 * validation, notification/mail handling and the "Actions After Submit"
 * redirect to /thanks/ all stay in the form's own settings; the design's
 * success presentation lives on /thanks/ (page-thanks.php).
 *
 * The Elementor branch used to render only the_content(), which would have
 * discarded the approved layout on an Elementor-built page. The layout now
 * always renders and Elementor's output sits inside the form card, so the
 * page's Elementor content should be the form itself.
 *
 * Design source: `Focused Schools Contact.dc.html`. Sections in order: hero,
 * form + aside, "Get to know how we work first" link cards. No closing CTA
 * banner. See docs/page-specs/contact.md.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		ob_start();
		the_content();
		$fs_form_content = trim( (string) ob_get_clean() );

		$fs_excerpt = has_excerpt() ? get_the_excerpt() : '';
		$fs_img     = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/rail-text',
				null,
				array(
					'eyebrow'        => get_the_title() ? get_the_title() : __( 'Contact us', 'focused-schools' ),
					'icon_url'       => $fs_img . 'mark-1.svg',
					'heading'        => __( "Let's start a conversation.", 'focused-schools' ),
					'heading_tag'    => 'h1',
					'heading_size'   => 68,
					'heading_gap'    => 26,
					'heading_max_ch' => 18,
					'padding_top'    => 72,
					'padding_bottom' => 80,
					'body_max_ch'    => 62,
					'body'           => $fs_excerpt ? $fs_excerpt : __( 'Tell us where your district is headed and what is getting in the way. We will read every word, and a real person from our team will reply — usually within two business days.', 'focused-schools' ),
				)
			);
			?>

			<section class="fs-contact-shell" id="form" aria-labelledby="fs-contact-form-title">
				<div class="fs-container fs-container--shell fs-contact-shell__grid">
					<div class="fs-contact-form fs-contact-card">
						<header class="fs-contact-form__head">
							<h2 id="fs-contact-form-title"><?php esc_html_e( 'Send us a message', 'focused-schools' ); ?></h2>
							<p><?php esc_html_e( 'Fields marked * are required. Everything else helps us route your message to the right person.', 'focused-schools' ); ?></p>
						</header>
						<?php
						if ( '' !== $fs_form_content ) :
							get_template_part(
								'template-parts/components/form-wrapper',
								null,
								array(
									'inner'   => $fs_form_content,
									'variant' => 'embedded',
								)
							);
							?>
							<p class="fs-contact-form__note"><?php esc_html_e( 'We reply within two business days. We never share your information.', 'focused-schools' ); ?></p>
						<?php else : ?>
							<p class="fs-contact__empty"><?php esc_html_e( 'Our contact form is being updated — please check back soon.', 'focused-schools' ); ?></p>
						<?php endif; ?>
					</div>

					<?php get_template_part( 'template-parts/components/contact-aside' ); ?>
				</div>
			</section>

			<section class="fs-contact-reach" aria-labelledby="fs-contact-reach-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-contact-reach__head">
						<p class="fs-eyebrow"><?php esc_html_e( 'Not ready to write yet?', 'focused-schools' ); ?></p>
						<h2 id="fs-contact-reach-title"><?php esc_html_e( 'Get to know how we work first.', 'focused-schools' ); ?></h2>
					</header>
					<?php
					get_template_part(
						'template-parts/components/link-cards',
						null,
						array(
							'cards' => array(
								array(
									'title'  => __( 'Impact Stories', 'focused-schools' ),
									'note'   => __( 'What changed in districts that partnered with us.', 'focused-schools' ),
									'cta'    => __( 'Read stories', 'focused-schools' ),
									'url'    => home_url( '/impact-stories/' ),
									'accent' => 'cerulean',
								),
								array(
									'title'  => __( 'Our Services', 'focused-schools' ),
									'note'   => __( 'Three lanes, one cycle of inquiry.', 'focused-schools' ),
									'cta'    => __( 'Explore services', 'focused-schools' ),
									'url'    => home_url( '/services/' ),
									'accent' => 'coral',
								),
								array(
									'title'  => __( 'The Podcast', 'focused-schools' ),
									'note'   => __( 'Conversations with leaders doing this work now.', 'focused-schools' ),
									'cta'    => __( 'Listen', 'focused-schools' ),
									'url'    => home_url( '/podcast/' ),
									'accent' => 'raspberry',
								),
								array(
									'title'  => __( 'Meet the Team', 'focused-schools' ),
									'note'   => __( 'Who you would actually be working with.', 'focused-schools' ),
									'cta'    => __( 'See the team', 'focused-schools' ),
									'url'    => home_url( '/team/' ),
									'accent' => 'lime',
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
