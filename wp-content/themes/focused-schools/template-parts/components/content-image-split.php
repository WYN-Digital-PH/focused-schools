<?php
/**
 * Component: Content/Image Split.
 *
 * Contract ($args):
 * - heading         (string)
 * - body            (string, allows basic HTML — run through wp_kses_post())
 * - image_id        (int) attachment ID
 * - image_url       (string) static image URL, used only when image_id is not set
 *                    (e.g. a theme-bundled placeholder via get_template_directory_uri())
 * - image_alt       (string) alt text for image_url (image_id already carries its own)
 * - image_position  (string) 'left'|'right', default 'right'
 * - cta_label       (string)
 * - cta_url         (string)
 *
 * Always renders in the wide (.fs-container--wide, 1200px) container — same
 * treatment as Hero and CTA Banner, for uniform section widths across a
 * page (see docs/architecture.md §4.6/§4.7).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading   = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body      = isset( $args['body'] ) ? $args['body'] : '';
$fs_image_id  = isset( $args['image_id'] ) ? absint( $args['image_id'] ) : 0;
$fs_image_url = ! $fs_image_id && isset( $args['image_url'] ) ? $args['image_url'] : '';
$fs_image_alt = isset( $args['image_alt'] ) ? $args['image_alt'] : '';
$fs_position  = isset( $args['image_position'] ) && 'left' === $args['image_position'] ? 'left' : 'right';
$fs_cta_label = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url   = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_heading ) && '' === trim( (string) $fs_body ) && ! $fs_image_id && '' === trim( (string) $fs_image_url ) ) {
	return;
}
?>
<div class="fs-content-split fs-content-split--image-<?php echo esc_attr( $fs_position ); ?>">
	<div class="fs-container fs-container--wide fs-content-split__inner">
		<div class="fs-content-split__content">
			<?php if ( $fs_heading ) : ?>
				<h2 class="fs-content-split__heading"><?php echo esc_html( $fs_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $fs_body ) : ?>
				<div class="fs-content-split__body"><?php echo wp_kses_post( $fs_body ); ?></div>
			<?php endif; ?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta_label,
						'url'   => $fs_cta_url,
					)
				);
				?>
			<?php endif; ?>
		</div>
		<?php if ( $fs_image_id ) : ?>
			<div class="fs-content-split__media">
				<?php echo wp_get_attachment_image( $fs_image_id, 'large', false, array( 'class' => 'fs-content-split__image' ) ); ?>
			</div>
		<?php elseif ( $fs_image_url ) : ?>
			<div class="fs-content-split__media">
				<img class="fs-content-split__image" src="<?php echo esc_url( $fs_image_url ); ?>" alt="<?php echo esc_attr( $fs_image_alt ); ?>" loading="lazy" />
			</div>
		<?php endif; ?>
	</div>
</div>
