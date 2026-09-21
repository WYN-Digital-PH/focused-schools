<?php
/**
 * Component: Hero block.
 *
 * Contract ($args):
 * - heading    (string, required)
 * - subheading (string)
 * - image_id   (int) attachment ID
 * - cta_label  (string)
 * - cta_url    (string)
 * - alignment  (string) 'left'|'center', default 'left'
 *
 * Always renders in the wide (.fs-container--wide, 1200px) container — a
 * Hero is inherently a wide/impactful visual section, same as CTA Banner.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$fs_image_id   = isset( $args['image_id'] ) ? absint( $args['image_id'] ) : 0;
$fs_cta_label  = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url    = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
$fs_alignment  = isset( $args['alignment'] ) && 'center' === $args['alignment'] ? 'center' : 'left';

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<section class="fs-hero fs-hero--align-<?php echo esc_attr( $fs_alignment ); ?>">
	<div class="fs-container fs-container--wide fs-hero__inner">
		<div class="fs-hero__content">
			<h1 class="fs-hero__heading"><?php echo esc_html( $fs_heading ); ?></h1>
			<?php if ( $fs_subheading ) : ?>
				<p class="fs-hero__subheading"><?php echo esc_html( $fs_subheading ); ?></p>
			<?php endif; ?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<div class="fs-hero__cta">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => $fs_cta_label,
							'url'   => $fs_cta_url,
							'style' => 'primary',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $fs_image_id ) : ?>
			<div class="fs-hero__media">
				<?php echo wp_get_attachment_image( $fs_image_id, 'large', false, array( 'class' => 'fs-hero__image' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
