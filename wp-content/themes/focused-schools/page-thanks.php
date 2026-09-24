<?php
/**
 * Template Name: Thank You
 *
 * Page template for the Thank You page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "thanks" (the Page ID is never referenced).
 *
 * This is the destination of the contact form's own "Actions After Submit"
 * redirect, which stays configured entirely inside the form's settings — this
 * template has no form or redirect logic. It carries the Contact design's
 * success presentation: the same hero and aside as /contact/, with the
 * confirmation panel where the form would be. The phone number in the panel
 * comes from Site Settings.
 *
 * The panel applies only while the page holds no content of its own. If the
 * page holds a full Elementor layout or written copy (the live confirmation
 * message), that content renders on its own, with no theme wrappers, so the
 * visitor never sees two confirmations.
 *
 * See docs/page-specs/contact.md §5.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		if ( ! focused_schools_is_form_only_content( get_the_ID() ) ) :
			// A full Elementor layout (or copy an editor wrote) renders as the
			// page's own content, with none of this template's wrappers.
			$fs_builder = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
			?>
			<main id="content"<?php echo $fs_builder ? '' : ' class="fs-container"'; ?>>
				<?php the_content(); ?>
			</main>
			<?php
			continue;
		endif;

		ob_start();
		the_content();
		$fs_extra = trim( (string) ob_get_clean() );

		$fs_img       = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
		$fs_phone     = function_exists( 'focused_schools_get_setting' ) ? (string) focused_schools_get_setting( 'phone' ) : '';
		$fs_phone_tel = preg_replace( '/[^0-9+]/', '', $fs_phone );
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/rail-text',
				null,
				array(
					'eyebrow'        => __( 'Contact us', 'focused-schools' ),
					'icon_url'       => $fs_img . 'mark-1.svg',
					'heading'        => __( "Let's start a conversation.", 'focused-schools' ),
					'heading_tag'    => 'h1',
					'heading_size'   => 68,
					'heading_gap'    => 26,
					'heading_max_ch' => 18,
					'padding_top'    => 72,
					'padding_bottom' => 80,
					'body_max_ch'    => 62,
					'body'           => has_excerpt() ? get_the_excerpt() : __( 'Tell us where your district is headed and what is getting in the way. We will read every word, and a real person from our team will reply — usually within two business days.', 'focused-schools' ),
				)
			);
			?>

			<section class="fs-contact-shell" aria-labelledby="fs-thanks-title">
				<div class="fs-container fs-container--shell fs-contact-shell__grid">
					<div class="fs-thanks-panel fs-contact-card" role="status" aria-live="polite">
						<span class="fs-thanks-panel__check" aria-hidden="true">&#10003;</span>
						<p class="fs-thanks-panel__eyebrow"><?php esc_html_e( 'Message sent', 'focused-schools' ); ?></p>
						<h2 id="fs-thanks-title"><?php esc_html_e( 'Thank you — we have your message.', 'focused-schools' ); ?></h2>
						<p class="fs-thanks-panel__body">
							<?php esc_html_e( 'A member of our team will reply within two business days.', 'focused-schools' ); ?>
							<?php if ( $fs_phone && $fs_phone_tel ) : ?>
								<?php esc_html_e( 'If your question is time-sensitive, call us at', 'focused-schools' ); ?>
								<a href="<?php echo esc_url( 'tel:' . $fs_phone_tel ); ?>"><?php echo esc_html( $fs_phone ); ?></a>.
							<?php endif; ?>
						</p>
						<div class="fs-thanks-panel__next">
							<p class="fs-thanks-panel__label"><?php esc_html_e( 'While you wait', 'focused-schools' ); ?></p>
							<div class="fs-thanks-panel__actions">
								<?php
								get_template_part(
									'template-parts/components/button',
									null,
									array(
										'label' => __( 'Read Impact Stories', 'focused-schools' ),
										'url'   => home_url( '/impact-stories/' ),
										'style' => 'secondary',
									)
								);
								get_template_part(
									'template-parts/components/button',
									null,
									array(
										'label' => __( 'Listen to the Podcast', 'focused-schools' ),
										'url'   => home_url( '/podcast/' ),
										'style' => 'secondary',
									)
								);
								?>
							</div>
						</div>
					</div>

					<?php get_template_part( 'template-parts/components/contact-aside' ); ?>
				</div>
			</section>

			<?php if ( '' !== $fs_extra ) : ?>
				<div class="fs-container fs-thanks__content">
					<?php echo $fs_extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the page's own the_content() output, already filtered. ?>
				</div>
			<?php endif; ?>
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
