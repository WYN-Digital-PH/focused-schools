<?php
/**
 * Component: Cycle of Excellence (3-phase stepper).
 *
 * Contract ($args):
 * - eyebrow   (string)
 * - heading   (string, required)
 * - body      (string)
 * - phases    (array, required) each item: { label (string, required), description (string) }
 * - mark_url  (string) theme-static cycle mark image. It is not a decorative
 *   spinner: the section pins itself for its scroll length and the mark is
 *   *scrubbed* by scroll position — the mark rotates through exactly 360°
 *   across the section, an orbiting dot tracks the same angle at the mark's
 *   radius, and the phase rows step in time with it. Under
 *   prefers-reduced-motion nothing rotates and the rows step alone.
 *
 * The design reference shows this idea twice: a teaser card first
 * (cycle-teaser.php), then this fuller interactive stepper section. Confirmed
 * as intentional (not a duplication artifact) once a second, independent
 * capture of the approved design showed the same two-section structure — see
 * docs/page-specs/home.md.
 *
 * The 3 phase buttons toggle an active phase (assets/js/components/cycle-of-excellence.js)
 * for visual emphasis only — each phase's full label + description is
 * always present in the markup (not hidden), so no-JS and screen-reader
 * users get everything without needing the interaction.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body    = isset( $args['body'] ) ? $args['body'] : '';
$fs_phases  = isset( $args['phases'] ) && is_array( $args['phases'] ) ? $args['phases'] : array();
$fs_mark    = isset( $args['mark_url'] ) ? $args['mark_url'] : '';

if ( '' === trim( (string) $fs_heading ) || empty( $fs_phases ) ) {
	return;
}
?>
<section class="fs-cycle" aria-labelledby="fs-cycle-title" data-fs-cycle-section>
	<div class="fs-cycle__stage">
	<div class="fs-container fs-container--shell fs-cycle__layout">
		<header class="fs-cycle__intro">
			<?php if ( $fs_eyebrow ) : ?>
				<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( $fs_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 id="fs-cycle-title"><?php echo esc_html( $fs_heading ); ?></h2>
			<?php if ( $fs_body ) : ?>
				<p><?php echo esc_html( $fs_body ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( $fs_mark ) : ?>
			<div class="fs-cycle__mark" aria-hidden="true">
				<span class="fs-cycle__mark-disc"></span>
				<img class="fs-cycle__mark-rotor" src="<?php echo esc_url( $fs_mark ); ?>" alt="" data-fs-cycle-rotor />
				<span class="fs-cycle__mark-orbit" data-fs-cycle-orbit></span>
			</div>
		<?php endif; ?>

		<div class="fs-cycle__steps" role="group" aria-label="<?php esc_attr_e( 'Cycle of Excellence phases', 'focused-schools' ); ?>" data-fs-cycle>
			<?php
			$fs_index = 0;
			foreach ( $fs_phases as $fs_phase ) :
				$fs_label       = isset( $fs_phase['label'] ) ? $fs_phase['label'] : '';
				$fs_description = isset( $fs_phase['description'] ) ? $fs_phase['description'] : '';

				if ( '' === trim( (string) $fs_label ) ) {
					continue;
				}
				?>
				<div class="fs-cycle__step<?php echo 0 === $fs_index ? ' is-active' : ''; ?>">
					<button
						type="button"
						class="fs-cycle__step-trigger fs-cycle__step-trigger--<?php echo esc_attr( $fs_index % 3 ); ?>"
						data-fs-cycle-step="<?php echo esc_attr( $fs_index ); ?>"
						<?php echo 0 === $fs_index ? 'aria-current="step"' : ''; ?>
					>
						<span class="fs-cycle__step-dot" aria-hidden="true"></span>
						<span class="fs-cycle__step-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index + 1 ) ); ?></span>
						<strong><?php echo esc_html( $fs_label ); ?></strong>
					</button>
					<?php if ( $fs_description ) : ?>
						<p class="fs-cycle__step-description"><?php echo esc_html( $fs_description ); ?></p>
					<?php endif; ?>
				</div>
				<?php
				++$fs_index;
			endforeach;
			?>
		</div>
	</div>
	</div>
</section>
