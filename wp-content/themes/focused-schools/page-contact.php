<?php
/**
 * Template Name: Contact
 *
 * Page template for the Contact page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "contact" — no numeric Page ID was provided for this task, but none is
 * needed: this mechanism never references an ID.
 *
 * Form preservation (the central constraint for this template): the real
 * contact form is never generated, modified, or replaced here.
 *
 * - If the page is Elementor-built (_elementor_edit_mode === 'builder'),
 *   only the_content() renders — Elementor's own saved output, completely
 *   untouched — exactly like every other page template's Elementor branch.
 * - Otherwise, the_content() (whatever shortcode/widget/block the page
 *   actually contains) is passed unmodified into form-wrapper.php's
 *   `inner` prop, purely for consistent visual styling. No assumption is
 *   made about which form plugin is in use.
 *
 * The redirect to /thanks/ after submission is configured entirely within
 * the form's own settings (e.g. Elementor's "Actions After Submit") —
 * external to this file.
 *
 * No closing CTA Banner on this page, deliberately: the page's whole
 * purpose already is the call to action.
 *
 * See docs/page-specs/contact.md for the full spec.
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
					'heading'    => get_the_title() ? get_the_title() : __( 'Contact Us', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( "We'd love to hear from you — reach out and a member of our team will follow up soon.", 'focused-schools' ),
				)
			);

			?>
			<div class="fs-contact__info-wrap fs-container fs-container--wide">
				<?php get_template_part( 'template-parts/components/contact-info' ); ?>
			</div>
			<?php

			$fs_form_content = get_the_content();

			if ( '' !== trim( (string) $fs_form_content ) ) :
				get_template_part(
					'template-parts/components/form-wrapper',
					null,
					array(
						'inner' => apply_filters( 'the_content', $fs_form_content ),
					)
				);
			else :
				?>
				<div class="fs-container fs-contact__empty">
					<p><?php esc_html_e( 'Our contact form is being updated — please check back soon.', 'focused-schools' ); ?></p>
				</div>
				<?php
			endif;
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
