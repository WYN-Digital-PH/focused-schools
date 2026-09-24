<?php
/**
 * Server render for focused-schools/rail-text.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_body = isset( $attributes['body'] ) ? trim( (string) $attributes['body'] ) : '';
$fs_url  = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';

get_template_part(
	'template-parts/components/rail-text',
	null,
	array(
		'eyebrow'        => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'icon_url'       => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-1.svg',
		'heading'        => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
		'heading_max_ch' => isset( $attributes['headingMaxCh'] ) ? (int) $attributes['headingMaxCh'] : 15,
		'body'           => '' !== $fs_body ? preg_split( '/\r\n\r\n|\n\n/', $fs_body ) : array(),
		'emphasis'       => isset( $attributes['emphasis'] ) ? $attributes['emphasis'] : '',
		'cta_label'      => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'        => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
	)
);
