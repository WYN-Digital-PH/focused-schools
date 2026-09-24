<?php
/**
 * Component: Hero block.
 *
 * Contract ($args):
 * - heading    (string, required)
 * - eyebrow    (string) small label above the heading (added for the About page task)
 * - subheading (string)
 * - image_id   (int) attachment ID
 * - image_url  (string) static image URL, used only when image_id is not set
 *               (e.g. a theme-bundled placeholder via get_template_directory_uri());
 *               added for the About page task
 * - image_alt  (string) alt text for image_url (image_id already carries its own)
 * - cta_label  (string)
 * - cta_url    (string)
 * - cta2_label (string) optional second, lower-emphasis CTA beside the first
 *               (added for the Podcast page, whose hero has two buttons)
 * - cta2_url   (string) required when cta2_label is set
 * - alignment  (string) 'left'|'center', default 'left'
 * - slab_width (int) desktop (≥1241px) slab width in px, default 680
 *   (Home/About's value) — Team and Services each specify their own
 *   narrower width (660px / 640px) in their `.dc` sources.
 *
 * Always renders in the shell (.fs-container--shell, 1400px) container,
 * matching the approved .dc design's hero treatment (About page task) —
 * the same width as the header/footer, since Hero sits directly below the
 * header.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_eyebrow    = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$fs_image_id   = isset( $args['image_id'] ) ? absint( $args['image_id'] ) : 0;
$fs_image_url  = ! $fs_image_id && isset( $args['image_url'] ) ? $args['image_url'] : '';
$fs_image_alt  = isset( $args['image_alt'] ) ? $args['image_alt'] : '';
$fs_cta_label  = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url    = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
$fs_cta2_label = isset( $args['cta2_label'] ) ? $args['cta2_label'] : '';
$fs_cta2_url   = isset( $args['cta2_url'] ) ? $args['cta2_url'] : '';
$fs_alignment  = isset( $args['alignment'] ) && 'center' === $args['alignment'] ? 'center' : 'left';
$fs_slab_width = isset( $args['slab_width'] ) ? absint( $args['slab_width'] ) : 680;

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<section class="fs-hero fs-hero--align-<?php echo esc_attr( $fs_alignment ); ?>">
	<div class="fs-container fs-container--shell fs-hero__inner">
		<?php if ( $fs_image_id ) : ?>
			<div class="fs-hero__media">
				<?php echo wp_get_attachment_image( $fs_image_id, 'large', false, array( 'class' => 'fs-hero__image' ) ); ?>
			</div>
		<?php elseif ( $fs_image_url ) : ?>
			<div class="fs-hero__media">
				<img class="fs-hero__image" src="<?php echo esc_url( $fs_image_url ); ?>" alt="<?php echo esc_attr( $fs_image_alt ); ?>" />
			</div>
		<?php endif; ?>

		<div class="fs-hero__slab" style="--fs-hero-slab-width: <?php echo esc_attr( $fs_slab_width ); ?>px;">
			<?php if ( $fs_eyebrow ) : ?>
				<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( $fs_eyebrow ); ?></p>
			<?php endif; ?>
			<h1 class="fs-hero__heading"><?php echo esc_html( $fs_heading ); ?></h1>
			<?php if ( $fs_subheading ) : ?>
				<p class="fs-hero__subheading"><?php echo esc_html( $fs_subheading ); ?></p>
			<?php endif; ?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<div class="fs-hero__actions">
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

					if ( $fs_cta2_label && $fs_cta2_url ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => $fs_cta2_label,
								'url'   => $fs_cta2_url,
								'style' => 'white',
							)
						);
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
