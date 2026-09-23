<?php
/**
 * Server render for focused-schools/contact.
 *
 * Delegates to the same components the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

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
		get_template_part( 'template-parts/components/contact-info' );
		?>
	</div>
	<div class="fs-home__contact-cta">
		<p><?php echo esc_html( isset( $attributes['body'] ) ? $attributes['body'] : '' ); ?></p>
		<?php
		if ( ! empty( $attributes['ctaLabel'] ) ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $attributes['ctaLabel'],
					'url'   => home_url( '/contact/' ),
					'style' => 'primary',
				)
			);
		}
		?>
	</div>
</section>
