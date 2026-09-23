<?php
/**
 * Server render for focused-schools/commitments.
 *
 * Delegates to the same component the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_a     = $attributes;
$fs_items = array();

for ( $fs_i = 1; $fs_i <= 3; $fs_i++ ) {
	$fs_heading = isset( $fs_a[ 'item' . $fs_i . 'Heading' ] ) ? $fs_a[ 'item' . $fs_i . 'Heading' ] : '';

	if ( '' === trim( (string) $fs_heading ) ) {
		continue;
	}

	$fs_url = isset( $fs_a[ 'item' . $fs_i . 'CtaUrl' ] ) ? (string) $fs_a[ 'item' . $fs_i . 'CtaUrl' ] : '';

	$fs_items[] = array(
		'heading'   => $fs_heading,
		'body'      => isset( $fs_a[ 'item' . $fs_i . 'Body' ] ) ? $fs_a[ 'item' . $fs_i . 'Body' ] : '',
		'cta_label' => isset( $fs_a[ 'item' . $fs_i . 'CtaLabel' ] ) ? $fs_a[ 'item' . $fs_i . 'CtaLabel' ] : '',
		// A leading slash means "a page on this site"; anything else is used as given.
		'cta_url'   => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
	);
}

get_template_part(
	'template-parts/components/commitment-list',
	null,
	array(
		'eyebrow'              => isset( $fs_a['eyebrow'] ) ? $fs_a['eyebrow'] : '',
		'heading'              => sprintf(
			/* translators: %s: emphasised tail of the heading. */
			__( '%1$s %2$s', 'focused-schools' ),
			isset( $fs_a['heading'] ) ? $fs_a['heading'] : '',
			'<strong>' . esc_html( isset( $fs_a['headingEmphasis'] ) ? $fs_a['headingEmphasis'] : '' ) . '</strong>'
		),
		'intro'                => isset( $fs_a['intro'] ) ? $fs_a['intro'] : '',
		'image_url'            => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/retreat-1.jpg',
		'image_alt'            => __( 'District and school leaders working together during a Focused Schools leadership retreat.', 'focused-schools' ),
		'image_caption_kicker' => isset( $fs_a['captionKicker'] ) ? $fs_a['captionKicker'] : '',
		'image_caption_text'   => isset( $fs_a['captionText'] ) ? $fs_a['captionText'] : '',
		'items'                => $fs_items,
	)
);
