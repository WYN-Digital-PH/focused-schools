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
