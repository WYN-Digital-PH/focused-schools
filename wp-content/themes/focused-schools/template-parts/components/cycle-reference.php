<?php
/**
 * Component: Cycle of Excellence — Static Reference Band.
 *
 * Contract ($args):
 * - eyebrow   (string)
 * - heading   (string, required)
 * - body      (string)
 * - mark_url  (string) theme-static cycle mark icon, centered in a white
 *   circle — purely decorative, no rotation/scroll-scrub (that's
 *   cycle-of-excellence.php's job on Home/About, a deliberately fuller
 *   interactive treatment).
 * - cta_label (string)
 * - cta_url   (string)
 *
 * The `.dc` source's own section label calls this the "static reference" —
 * a teal-band callback to the concept already explained in full elsewhere
 * by cycle-of-excellence.php, not a second interactive stepper. Built for
 * the Services page, since reusing the interactive component there would
 * show phase-stepper UI, breakpoints, and copy the Services `.dc` source
 * doesn't have, while missing the CTA button it does have.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow   = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading   = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body      = isset( $args['body'] ) ? $args['body'] : '';
$fs_mark      = isset( $args['mark_url'] ) ? $args['mark_url'] : '';
$fs_cta_label = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url   = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<section class="fs-cycle-ref">
	<img class="fs-cycle-ref__watermark" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg' ); ?>" alt="" aria-hidden="true" />
	<div class="fs-container fs-container--shell fs-cycle-ref__inner">
		<?php if ( $fs_mark ) : ?>
			<div class="fs-cycle-ref__mark" aria-hidden="true">
				<span class="fs-cycle-ref__disc"></span>
				<img src="<?php echo esc_url( $fs_mark ); ?>" alt="" />
			</div>
		<?php endif; ?>
		<div class="fs-cycle-ref__copy">
			<?php if ( $fs_eyebrow ) : ?>
				<p class="fs-eyebrow fs-cycle-ref__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
			<?php endif; ?>
			<h2><?php echo esc_html( $fs_heading ); ?></h2>
			<?php if ( $fs_body ) : ?>
				<p><?php echo esc_html( $fs_body ); ?></p>
			<?php endif; ?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta_label,
						'url'   => $fs_cta_url,
						'style' => 'white',
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>
