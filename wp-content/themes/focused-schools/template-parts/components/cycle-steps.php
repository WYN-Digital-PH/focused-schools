<?php
/**
 * Component: Cycle Steps.
 *
 * Contract ($args):
 * - eyebrow  (string)
 * - heading  (string, required)
 * - body     (string)
 * - mark_url (string) watermark drawn behind the band
 * - steps    (array, required) each item: {
 *       kicker (string) e.g. "01 · Focus"
 *       title  (string)
 *       body   (string)
 *   }
 *
 * The Services page's statement of the method. It is the Home page's cycle
 * section with the motion removed, per the page spec: the same teal band and
 * watermark, but the phases are read as four static steps rather than a
 * scroll-scrubbed diagram. Home keeps the animated version
 * (cycle-of-excellence.php); this is deliberately a separate component
 * because the two draw different things, not the same thing twice.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow  = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading  = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body     = isset( $args['body'] ) ? $args['body'] : '';
$fs_mark_url = isset( $args['mark_url'] ) ? $args['mark_url'] : '';
$fs_steps    = isset( $args['steps'] ) && is_array( $args['steps'] ) ? $args['steps'] : array();

if ( '' === trim( (string) $fs_heading ) || empty( $fs_steps ) ) {
	return;
}
?>
<section class="fs-cycle-steps-band" aria-labelledby="fs-cycle-steps-title">
	<?php if ( $fs_mark_url ) : ?>
		<img class="fs-cycle-steps-band__mark" src="<?php echo esc_url( $fs_mark_url ); ?>" alt="" aria-hidden="true" />
	<?php endif; ?>
	<div class="fs-container fs-container--shell">
		<header class="fs-cycle-steps-band__intro">
			<div>
				<?php if ( $fs_eyebrow ) : ?>
					<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( $fs_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="fs-cycle-steps-title"><?php echo esc_html( $fs_heading ); ?></h2>
			</div>
			<?php if ( $fs_body ) : ?>
				<p class="fs-cycle-steps-band__lead"><?php echo esc_html( $fs_body ); ?></p>
			<?php endif; ?>
		</header>

		<div class="fs-cycle-steps">
			<?php foreach ( $fs_steps as $fs_step ) : ?>
				<?php
				$fs_title = isset( $fs_step['title'] ) ? $fs_step['title'] : '';

				if ( '' === trim( (string) $fs_title ) ) {
					continue;
				}
				?>
				<article class="fs-cycle-step">
					<?php if ( ! empty( $fs_step['kicker'] ) ) : ?>
						<span><?php echo esc_html( $fs_step['kicker'] ); ?></span>
					<?php endif; ?>
					<strong><?php echo esc_html( $fs_title ); ?></strong>
					<?php if ( ! empty( $fs_step['body'] ) ) : ?>
						<p><?php echo esc_html( $fs_step['body'] ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
