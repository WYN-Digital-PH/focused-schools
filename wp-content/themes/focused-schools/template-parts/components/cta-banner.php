<?php
/**
 * Component: CTA Banner.
 *
 * Contract ($args):
 * - heading     (string, required)
 * - eyebrow     (string) small label above the heading (added for the Services page)
 * - description (string)
 * - cta_label   (string, required)
 * - cta_url     (string, required)
 * - cta2_label  (string) optional second, lower-emphasis CTA (added for the
 *                Services page, whose closing banner has two buttons)
 * - cta2_url    (string) required when cta2_label is set
 *
 * A highlighted-band closing call-to-action section (e.g. "Ready to get
 * started?"), distinct from the single-link button.php component it
 * composes internally.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading     = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_eyebrow     = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_cta_label   = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url     = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
$fs_cta2_label  = isset( $args['cta2_label'] ) ? $args['cta2_label'] : '';
$fs_cta2_url    = isset( $args['cta2_url'] ) ? $args['cta2_url'] : '';

if ( '' === trim( (string) $fs_heading ) || '' === trim( (string) $fs_cta_label ) || '' === trim( (string) $fs_cta_url ) ) {
	return;
}
?>
<section class="fs-cta-banner">
	<div class="fs-container fs-cta-banner__inner">
		<?php if ( $fs_eyebrow ) : ?>
			<p class="fs-eyebrow fs-eyebrow--on-dark fs-cta-banner__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
		<?php endif; ?>
		<h2 class="fs-cta-banner__heading"><?php echo esc_html( $fs_heading ); ?></h2>
		<?php if ( $fs_description ) : ?>
			<p class="fs-cta-banner__description"><?php echo esc_html( $fs_description ); ?></p>
		<?php endif; ?>
		<div class="fs-cta-banner__actions">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
					'style' => 'secondary',
				)
			);

			if ( '' !== trim( (string) $fs_cta2_label ) && '' !== trim( (string) $fs_cta2_url ) ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta2_label,
						'url'   => $fs_cta2_url,
						'style' => 'text',
					)
				);
			}
			?>
		</div>
	</div>
</section>
