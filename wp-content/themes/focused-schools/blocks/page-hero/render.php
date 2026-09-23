<?php
/**
 * Server render for focused-schools/page-hero.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_photo = focused_schools_block_image( $attributes, 'image', '/assets/img/retreat-1.jpg', __( 'Educators and district leaders working together in a Focused Schools session.', 'focused-schools' ) );
$fs_url   = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';

get_template_part(
	'template-parts/components/hero',
	null,
	array(
		'eyebrow'    => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'heading'    => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'subheading' => isset( $attributes['subheading'] ) ? $attributes['subheading'] : '',
		'image_url'  => $fs_photo['url'],
		'image_alt'  => $fs_photo['alt'],
		'cta_label'  => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'    => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
	)
);
