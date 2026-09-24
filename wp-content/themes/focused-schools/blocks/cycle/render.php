<?php
/**
 * Server render for focused-schools/cycle.
 *
 * Delegates to the same component the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_phases = array();

for ( $fs_i = 1; $fs_i <= 3; $fs_i++ ) {
	$fs_label = isset( $attributes[ 'phase' . $fs_i ] ) ? $attributes[ 'phase' . $fs_i ] : '';

	if ( '' !== trim( (string) $fs_label ) ) {
		$fs_phases[] = array( 'label' => $fs_label );
	}
}

get_template_part(
	'template-parts/components/cycle-of-excellence',
	null,
	array(
		'eyebrow'  => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'heading'  => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'body'     => isset( $attributes['body'] ) ? $attributes['body'] : '',
		'mark_url' => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-cycle.svg',
		'phases'   => $fs_phases,
	)
);
