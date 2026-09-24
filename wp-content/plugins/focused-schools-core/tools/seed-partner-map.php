<?php
/**
 * Populate the Partner Districts directory and its map.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seed-partner-map.php
 *
 * The table below is the approved mockup's own partner data: 25 states and
 * regions, 63 districts, with each state's map coordinates and each district
 * marked current or previous.
 *
 * Additive and idempotent:
 * - a state term or district that already exists is reused, never duplicated;
 * - coordinates are written only when the term has none, so a coordinate an
 *   editor has nudged by hand is never overwritten;
 * - the partnership status is kept in step with the approved data.
 *
 * Nothing else is touched: no page, no option, no setting.
 *
 * @package FocusedSchoolsCore
 */

use FocusedSchoolsCore\Modules\Partners;

defined( 'ABSPATH' ) || exit;

if ( ! taxonomy_exists( Partners::TAXONOMY ) ) {
	WP_CLI::error( 'The Partners module is not active, so there is nothing to populate.' );
}

/**
 * Find an existing district by its exact name.
 *
 * A title lookup is the only handle we have — the district name is the record —
 * and get_page_by_title() is deprecated, so query for it instead.
 *
 * @param string $title District name.
 * @return int Post ID, or 0 when there is no match.
 */
function fs_find_partner_by_title( $title ) {
	$found = get_posts(
		array(
			'post_type'              => Partners::POST_TYPE,
			'post_status'            => array( 'publish', 'draft', 'pending', 'private' ),
			'title'                  => $title,
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		)
	);

	return $found ? (int) $found[0] : 0;
}

$fs_regions = array(
	array(
		'name'     => 'Canada',
		'lat'      => 53.6209059,
		'lng'      => -113.5429892,
		'current'  => array(),
		'previous' => array( 'Edmonton Public Schools' ),
	),
	array(
		'name'     => 'Arizona',
		'lat'      => 33.7186,
		'lng'      => -112.3099,
		'current'  => array(),
		'previous' => array( 'Rowland Unified School District', 'Peoria Unified School District' ),
	),
	array(
		'name'     => 'California',
		'lat'      => 36.778259,
		'lng'      => -119.417931,
		'current'  => array( 'Covina-Valley Unified School District', 'Downey Unified School District', 'San Marino Unified School District' ),
		'previous' => array( 'Alliance College-Ready Public Schools', 'Lowell Joint School District', 'Los Angeles County Office of Education', 'Mammoth Unified School District', 'Monrovia Public Schools', 'Stockton Unified School District', 'Yucaipa-Calimesa Joint USD' ),
	),
	array(
		'name'     => 'Connecticut',
		'lat'      => 41.6032645,
		'lng'      => -73.087864,
		'current'  => array( 'Capitol Region Education Council', 'East Windsor Public Schools' ),
		'previous' => array( 'New Haven Public Schools' ),
	),
	array(
		'name'     => 'Delaware',
		'lat'      => 39.422675,
		'lng'      => -75.740239,
		'current'  => array( 'MOT Charter Schools' ),
		'previous' => array(),
	),
	array(
		'name'     => 'Illinois',
		'lat'      => 40.0796625,
		'lng'      => -89.4337344,
		'current'  => array( 'Champaign Unit 4 School District', 'Springfield Public School District #186' ),
		'previous' => array( 'Sangamon-Menard Regional Office of Education', 'Brown County School District #1', 'Rantoul City Schools SD 137' ),
	),
	array(
		'name'     => 'Indiana',
		'lat'      => 40.273502,
		'lng'      => -86.126976,
		'current'  => array(),
		'previous' => array( 'State of Indiana Department of Education (IDOE)' ),
	),
	array(
		'name'     => 'Kentucky',
		'lat'      => 37.9404,
		'lng'      => -85.6435,
		'current'  => array(),
		'previous' => array( 'Bullitt County Public Schools' ),
	),
	array(
		'name'     => 'Massachusetts',
		'lat'      => 42.407211,
		'lng'      => -71.382439,
		'current'  => array( 'Athol-Royalston Regional School District', 'Blackstone-Millville Regional School District', 'Fitchburg Public Schools', 'Medway Public Schools' ),
		'previous' => array( 'Hampden-Wilbraham Public Schools', 'Ludlow Public School District', 'New Bedford Public Schools', 'Palmer Public School District', 'Somerville Public Schools', 'Southbridge Public Schools', 'Springfield Public Schools', 'Watertown Public Schools', 'Hudson Public Schools', 'Shrewsbury Public Schools', 'Worcester Public Schools', 'Marlborough Public Schools', 'Mohawk Trail & Hawlemont Regional School Districts', 'Natick Public Schools' ),
	),
	array(
		'name'     => 'Mississippi',
		'lat'      => 32.5610555,
		'lng'      => -91.1982,
		'current'  => array(),
		'previous' => array( 'Mississippi Achievement School District' ),
	),
	array(
		'name'     => 'Missouri',
		'lat'      => 39.0825,
		'lng'      => -94.3874,
		'current'  => array(),
		'previous' => array( 'Independence School District', 'Saint Louis Public Schools' ),
	),
	array(
		'name'     => 'New Jersey',
		'lat'      => 40.3231756,
		'lng'      => -74.6406927,
		'current'  => array(),
		'previous' => array( 'Mathematica Policy Research' ),
	),
	array(
		'name'     => 'New York',
		'lat'      => 40.7086765,
		'lng'      => -74.0126734,
		'current'  => array(),
		'previous' => array(),
	),
	array(
		'name'     => 'Ohio',
		'lat'      => 41.3918325,
		'lng'      => -81.6619337,
		'current'  => array(),
		'previous' => array( 'Educational Service Center of Northeast Ohio' ),
	),
	array(
		'name'     => 'Oklahoma',
		'lat'      => 35.0207568,
		'lng'      => -97.2437105,
		'current'  => array(),
		'previous' => array( 'University of Oklahoma', 'Tulsa Public Schools', 'Oklahoma City Public Schools' ),
	),
	array(
		'name'     => 'Oregon',
		'lat'      => 43.8041334,
		'lng'      => -120.5542012,
		'current'  => array(),
		'previous' => array( 'Greater Albany Public Schools' ),
	),
	array(
		'name'     => 'Pennsylvania',
		'lat'      => 40.4446794,
		'lng'      => -79.9801456,
		'current'  => array(),
		'previous' => array( 'Pittsburgh Public Schools' ),
	),
	array(
		'name'     => 'South Dakota',
		'lat'      => 43.5291222,
		'lng'      => -96.7102757,
		'current'  => array(),
		'previous' => array( 'Sioux Falls School District' ),
	),
	array(
		'name'     => 'Tennessee',
		'lat'      => 36.1678393,
		'lng'      => -86.7781606,
		'current'  => array(),
		'previous' => array( 'Metro Nashville Public Schools' ),
	),
	array(
		'name'     => 'Texas',
		'lat'      => 33.5801105,
		'lng'      => -101.8866668,
		'current'  => array(),
		'previous' => array( 'Region 17 ESC' ),
	),
	array(
		'name'     => 'Vermont',
		'lat'      => 43.957199,
		'lng'      => -72.7166041,
		'current'  => array( 'Windham Northeast Supervisory Union' ),
		'previous' => array(),
	),
	array(
		'name'     => 'Virginia',
		'lat'      => 36.850769,
		'lng'      => -76.285873,
		'current'  => array(),
		'previous' => array( 'Norfolk Public Schools' ),
	),
	array(
		'name'     => 'Washington',
		'lat'      => 47.6062095,
		'lng'      => -122.3320708,
		'current'  => array(),
		'previous' => array( 'Seattle Public Schools', 'Spokane Public Schools', 'Yakima School District' ),
	),
	array(
		'name'     => 'West Virginia',
		'lat'      => 38.4192496,
		'lng'      => -82.445154,
		'current'  => array(),
		'previous' => array( 'Cabell County Public Schools' ),
	),
	array(
		'name'     => 'Wisconsin',
		'lat'      => 42.5846772,
		'lng'      => -87.8212264,
		'current'  => array(),
		'previous' => array( 'Kenosha Unified School District', 'Sheboygan Area School District' ),
	),
);

$fs_made_terms = 0;
$fs_made_posts = 0;
$fs_coords     = 0;
$fs_statuses   = 0;

foreach ( $fs_regions as $fs_region ) {
	$fs_term = get_term_by( 'name', $fs_region['name'], Partners::TAXONOMY );

	if ( ! $fs_term ) {
		$fs_new = wp_insert_term( $fs_region['name'], Partners::TAXONOMY );

		if ( is_wp_error( $fs_new ) ) {
			WP_CLI::warning( sprintf( '%1$s: %2$s', $fs_region['name'], $fs_new->get_error_message() ) );
			continue;
		}

		$fs_term = get_term( $fs_new['term_id'], Partners::TAXONOMY );
		++$fs_made_terms;
	}

	// Only fill empty coordinates, so a hand-adjusted pin survives a re-run.
	if ( '' === (string) get_term_meta( $fs_term->term_id, Partners::LAT_META, true ) ) {
		update_term_meta( $fs_term->term_id, Partners::LAT_META, $fs_region['lat'] );
		update_term_meta( $fs_term->term_id, Partners::LNG_META, $fs_region['lng'] );
		++$fs_coords;
	}

	$fs_districts = array(
		'current'  => $fs_region['current'],
		'previous' => $fs_region['previous'],
	);

	$fs_order = 0;

	foreach ( $fs_districts as $fs_status => $fs_names ) {
		foreach ( $fs_names as $fs_name ) {
			++$fs_order;
			$fs_id = fs_find_partner_by_title( $fs_name );

			if ( ! $fs_id ) {
				$fs_id = wp_insert_post(
					array(
						'post_type'   => Partners::POST_TYPE,
						'post_status' => 'publish',
						'post_title'  => $fs_name,
						'menu_order'  => $fs_order,
					),
					true
				);

				if ( is_wp_error( $fs_id ) ) {
					WP_CLI::warning( sprintf( '%1$s: %2$s', $fs_name, $fs_id->get_error_message() ) );
					continue;
				}

				++$fs_made_posts;
			}

			wp_set_object_terms( $fs_id, array( (int) $fs_term->term_id ), Partners::TAXONOMY );

			if ( get_post_meta( $fs_id, Partners::STATUS_META, true ) !== $fs_status ) {
				update_post_meta( $fs_id, Partners::STATUS_META, $fs_status );
				++$fs_statuses;
			}
		}
	}
}

WP_CLI::success( 'Partner directory populated.' );
WP_CLI::log( sprintf( '  states created      : %d', $fs_made_terms ) );
WP_CLI::log( sprintf( '  districts created   : %d', $fs_made_posts ) );
WP_CLI::log( sprintf( '  coordinates written : %d', $fs_coords ) );
WP_CLI::log( sprintf( '  statuses set        : %d', $fs_statuses ) );
WP_CLI::log( '  Re-running is safe: existing records are reused and hand-set coordinates are left alone.' );
