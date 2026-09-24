<?php
/**
 * Populate the homepage testimonial carousel.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seed-testimonials.php
 *
 * The quotes below are the approved design's own, attributed as it attributes
 * them — by role, not by name. The homepage carousel reads the fs_testimonial
 * records directly, so with fewer than two of them it renders a single quote
 * and no controls, which is why an unseeded site looks nothing like the comp.
 *
 * Idempotent: a testimonial that already exists is updated in place, matched
 * on its slug, so re-running never adds a duplicate. It touches nothing else.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! post_type_exists( 'fs_testimonial' ) ) {
	WP_CLI::error( 'The Testimonials module is not active, so there is nothing to populate.' );
}

$fs_quotes = array(
	array(
		'slug'  => 'assistant-superintendent-finance-facilities',
		'role'  => 'Assistant Superintendent of Finance and Facilities',
		'quote' => 'I would be glad to recommend Focused Schools as an individual who has worked with them as both a high school principal and District Administrator.',
	),
	array(
		'slug'  => 'school-committee-member',
		'role'  => 'School Committee Member',
		'quote' => 'I appreciate the data-driven approach that we took to arrive at this [Strategic] plan. I also appreciate the engagement that occurred with the community…',
	),
	array(
		'slug'  => 'superintendent',
		'role'  => 'Superintendent',
		'quote' => 'We were pleased to collaborate with Focused Schools who assisted us with this process. It was, I thought, a very thorough process that did engage hundreds of our stakeholders in different ways…',
	),
);

$fs_created = 0;
$fs_updated = 0;
$fs_order   = 0;

foreach ( $fs_quotes as $fs_quote ) {
	++$fs_order;

	$fs_existing = get_page_by_path( $fs_quote['slug'], OBJECT, 'fs_testimonial' );

	// post_title is the attribution and post_content the quote, which is the
	// shape template-parts/components/testimonial-carousel.php renders.
	$fs_postarr = array(
		'post_type'    => 'fs_testimonial',
		'post_status'  => 'publish',
		'post_title'   => $fs_quote['role'],
		'post_name'    => $fs_quote['slug'],
		'post_content' => $fs_quote['quote'],
		'menu_order'   => $fs_order,
	);

	if ( $fs_existing ) {
		$fs_postarr['ID'] = $fs_existing->ID;
		$fs_id            = wp_update_post( $fs_postarr, true );
		++$fs_updated;
	} else {
		$fs_id = wp_insert_post( $fs_postarr, true );
		++$fs_created;
	}

	if ( is_wp_error( $fs_id ) ) {
		WP_CLI::warning( sprintf( '%1$s: %2$s', $fs_quote['role'], $fs_id->get_error_message() ) );
	}
}

WP_CLI::success( 'Testimonials populated.' );
WP_CLI::log( sprintf( '  created : %d', $fs_created ) );
WP_CLI::log( sprintf( '  updated : %d', $fs_updated ) );
