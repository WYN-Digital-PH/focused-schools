<?php
/**
 * Server render for focused-schools/stats-band.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_has_settings = function_exists( 'focused_schools_get_setting' );
$fs_url          = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';
$fs_img          = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
?>
<section class="fs-about__stats-band" aria-labelledby="fs-about-stats-title">
	<img class="fs-about__stats-watermark" src="<?php echo esc_url( $fs_img . 'mark-white.svg' ); ?>" alt="" aria-hidden="true" />
	<div class="fs-container fs-container--shell fs-about__stats-head">
		<div>
			<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
			<h2 id="fs-about-stats-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
		</div>
		<?php
		if ( ! empty( $attributes['ctaLabel'] ) ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $attributes['ctaLabel'],
					'url'   => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
					'style' => 'white',
				)
			);
		}
		?>
	</div>
	<div class="fs-container fs-container--shell">
		<?php
		get_template_part(
			'template-parts/components/statistics-counter',
			null,
			array(
				'on_teal' => true,
				'stats'   => array(
					array(
						'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_students', 2 ) : 2,
						'suffix' => '+',
						'unit'   => __( 'Million', 'focused-schools' ),
						'label'  => __( 'Students impacted', 'focused-schools' ),
					),
					array(
						'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_years', 20 ) : 20,
						'suffix' => '+',
						'unit'   => __( 'Years', 'focused-schools' ),
						'label'  => __( 'Partnering with schools', 'focused-schools' ),
					),
					array(
						'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_states', 25 ) : 25,
						'suffix' => '+',
						'unit'   => __( 'States', 'focused-schools' ),
						'label'  => __( 'Served', 'focused-schools' ),
					),
				),
			)
		);
		?>
	</div>
</section>
