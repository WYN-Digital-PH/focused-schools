<?php
/**
 * Server render for focused-schools/proof.
 *
 * Delegates to the same components the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_has_settings = function_exists( 'focused_schools_get_setting' );
?>
<section class="fs-home__section fs-home__proof" aria-labelledby="fs-home-proof-title">
	<div class="fs-container fs-container--shell">
		<h2 class="fs-home__proof-title" id="fs-home-proof-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
		<?php
		get_template_part(
			'template-parts/components/statistics-counter',
			null,
			array(
				'stats' => array(
					array(
						'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_students', 2 ) : 2,
						'suffix' => '+',
						'unit'   => __( 'Million', 'focused-schools' ),
						'label'  => __( 'Students Impacted', 'focused-schools' ),
					),
					array(
						'value'  => $fs_has_settings ? (int) focused_schools_get_setting( 'impact_years', 20 ) : 20,
						'suffix' => '+',
						'unit'   => __( 'Years', 'focused-schools' ),
						'label'  => __( 'Partnering with Schools', 'focused-schools' ),
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
