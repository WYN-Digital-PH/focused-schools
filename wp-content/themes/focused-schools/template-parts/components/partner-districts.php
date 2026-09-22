<?php
/**
 * Component: Partner Districts.
 *
 * Contract ($args):
 * - eyebrow      (string)
 * - heading      (string, required, allows basic HTML — run through wp_kses_post())
 * - intro        (string)
 * - states       (array, required) each item: {
 *       name       (string, required)
 *       districts  (string[], required)
 *   }
 * - badge_url    (string) theme-static badge image URL
 * - badge_alt    (string)
 * - note         (string, allows basic HTML — run through wp_kses_post())
 * - closing_text (string)
 * - cta_label    (string)
 * - cta_url      (string)
 *
 * Args-driven rendering; the `states` array is built by the calling page
 * template from a real WP_Query against the `fs_partner` CPT + its
 * `fs_partner_state` taxonomy (see page-about-our-mission-vision.php and
 * FocusedSchoolsCore\Modules\Partners) — "adding a sixth state adds a row,
 * no template change" per the approved .dc design's own spec. Kept
 * args-driven (not a query inside this component) for the same reason
 * service-list.php/testimonial-carousel.php are: the page template owns the
 * query, this component only renders.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading      = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_intro        = isset( $args['intro'] ) ? $args['intro'] : '';
$fs_states       = isset( $args['states'] ) && is_array( $args['states'] ) ? $args['states'] : array();
$fs_badge_url    = isset( $args['badge_url'] ) ? $args['badge_url'] : '';
$fs_badge_alt    = isset( $args['badge_alt'] ) ? $args['badge_alt'] : '';
$fs_note         = isset( $args['note'] ) ? $args['note'] : '';
$fs_closing_text = isset( $args['closing_text'] ) ? $args['closing_text'] : '';
$fs_cta_label    = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url      = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_heading ) || empty( $fs_states ) ) {
	return;
}
?>
<section class="fs-partner-districts" aria-labelledby="fs-partner-districts-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-partner-districts__intro">
			<div>
				<?php if ( $fs_eyebrow ) : ?>
					<p class="fs-eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="fs-partner-districts-title"><?php echo wp_kses_post( $fs_heading ); ?></h2>
			</div>
			<?php if ( $fs_intro ) : ?>
				<p class="fs-partner-districts__lead"><?php echo esc_html( $fs_intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="fs-partner-districts__states">
			<?php
			$fs_index = 0;
			foreach ( $fs_states as $fs_state ) :
				$fs_name      = isset( $fs_state['name'] ) ? $fs_state['name'] : '';
				$fs_districts = isset( $fs_state['districts'] ) && is_array( $fs_state['districts'] ) ? $fs_state['districts'] : array();

				if ( '' === trim( (string) $fs_name ) || empty( $fs_districts ) ) {
					continue;
				}
				++$fs_index;
				?>
				<div class="fs-partner-districts__row">
					<div class="fs-partner-districts__row-label">
						<span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
						<h3><?php echo esc_html( $fs_name ); ?></h3>
					</div>
					<ul class="fs-chips">
						<?php foreach ( $fs_districts as $fs_district ) : ?>
							<li><span aria-hidden="true"></span><?php echo esc_html( $fs_district ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $fs_badge_url || $fs_note || ( $fs_closing_text && $fs_cta_label && $fs_cta_url ) ) : ?>
			<div class="fs-partner-districts__foot">
				<?php if ( $fs_badge_url || $fs_note ) : ?>
					<div class="fs-partner-districts__badge">
						<?php if ( $fs_badge_url ) : ?>
							<img src="<?php echo esc_url( $fs_badge_url ); ?>" alt="<?php echo esc_attr( $fs_badge_alt ); ?>" loading="lazy" />
						<?php endif; ?>
						<?php if ( $fs_note ) : ?>
							<p class="fs-small"><?php echo wp_kses_post( $fs_note ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( $fs_closing_text && $fs_cta_label && $fs_cta_url ) : ?>
					<div class="fs-partner-districts__cta">
						<p><?php echo esc_html( $fs_closing_text ); ?></p>
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
		<?php endif; ?>
	</div>
</section>
