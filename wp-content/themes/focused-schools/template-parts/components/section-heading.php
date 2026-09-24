<?php
/**
 * Component: Section Heading.
 *
 * Contract ($args):
 * - heading       (string, required) allows basic HTML (run through
 *                  wp_kses_post()) so a caller can mix regular/bold weight
 *                  within it — plain-text headings render identically to
 *                  before.
 * - eyebrow       (string)
 * - description   (string)
 * - heading_level (int) 2-4, default 2
 * - alignment     (string) 'left'|'center', default 'left'
 * - heading_id    (string) optional id attribute on the heading element,
 *                  e.g. for aria-labelledby on an ancestor <section>
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow     = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading     = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_alignment   = isset( $args['alignment'] ) && 'center' === $args['alignment'] ? 'center' : 'left';
$fs_level       = isset( $args['heading_level'] ) ? absint( $args['heading_level'] ) : 2;
$fs_level       = in_array( $fs_level, array( 2, 3, 4 ), true ) ? $fs_level : 2;
$fs_tag         = tag_escape( 'h' . $fs_level );
$fs_heading_id  = isset( $args['heading_id'] ) ? $args['heading_id'] : '';

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<div class="fs-section-heading fs-section-heading--align-<?php echo esc_attr( $fs_alignment ); ?>">
	<?php if ( $fs_eyebrow ) : ?>
		<p class="fs-section-heading__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
	<?php endif; ?>
	<?php if ( $fs_heading_id ) : ?>
		<?php printf( '<%1$s id="%3$s" class="fs-section-heading__heading">%2$s</%1$s>', esc_html( $fs_tag ), wp_kses_post( $fs_heading ), esc_attr( $fs_heading_id ) ); ?>
	<?php else : ?>
		<?php printf( '<%1$s class="fs-section-heading__heading">%2$s</%1$s>', esc_html( $fs_tag ), wp_kses_post( $fs_heading ) ); ?>
	<?php endif; ?>
	<?php if ( $fs_description ) : ?>
		<p class="fs-section-heading__description"><?php echo esc_html( $fs_description ); ?></p>
	<?php endif; ?>
</div>
