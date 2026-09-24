<?php
/**
 * Form-only page content detection.
 *
 * The Contact and Thank You templates apply the approved theme layout only
 * when the page's own content is nothing but a form: Elementor form/shortcode
 * widgets, or bare shortcodes/<form> markup. Anything else (a full Elementor
 * layout, or copy an editor wrote) is rendered as the page's own content
 * without the template's hero, card or aside wrappers, so an existing layout
 * is never nested inside extra chrome and a form is never touched.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

/**
 * Collect the widget types used in an Elementor element tree.
 *
 * @param array $elements Elementor `elements` array.
 * @param array $types    Accumulator, passed by reference.
 * @return void
 */
function focused_schools_collect_elementor_widget_types( array $elements, array &$types ) {
	foreach ( $elements as $element ) {
		if ( ! is_array( $element ) ) {
			continue;
		}

		if ( isset( $element['elType'] ) && 'widget' === $element['elType'] ) {
			$types[] = isset( $element['widgetType'] ) ? (string) $element['widgetType'] : '';
		}

		if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
			focused_schools_collect_elementor_widget_types( $element['elements'], $types );
		}
	}
}

/**
 * Whether a page's content is only a form (or empty).
 *
 * Elementor-built pages: true when every widget is a `form` or `shortcode`
 * widget (no widgets counts as empty). Other pages: true when, after removing
 * HTML comments, <form> blocks and shortcodes, no text or markup is left.
 * Shortcodes are matched by shape rather than by registered tag, so a form
 * shortcode still counts when its plugin is inactive.
 *
 * @param int $post_id Page ID.
 * @return bool
 */
function focused_schools_is_form_only_content( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return true;
	}

	if ( 'builder' === get_post_meta( $post->ID, '_elementor_edit_mode', true ) ) {
		$data = json_decode( (string) get_post_meta( $post->ID, '_elementor_data', true ), true );

		if ( ! is_array( $data ) ) {
			return true;
		}

		$types = array();
		focused_schools_collect_elementor_widget_types( $data, $types );

		return empty( array_diff( $types, array( 'form', 'shortcode' ) ) );
	}

	$content = (string) $post->post_content;
	$content = preg_replace( '/<!--.*?-->/s', '', $content );
	$content = preg_replace( '/<form\b.*?<\/form>/is', '', $content );
	$content = preg_replace( '/\[\/?[A-Za-z0-9_-]+(?:\s[^\]]*)?\]/', '', $content );

	if ( preg_match( '/<(?:img|iframe|video|audio|figure|picture|svg|table)\b/i', $content ) ) {
		return false;
	}

	return '' === trim( str_replace( '&nbsp;', '', wp_strip_all_tags( $content ) ) );
}
