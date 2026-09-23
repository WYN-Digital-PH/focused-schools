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

$fs_poster = focused_schools_block_image( $attributes, 'poster', '/assets/img/student-video.jpg' );

get_template_part(
	'template-parts/components/home-hero',
	null,
	array(
		'heading'    => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'subheading' => isset( $attributes['subheading'] ) ? $attributes['subheading'] : '',
		'poster_url' => $fs_poster['url'],
		'youtube_id' => isset( $attributes['youtubeId'] ) ? $attributes['youtubeId'] : '',
		'cta_label'  => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'    => ! empty( $attributes['ctaUrl'] ) ? $attributes['ctaUrl'] : '#why',
	)
);
