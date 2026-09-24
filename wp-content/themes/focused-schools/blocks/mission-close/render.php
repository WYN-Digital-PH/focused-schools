<?php
/**
 * Server render for focused-schools/mission-close.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_photo = focused_schools_block_image( $attributes, 'image', '/assets/img/retreat-2.jpg', __( 'Two education leaders celebrating progress together.', 'focused-schools' ) );
$fs_one   = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';
$fs_two   = isset( $attributes['cta2Url'] ) ? (string) $attributes['cta2Url'] : '';

get_template_part(
	'template-parts/components/content-image-split',
	null,
	array(
		'eyebrow'        => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'heading'        => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'body'           => '<p>' . esc_html( isset( $attributes['body'] ) ? $attributes['body'] : '' ) . '</p>',
		'image_url'      => $fs_photo['url'],
		'image_alt'      => $fs_photo['alt'],
		'image_position' => 'right',
		'cta_label'      => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'        => 0 === strpos( $fs_one, '/' ) ? home_url( $fs_one ) : $fs_one,
		'cta2_label'     => isset( $attributes['cta2Label'] ) ? $attributes['cta2Label'] : '',
		'cta2_url'       => 0 === strpos( $fs_two, '/' ) ? home_url( $fs_two ) : $fs_two,
	)
);
