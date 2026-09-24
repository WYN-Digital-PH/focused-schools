<?php
/**
 * Server render for focused-schools/contact.
 *
 * Delegates to the same components the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * The form itself is never generated here. As on page-contact.php, the theme
 * only supplies a styled shell and renders whatever the site's own form
 * plugin returns for the shortcode in the `formShortcode` attribute — so the
 * form's fields, validation and post-submit actions stay owned by that plugin
 * and nothing about an existing Elementor form is bypassed or replaced. With
 * no shortcode set the section falls back to the button, which is a working
 * link to the full contact page rather than a form that goes nowhere.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_cta = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '/contact/';
$fs_cta = 0 === strpos( $fs_cta, '/' ) ? home_url( $fs_cta ) : $fs_cta;

$fs_shortcode = isset( $attributes['formShortcode'] ) ? trim( (string) $attributes['formShortcode'] ) : '';
$fs_form      = '' !== $fs_shortcode ? do_shortcode( $fs_shortcode ) : '';
$fs_badge     = focused_schools_block_image( $attributes, 'badge', '/assets/img/chamber-badge.jpg', __( '2026 Proud Member — Manatee Chamber of Commerce', 'focused-schools' ) );
$fs_link_text = isset( $attributes['linkText'] ) ? $attributes['linkText'] : '';
$fs_link_cta  = isset( $attributes['linkLabel'] ) ? $attributes['linkLabel'] : '';
?>
<section class="fs-home__contact fs-container fs-container--shell" id="contact" aria-labelledby="fs-contact-title">
	<div class="fs-home__contact-copy">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'eyebrow'    => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
				'heading'    => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
				'heading_id' => 'fs-contact-title',
			)
		);

		get_template_part( 'template-parts/components/contact-info', null, array( 'layout' => 'stacked' ) );
		?>

		<?php if ( $fs_badge['url'] ) : ?>
			<img
				class="fs-home__contact-badge"
				src="<?php echo esc_url( $fs_badge['url'] ); ?>"
				alt="<?php echo esc_attr( $fs_badge['alt'] ); ?>"
				width="176"
				height="176"
				loading="lazy"
			/>
		<?php endif; ?>

		<?php if ( $fs_link_text && $fs_link_cta ) : ?>
			<p class="fs-home__contact-aside">
				<?php echo esc_html( $fs_link_text ); ?>
				<a href="<?php echo esc_url( $fs_cta ); ?>"><?php echo esc_html( $fs_link_cta ); ?> <span aria-hidden="true">&rarr;</span></a>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( '' !== trim( (string) $fs_form ) ) : ?>
		<div class="fs-home__contact-form">
			<?php
			get_template_part(
				'template-parts/components/form-wrapper',
				null,
				array( 'inner' => $fs_form )
			);
			?>
		</div>
	<?php else : ?>
		<div class="fs-home__contact-cta">
			<p><?php echo esc_html( isset( $attributes['body'] ) ? $attributes['body'] : '' ); ?></p>
			<?php
			if ( ! empty( $attributes['ctaLabel'] ) ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $attributes['ctaLabel'],
						'url'   => $fs_cta,
						'style' => 'primary',
					)
				);
			}
			?>
		</div>
	<?php endif; ?>
</section>
