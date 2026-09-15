<?php
/**
 * Component: Form styling wrapper (Elementor form coexistence).
 *
 * Contract ($args):
 * - inner   (string, required) pre-rendered form HTML, e.g. the output of
 *           do_shortcode( '[elementor-template id="123"]' ). This component
 *           never generates or modifies form markup itself — it only
 *           provides a consistently-styled wrapper. Styling (see
 *           assets/css/components/form-wrapper.css) targets both generic
 *           form elements and Elementor's own classes
 *           (.elementor-form, .elementor-field-group, .elementor-button)
 *           so it doesn't fight Elementor's structure or specificity.
 * - heading (string)
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_inner   = isset( $args['inner'] ) ? $args['inner'] : '';
$fs_heading = isset( $args['heading'] ) ? $args['heading'] : '';

if ( '' === trim( (string) $fs_inner ) ) {
	return;
}
?>
<div class="fs-form">
	<div class="fs-container fs-form__inner">
		<?php if ( $fs_heading ) : ?>
			<h2 class="fs-form__heading"><?php echo esc_html( $fs_heading ); ?></h2>
		<?php endif; ?>
		<div class="fs-form__content">
			<?php echo $fs_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-rendered trusted form markup supplied by the calling template (e.g. Elementor shortcode output). ?>
		</div>
	</div>
</div>
