<?php
/**
 * Server render for focused-schools/contact-form.
 *
 * This block does not build, validate, submit or route a form, and must not.
 * Elementor Pro owns the contact form, along with its saved submissions, its
 * notification emails and its redirect to /thanks/.
 *
 * The shortcode an editor puts in `formShortcode` is run through
 * do_shortcode(), so the form plugin renders exactly what it renders today.
 * With nothing set, the page's own remaining content is used instead — which
 * is how a page that already held the form keeps working after this block is
 * added, without anyone having to move the shortcode by hand.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_shortcode = isset( $attributes['formShortcode'] ) ? trim( (string) $attributes['formShortcode'] ) : '';
$fs_form      = '' !== $fs_shortcode ? do_shortcode( $fs_shortcode ) : '';
$fs_img       = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
?>
<section class="fs-contact__form-section" id="form">
	<div class="fs-container fs-container--shell fs-contact-grid">
		<div class="fs-form-col">
			<?php
			if ( '' !== trim( (string) $fs_form ) ) :
				get_template_part(
					'template-parts/components/form-wrapper',
					null,
					array( 'inner' => $fs_form )
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
