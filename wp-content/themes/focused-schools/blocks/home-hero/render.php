<?php
/**
 * Server render for focused-schools/home-hero.
 *
 * Delegates to the same component the hardcoded template used, so block and
 * template output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_img = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';

get_template_part(
	'template-parts/components/home-hero',
	null,
	array(
		'heading'    => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'subheading' => isset( $attributes['subheading'] ) ? $attributes['subheading'] : '',
		'poster_url' => ! empty( $attributes['posterUrl'] ) ? $attributes['posterUrl'] : $fs_img . 'student-video.jpg',
		'youtube_id' => isset( $attributes['youtubeId'] ) ? $attributes['youtubeId'] : '',
		'cta_label'  => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'    => ! empty( $attributes['ctaUrl'] ) ? $attributes['ctaUrl'] : '#why',
	)
);
