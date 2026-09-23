<?php
/**
 * Component: Cycle Teaser (decorative teacher/mission card).
 *
 * Contract ($args):
 * - heading      (string, required)
 * - body         (string)
 * - image_url    (string) theme-static portrait image URL
 * - image_alt    (string)
 * - watermark_url (string) theme-static large mark image, shown faint bottom-left
 * - shape_urls   (array) up to 3 theme-static decorative shape image URLs;
 *   purely decorative, hover-choreographed via CSS only (no JS)
 *
 * Precedes cycle-of-excellence.php on the Home page — see that component's
 * docblock and docs/page-specs/home.md for why these are two sections, not
 * one. Generic/args-driven, no CPT.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body       = isset( $args['body'] ) ? $args['body'] : '';
$fs_image_url  = isset( $args['image_url'] ) ? $args['image_url'] : '';
$fs_image_alt  = isset( $args['image_alt'] ) ? $args['image_alt'] : '';
$fs_watermark  = isset( $args['watermark_url'] ) ? $args['watermark_url'] : '';
$fs_shape_urls = isset( $args['shape_urls'] ) && is_array( $args['shape_urls'] ) ? array_values( $args['shape_urls'] ) : array();

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<section class="fs-cycle-teaser">
	<div class="fs-container fs-container--shell">
		<article class="fs-cycle-teaser__card" data-fs-teacher-card>
			<?php if ( $fs_watermark ) : ?>
				<span class="fs-cycle-teaser__watermark" aria-hidden="true">
					<img src="<?php echo esc_url( $fs_watermark ); ?>" alt="" />
				</span>
			<?php endif; ?>
			<div class="fs-cycle-teaser__copy">
				<h2><?php echo esc_html( $fs_heading ); ?></h2>
				<?php if ( $fs_body ) : ?>
					<p><?php echo esc_html( $fs_body ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $fs_image_url || $fs_shape_urls ) : ?>
				<div class="fs-cycle-teaser__scene" aria-hidden="true">
					<?php foreach ( array_slice( $fs_shape_urls, 0, 3 ) as $fs_shape_index => $fs_shape_url ) : ?>
						<img class="fs-cycle-teaser__shape fs-cycle-teaser__shape--<?php echo esc_attr( chr( 97 + $fs_shape_index ) ); ?>" src="<?php echo esc_url( $fs_shape_url ); ?>" alt="" />
					<?php endforeach; ?>
					<?php if ( $fs_image_url ) : ?>
						<div class="fs-cycle-teaser__person">
							<?php
							/*
							 * Not lazy-loaded. At desktop the portrait is shown
							 * at its natural aspect with no box around it, so
							 * before it loads it has no height — and a
							 * zero-height lazy image never trips the loader,
							 * leaving the portrait permanently blank.
							 */
							?>
							<img src="<?php echo esc_url( $fs_image_url ); ?>" alt="<?php echo esc_attr( $fs_image_alt ); ?>" decoding="async" />
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	</div>
</section>
