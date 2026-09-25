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
		'page-hero',
		'rail-text',
		'stats-band',
		'partner-districts',
		'where-we-work',
		'service-index',
		'service-lanes',
		'cycle-steps',
		'pull-quote',
		'story-spotlight',
		'story-index',
		'podcast-subscribe',
		'podcast-latest',
		'podcast-listen',
		'podcast-videos',
		'contact-hero',
		'contact-form',
		'contact-reach',
		'team-grid',
		'mission-close',
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

/**
 * Partner records grouped by state, for the blocks that show them.
 *
 * One query serves both views the approved design asks for: the map plots
 * every state we have worked in, while the "Partnering in <year>" list shows
 * only the districts whose partnership is current. Keeping that split in the
 * status meta rather than in two hand-kept lists means adding a district in
 * wp-admin updates both, and they cannot drift apart.
 *
 * @return array[] Each item: name, lat, lng, current[], previous[].
 */
function focused_schools_partner_states() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$cache = array();
	$terms = get_terms(
		array(
			'taxonomy'   => 'fs_partner_state',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return $cache;
	}

	foreach ( $terms as $term ) {
		$posts = get_posts(
			array(
				'post_type'      => 'fs_partner',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- small, admin-managed taxonomy.
					array(
						'taxonomy' => 'fs_partner_state',
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		if ( ! $posts ) {
			continue;
		}

		$current  = array();
		$previous = array();

		foreach ( $posts as $partner ) {
			if ( 'previous' === get_post_meta( $partner->ID, '_fs_partner_status', true ) ) {
				$previous[] = $partner->post_title;
			} else {
				$current[] = $partner->post_title;
			}
		}

		$cache[] = array(
			'name'     => $term->name,
			'lat'      => get_term_meta( $term->term_id, '_fs_partner_state_lat', true ),
			'lng'      => get_term_meta( $term->term_id, '_fs_partner_state_lng', true ),
			'current'  => $current,
			'previous' => $previous,
		);
	}

	return $cache;
}

/**
 * Published services, in the order editors set.
 *
 * Shared by the index and the lanes so the two always agree about which
 * services exist and what order they run in; the lane numerals, grounds,
 * media sides and anchors all derive from that order.
 *
 * @return WP_Post[]
 */
function focused_schools_service_query() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$cache = get_posts(
		array(
			'post_type'      => 'fs_service',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	return $cache;
}

/**
 * Impact stories for the landing page, with its filters applied.
 *
 * Kept whole and in one place because the ordering is not obvious: the
 * fs_impact_story records and the migrated legacy Pages are two sources that
 * have to interleave by year and then by date, rather than clumping by
 * source. Splitting that across blocks would let the two drift apart.
 *
 * Filters come from the URL, so a filtered view stays shareable and
 * back-button safe, and the legacy Pages are only included on the unfiltered
 * view because they carry none of the filter meta.
 *
 * @return array{stories:WP_Post[],featured:?WP_Post,total:int,filtered:bool,state:string,year:string}
 */
function focused_schools_impact_stories() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only public filters, they change no state.
	$state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '';
	$year  = isset( $_GET['story_year'] ) ? sanitize_text_field( wp_unslash( $_GET['story_year'] ) ) : '';
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	$meta_query = array();

	if ( '' !== $state ) {
		$meta_query[] = array(
			'key'   => '_fs_impact_story_state',
			'value' => $state,
		);
	}

	if ( '' !== $year ) {
		$meta_query[] = array(
			'key'   => '_fs_impact_story_year',
			'value' => $year,
		);
	}

	$args = array(
		'post_type'      => 'fs_impact_story',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	);

	if ( $meta_query ) {
		$meta_query['relation'] = 'AND';
		$args['meta_query']     = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- small, admin-managed set.
	}

	$stories  = get_posts( $args );
	$filtered = '' !== $state || '' !== $year;

	if ( ! $filtered ) {
		$stories = array_merge(
			$stories,
			get_posts(
				array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
					'meta_key'       => '_fs_legacy_impact_story', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small, admin-managed set.
					'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- see above.
				)
			)
		);
	}

	usort(
		$stories,
		static function ( $a, $b ) {
			$year_a = (int) get_post_meta( $a->ID, '_fs_impact_story_year', true );
			$year_b = (int) get_post_meta( $b->ID, '_fs_impact_story_year', true );

			if ( $year_a !== $year_b ) {
				return $year_b <=> $year_a;
			}

			return strtotime( $b->post_date ) <=> strtotime( $a->post_date );
		}
	);

	$featured = null;

	foreach ( $stories as $story ) {
		if ( 'fs_impact_story' === $story->post_type && get_post_meta( $story->ID, '_fs_impact_story_featured', true ) ) {
			$featured = $story;
			break;
		}
	}

	$cache = array(
		'stories'  => $stories,
		'featured' => $featured,
		'total'    => count( $stories ),
		'filtered' => $filtered,
		'state'    => $state,
		'year'     => $year,
	);

	return $cache;
}

/**
 * Distinct values of a story meta field, for the filter chips.
 *
 * Lives here rather than in the page template because the Story Index block
 * needs it too, and two copies would be free to disagree.
 *
 * @param string $meta_key Story meta key to collect.
 * @return string[] Sorted, de-duplicated, blanks removed.
 */
function focused_schools_story_filter_terms( $meta_key ) {
	$stories = get_posts(
		array(
			'post_type'      => 'fs_impact_story',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	$values = array();

	foreach ( $stories as $story_id ) {
		$value = trim( (string) get_post_meta( $story_id, $meta_key, true ) );

		if ( '' !== $value ) {
			$values[ $value ] = true;
		}
	}

	$values = array_keys( $values );
	sort( $values );

	return $values;
}
