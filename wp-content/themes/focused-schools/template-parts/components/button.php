<?php
/**
 * Component: Button / CTA.
 *
 * Contract ($args):
 * - label  (string, required)
 * - url    (string, required)
 * - style  (string) 'primary'|'secondary'|'outline', default 'primary'
 * - target (string) e.g. '_blank' (adds rel="noopener noreferrer")
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_label  = isset( $args['label'] ) ? $args['label'] : '';
$fs_url    = isset( $args['url'] ) ? $args['url'] : '';
$fs_style  = isset( $args['style'] ) ? $args['style'] : 'primary';
$fs_style  = in_array( $fs_style, array( 'primary', 'secondary', 'outline' ), true ) ? $fs_style : 'primary';
$fs_target = isset( $args['target'] ) ? $args['target'] : '';

if ( '' === trim( (string) $fs_label ) || '' === trim( (string) $fs_url ) ) {
	return;
}
?>
<a
	href="<?php echo esc_url( $fs_url ); ?>"
	class="fs-btn fs-btn--<?php echo esc_attr( $fs_style ); ?>"
	<?php echo '_blank' === $fs_target ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
>
	<?php echo esc_html( $fs_label ); ?>
</a>
