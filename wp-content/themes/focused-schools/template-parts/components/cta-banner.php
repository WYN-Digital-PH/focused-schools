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
 * - image_url   (string) switches the component to the split treatment the
 *                Services and Team pages close with: copy left, photograph
 *                right, on the page ground rather than a coloured band
 * - image_alt   (string) required when image_url is set
 *
 * A closing call-to-action section (e.g. "Ready to get started?"), distinct
 * from the single-link button.php component it composes internally. Two
 * treatments: the default highlighted band, and the split described above.
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

$fs_image_url = isset( $args['image_url'] ) ? trim( (string) $args['image_url'] ) : '';
$fs_image_alt = isset( $args['image_alt'] ) ? (string) $args['image_alt'] : '';
$fs_is_split  = '' !== $fs_image_url;

if ( '' === trim( (string) $fs_heading ) || '' === trim( (string) $fs_cta_label ) || '' === trim( (string) $fs_cta_url ) ) {
	return;
}

// The band reverses out of a coloured ground, so its buttons step down one
// level; the split sits on the page ground and takes the usual pair.
$fs_cta_style  = $fs_is_split ? 'primary' : 'secondary';
$fs_cta2_style = $fs_is_split ? 'secondary' : 'text';
?>
<section class="fs-cta-banner<?php echo $fs_is_split ? ' fs-cta-banner--split' : ''; ?>">
	<?php
	/*
	 * The split runs the full shell, like every other section on the pages
	 * that use it — at the narrow default its 460px figure leaves the copy
	 * column a sliver, and the section reads as indented against the ones
	 * above it. The centred band keeps the narrow measure on purpose.
	 */
	?>
	<div class="fs-container<?php echo $fs_is_split ? ' fs-container--shell' : ''; ?> fs-cta-banner__inner">
		<div class="fs-cta-banner__copy">
			<?php if ( $fs_eyebrow ) : ?>
				<p class="fs-eyebrow<?php echo $fs_is_split ? '' : ' fs-eyebrow--on-dark'; ?> fs-cta-banner__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
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
						'style' => $fs_cta_style,
					)
				);

				if ( '' !== trim( (string) $fs_cta2_label ) && '' !== trim( (string) $fs_cta2_url ) ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => $fs_cta2_label,
							'url'   => $fs_cta2_url,
							'style' => $fs_cta2_style,
						)
					);
				}
				?>
			</div>
		</div>
		<?php if ( $fs_is_split ) : ?>
			<figure class="fs-cta-banner__figure">
				<img
					src="<?php echo esc_url( $fs_image_url ); ?>"
					alt="<?php echo esc_attr( $fs_image_alt ); ?>"
					width="1000"
					height="1250"
					loading="lazy"
				/>
			</figure>
		<?php endif; ?>
	</div>
</section>
