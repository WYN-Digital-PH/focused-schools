<?php
/**
 * Component: Commitment List ("How we partner" — numbered commitments + photo).
 *
 * Contract ($args):
 * - eyebrow    (string)
 * - heading    (string, required, allows basic HTML — run through wp_kses_post())
 * - intro      (string)
 * - image_url  (string) static image URL (get_template_directory_uri() . '/assets/img/...')
 * - image_alt  (string)
 * - image_caption_kicker (string) small italic label on the floating caption chip
 * - image_caption_text   (string) bold line on the floating caption chip
 * - heading_max_ch (int) heading's max-width in `ch` units — the Home and
 *   About `.dc` sources specify different values (9ch / 11ch) for this
 *   shared component's heading, default 9 (Home's value, the original
 *   caller).
 * - items      (array, required) each item: {
 *       heading    (string, required)
 *       body       (string)
 *       cta_label  (string)
 *       cta_url    (string)
 *   }
 *
 * Generic/args-driven, no CPT — this is fixed editorial copy, not repeated
 * content that benefits from a post type (matching the Podcast Card /
 * Partner Strip precedent for this kind of section).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow        = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading        = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_intro          = isset( $args['intro'] ) ? $args['intro'] : '';
$fs_image_url      = isset( $args['image_url'] ) ? $args['image_url'] : '';
$fs_image_alt      = isset( $args['image_alt'] ) ? $args['image_alt'] : '';
$fs_kicker         = isset( $args['image_caption_kicker'] ) ? $args['image_caption_kicker'] : '';
$fs_caption        = isset( $args['image_caption_text'] ) ? $args['image_caption_text'] : '';
$fs_items          = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$fs_heading_max_ch = isset( $args['heading_max_ch'] ) ? absint( $args['heading_max_ch'] ) : 9;

if ( '' === trim( (string) $fs_heading ) || empty( $fs_items ) ) {
	return;
}
?>
<section class="fs-commitments" aria-labelledby="fs-commitments-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-commitments__intro">
			<div style="--fs-commitments-heading-max: <?php echo esc_attr( $fs_heading_max_ch ); ?>ch;">
				<?php if ( $fs_eyebrow ) : ?>
					<p class="fs-eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="fs-commitments__heading" id="fs-commitments-title"><?php echo wp_kses_post( $fs_heading ); ?></h2>
			</div>
			<?php if ( $fs_intro ) : ?>
				<p class="fs-commitments__lead"><?php echo esc_html( $fs_intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="fs-commitments__body">
			<?php if ( $fs_image_url ) : ?>
				<figure class="fs-commitments__photo">
					<img src="<?php echo esc_url( $fs_image_url ); ?>" alt="<?php echo esc_attr( $fs_image_alt ); ?>" loading="lazy" />
					<?php if ( $fs_kicker || $fs_caption ) : ?>
						<figcaption class="fs-figure-caption">
							<?php if ( $fs_kicker ) : ?>
								<span class="fs-figure-caption__kicker"><?php echo esc_html( $fs_kicker ); ?></span>
							<?php endif; ?>
							<?php if ( $fs_caption ) : ?>
								<strong><?php echo esc_html( $fs_caption ); ?></strong>
							<?php endif; ?>
						</figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<ol class="fs-commitments__list">
				<?php
				$fs_index = 0;
				foreach ( $fs_items as $fs_item ) :
					++$fs_index;
					$fs_item_heading   = isset( $fs_item['heading'] ) ? $fs_item['heading'] : '';
					$fs_item_body      = isset( $fs_item['body'] ) ? $fs_item['body'] : '';
					$fs_item_cta_label = isset( $fs_item['cta_label'] ) ? $fs_item['cta_label'] : '';
					$fs_item_cta_url   = isset( $fs_item['cta_url'] ) ? $fs_item['cta_url'] : '';

					if ( '' === trim( (string) $fs_item_heading ) ) {
						continue;
					}
					?>
					<li class="fs-commitments__row">
						<span class="fs-commitments__index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
						<div class="fs-commitments__copy">
							<h3><?php echo esc_html( $fs_item_heading ); ?></h3>
							<?php if ( $fs_item_body ) : ?>
								<p><?php echo esc_html( $fs_item_body ); ?></p>
							<?php endif; ?>
							<?php if ( $fs_item_cta_label && $fs_item_cta_url ) : ?>
								<?php
								get_template_part(
									'template-parts/components/button',
									null,
									array(
										'label' => $fs_item_cta_label,
										'url'   => $fs_item_cta_url,
										'style' => 'secondary',
									)
								);
								?>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
