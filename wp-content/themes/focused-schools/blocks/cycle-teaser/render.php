<?php
/**
 * Server render for focused-schools/cycle-teaser.
 *
 * Delegates to the same component the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_img    = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
$fs_photo  = focused_schools_block_image( $attributes, 'image', '/assets/img/teacher-portrait.webp' );
$fs_shapes = FOCUSED_SCHOOLS_THEME_URI . '/assets/shapes/';

get_template_part(
	'template-parts/components/cycle-teaser',
	null,
	array(
		'heading'       => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'body'          => isset( $attributes['body'] ) ? $attributes['body'] : '',
		'image_url'     => $fs_photo['url'],
		'image_alt'     => $fs_photo['alt'],
		'watermark_url' => $fs_img . 'mark-white.svg',
		'shape_urls'    => array(
			$fs_shapes . 'star.svg',
			$fs_shapes . 'circle.svg',
			$fs_shapes . 'confetti-1.svg',
		),
	)
);
