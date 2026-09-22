<?php
/**
 * Component: Button / CTA.
 *
 * Contract ($args):
 * - label  (string, required)
 * - url    (string, required)
 * - style  (string) 'primary'|'secondary'|'white'|'text', default 'primary'.
 *   'white' is solid white fill + teal label, for CTAs on a teal ground
 *   (e.g. "View Impact Stories" in a teal stats band) — distinct from
 *   'secondary' (white fill + teal border, for light grounds).
 *   'text' is the bare arrow-link style (no pill), e.g. "View all services".
 * - target (string) e.g. '_blank' (adds rel="noopener noreferrer")
 * - arrow  (bool) trailing arrow glyph, default true — matches the approved
 *   design, where every button/link carries one.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_label  = isset( $args['label'] ) ? $args['label'] : '';
$fs_url    = isset( $args['url'] ) ? $args['url'] : '';
$fs_style  = isset( $args['style'] ) ? $args['style'] : 'primary';
$fs_style  = in_array( $fs_style, array( 'primary', 'secondary', 'white', 'text' ), true ) ? $fs_style : 'primary';
$fs_target = isset( $args['target'] ) ? $args['target'] : '';
$fs_arrow  = ! isset( $args['arrow'] ) || $args['arrow'];

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
	<?php if ( $fs_arrow ) : ?>
		<span aria-hidden="true" class="fs-btn__arrow">&rarr;</span>
	<?php endif; ?>
</a>
