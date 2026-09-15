<?php
/**
 * Component: CTA Banner.
 *
 * Contract ($args):
 * - heading     (string, required)
 * - description (string)
 * - cta_label   (string, required)
 * - cta_url     (string, required)
 *
 * A highlighted-band closing call-to-action section (e.g. "Ready to get
 * started?"), distinct from the single-link button.php component it
 * composes internally.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading     = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_cta_label   = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url     = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_heading ) || '' === trim( (string) $fs_cta_label ) || '' === trim( (string) $fs_cta_url ) ) {
	return;
}
?>
<section class="fs-cta-banner">
	<div class="fs-container fs-cta-banner__inner">
		<h2 class="fs-cta-banner__heading"><?php echo esc_html( $fs_heading ); ?></h2>
		<?php if ( $fs_description ) : ?>
			<p class="fs-cta-banner__description"><?php echo esc_html( $fs_description ); ?></p>
		<?php endif; ?>
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
		?>
	</div>
</section>
