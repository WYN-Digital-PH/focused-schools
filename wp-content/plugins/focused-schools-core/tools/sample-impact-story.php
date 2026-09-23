<?php
/**
 * Create a fully populated sample Impact Story, for reviewing the approved
 * design against real content.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/sample-impact-story.php
 *
 * Idempotent: re-running updates the same story rather than adding another. It
 * touches nothing else — no legacy Page, no option, no setting — and the story
 * it creates is marked `_fs_test_fixture` so it is easy to find and remove:
 *
 *     wp post list --post_type=fs_impact_story --meta_key=_fs_test_fixture --meta_value=1 --field=ID
 *
 * This is a review fixture, not seed data. Delete it before launch.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_slug = 'sample-champaign-unit-4-data-days';

$fs_body = <<<'HTML'
<!-- wp:paragraph -->
<p>Champaign Unit 4 came to this work with a familiar problem: plenty of data, and no shared habit of using it. Principals received dashboards nobody had asked for, and teachers experienced data meetings as something done to them rather than with them.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What actually changed</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We started by cutting, not adding. The district retired two reporting rhythms that nobody used, which freed the calendar for one that people would. Teams now open every cycle with student work rather than a spreadsheet, and the protocol fits inside a meeting that already existed.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>I appreciate the data-driven approach that we took to arrive at this plan, and the engagement that occurred with the community.</p><cite>School Committee Member, Champaign Unit 4</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>Three years on, the cycle runs without us in the room. That was the point: the measure of the work is not what we did, it is what kept going after we left.</p>
<!-- /wp:paragraph -->
HTML;

$fs_meta = array(
	'_fs_impact_story_district_or_school' => 'Champaign Unit 4 School District',
	'_fs_impact_story_state'              => 'Illinois',
	'_fs_impact_story_year'               => '2025',
	'_fs_impact_story_featured'           => 1,
	'_fs_impact_story_service_lane'       => 'Strategy and Vision',
	'_fs_impact_story_partnership_length' => 'Three years',
	'_fs_impact_story_results'            => "400+ | Educators | Coached across eight campuses\n18 | Points | Drop in chronic absence\n9 of 9 | Schools | Running the full inquiry cycle",
	'_fs_test_fixture'                    => 1,
);

$fs_existing = get_page_by_path( $fs_slug, OBJECT, 'fs_impact_story' );

$fs_postarr = array(
	'post_type'    => 'fs_impact_story',
	'post_status'  => 'publish',
	'post_title'   => 'A model for community engagement: data days in Champaign Unit 4',
	'post_name'    => $fs_slug,
	'post_excerpt' => 'A district with plenty of data and no shared habit of using it built a cycle its own staff now protect — and kept it running after the partnership ended.',
	'post_content' => $fs_body,
);

if ( $fs_existing ) {
	$fs_postarr['ID'] = $fs_existing->ID;
	$fs_id            = wp_update_post( $fs_postarr, true );
	$fs_action        = 'updated';
} else {
	$fs_id     = wp_insert_post( $fs_postarr, true );
	$fs_action = 'created';
}

if ( is_wp_error( $fs_id ) ) {
	WP_CLI::error( $fs_id->get_error_message() );
}

foreach ( $fs_meta as $fs_key => $fs_value ) {
	update_post_meta( $fs_id, $fs_key, $fs_value );
}

// Use a landscape image already in the media library rather than uploading
// anything: the hero is 21:9 and the card 16:9, so a wide file reads best.
if ( ! has_post_thumbnail( $fs_id ) ) {
	$fs_candidates = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => 25,
			'fields'         => 'ids',
		)
	);

	$fs_best  = 0;
	$fs_ratio = 0;

	foreach ( $fs_candidates as $fs_candidate ) {
		$fs_size = wp_get_attachment_image_src( $fs_candidate, 'full' );

		if ( ! $fs_size || empty( $fs_size[2] ) ) {
			continue;
		}

		$fs_this = $fs_size[1] / $fs_size[2];

		if ( $fs_this > $fs_ratio ) {
			$fs_ratio = $fs_this;
			$fs_best  = $fs_candidate;
		}
	}

	if ( $fs_best ) {
		set_post_thumbnail( $fs_id, $fs_best );
	}
}

WP_CLI::success(
	sprintf(
		'Sample story %s: ID %d — %s',
		$fs_action,
		$fs_id,
		get_permalink( $fs_id )
	)
);

WP_CLI::log( '  featured image: ' . ( has_post_thumbnail( $fs_id ) ? 'set' : 'none found in the media library — set one in wp-admin' ) );
WP_CLI::log( '  meta fields populated: ' . ( count( $fs_meta ) - 1 ) );
