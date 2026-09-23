<?php
/**
 * Homepage block registration.
 *
 * Each homepage section is a **dynamic** block: its attributes hold the
 * editable copy, and its render callback (blocks/<name>/render.php) hands
 * those attributes to the same `template-parts/components/*` the hardcoded
 * template already used. The approved markup, geometry and behaviour are
 * therefore identical whether a section is rendered from a block or from the
 * template — there is one implementation of each component, not two.
 *
 * Why dynamic rather than static/core blocks: the homepage sections are not
 * expressible as core blocks (an ambient-video hero, a scroll-scrubbed cycle
 * diagram). Core blocks and patterns genuinely cannot meet the requirement,
 * which is the bar docs/AGENTS.md sets before building custom blocks. Keeping
 * them server-rendered also means an editor's saved content is just a short
 * attribute record: changing the design later updates every page at once,
 * with no block-invalidation or re-save needed.
 *
 * No build step: the editor script is plain ES5 using wp.element.createElement
 * and wp.serverSideRender, so there is no JSX, bundler or node_modules — per
 * the project's "no new build tools without discussion" rule.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block directory names, relative to the theme's blocks/ folder.
 *
 * @return string[]
 */
function focused_schools_block_names() {
	return array(
		'home-hero',
		'beliefs',
		'commitments',
		'cycle-teaser',
		'cycle',
		'services',
		'impact-stories',
		'proof',
		'contact',
	);
}

/**
 * Register the shared editor script, then every block.
 *
 * Each block.json points its `editorScript` at this one registered handle, so
 * the dependencies (including wp-server-side-render) are declared once and
 * correctly, which a bare `file:` reference could not do without a build step.
 *
 * @return void
 */
function focused_schools_register_blocks() {
	wp_register_script(
		'focused-schools-blocks-editor',
		FOCUSED_SCHOOLS_THEME_URI . '/assets/js/blocks-editor.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-server-side-render',
			'wp-i18n',
		),
		focused_schools_asset_version( '/assets/js/blocks-editor.js' ),
		true
	);

	foreach ( focused_schools_block_names() as $fs_block ) {
		$fs_path = FOCUSED_SCHOOLS_THEME_DIR . '/blocks/' . $fs_block;

		if ( is_readable( $fs_path . '/block.json' ) ) {
			register_block_type( $fs_path );
		}
	}
}
add_action( 'init', 'focused_schools_register_blocks' );

/**
 * Resolve an editable image slot on a block.
 *
 * Precedence: a picked media library item, then a pasted URL, then the
 * theme-bundled default. Alt text follows the same idea — the block's own alt
 * wins, then the attachment's, then the supplied default — so a site that has
 * never opened the editor still renders correct, described images.
 *
 * @param array  $attributes   Block attributes.
 * @param string $prefix       Attribute prefix, e.g. 'image' for imageId/imageUrl/imageAlt.
 * @param string $default_file Theme-relative fallback, e.g. '/assets/img/retreat-1.jpg'.
 * @param string $default_alt  Fallback alt text.
 * @return array{url:string,alt:string}
 */
function focused_schools_block_image( $attributes, $prefix, $default_file, $default_alt = '' ) {
	$id  = isset( $attributes[ $prefix . 'Id' ] ) ? (int) $attributes[ $prefix . 'Id' ] : 0;
	$url = isset( $attributes[ $prefix . 'Url' ] ) ? trim( (string) $attributes[ $prefix . 'Url' ] ) : '';
	$alt = isset( $attributes[ $prefix . 'Alt' ] ) ? trim( (string) $attributes[ $prefix . 'Alt' ] ) : '';
	if ( $id ) {
		$from_library = wp_get_attachment_image_url( $id, 'full' );
		if ( $from_library ) {
			$url = $from_library;
			if ( '' === $alt ) {
				$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
			}
		}
	}

	if ( '' === $url ) {
		$url = FOCUSED_SCHOOLS_THEME_URI . $default_file;
	}

	return array(
		'url' => $url,
		'alt' => '' !== $alt ? $alt : $default_alt,
	);
}
