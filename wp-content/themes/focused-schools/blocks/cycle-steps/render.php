<?php
/**
 * Server render for focused-schools/cycle-steps.
 *
 * Delegates to the same component the hardcoded layout used, so block-built
 * and template-built output cannot drift.
 *
 * The four steps are page copy rather than records: they describe the
 * practice itself, not a list that grows. They are attributes so an editor
 * can reword them, and a step with no title is skipped, which is how you
 * drop one.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_steps = array();

for ( $fs_i = 1; $fs_i <= 4; $fs_i++ ) {
	$fs_steps[] = array(
		'kicker' => isset( $attributes[ 'step' . $fs_i . 'Kicker' ] ) ? $attributes[ 'step' . $fs_i . 'Kicker' ] : '',
		'title'  => isset( $attributes[ 'step' . $fs_i . 'Title' ] ) ? $attributes[ 'step' . $fs_i . 'Title' ] : '',
		'body'   => isset( $attributes[ 'step' . $fs_i . 'Body' ] ) ? $attributes[ 'step' . $fs_i . 'Body' ] : '',
	);
}

get_template_part(
	'template-parts/components/cycle-steps',
	null,
	array(
		'eyebrow'  => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'heading'  => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'body'     => isset( $attributes['body'] ) ? $attributes['body'] : '',
		'mark_url' => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg',
		'steps'    => $fs_steps,
	)
);
