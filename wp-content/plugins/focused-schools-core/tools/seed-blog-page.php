<?php
/**
 * Create the Blog page and assign it as the Posts page.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seed-blog-page.php
 *
 * This is deliberately separate from `wp focused-schools seed-site`, which
 * promises never to touch Settings > Reading so that an environment with an
 * existing Blog Posts Page cannot have it moved out from under it. Assigning
 * the Posts page is exactly that kind of change, so it lives here and is run
 * on purpose rather than as part of a general provisioning pass.
 *
 * What it does:
 *
 * - reuses a page already at /blog/ rather than creating a second one;
 * - creates one only when none exists;
 * - assigns `page_for_posts` only when nothing is assigned yet.
 *
 * What it will not do: reassign `page_for_posts` when it already points
 * somewhere. That would move the blog archive to a different URL and orphan
 * whatever the old page was, so it reports the conflict and stops instead.
 *
 * Nothing else is touched: no post, no permalink structure, no front page.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_slug = 'blog';

$fs_lead = __(
	'Ideas and inspiration from real leaders dedicated to enhancing leadership skills and empowering teams.',
	'focused-schools-core'
);

$fs_existing = get_page_by_path( $fs_slug );

if ( $fs_existing ) {
	$fs_page_id = (int) $fs_existing->ID;
	WP_CLI::log( sprintf( '  page: reused /%1$s/ (ID %2$d)', $fs_slug, $fs_page_id ) );
} else {
	$fs_page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __( 'Blog', 'focused-schools-core' ),
			'post_name'    => $fs_slug,
			'post_content' => $fs_lead,
		),
		true
	);

	if ( is_wp_error( $fs_page_id ) ) {
		WP_CLI::error( $fs_page_id->get_error_message() );
	}

	WP_CLI::log( sprintf( '  page: created /%1$s/ (ID %2$d)', $fs_slug, $fs_page_id ) );
}

$fs_assigned = (int) get_option( 'page_for_posts' );

if ( 0 === $fs_assigned ) {
	update_option( 'page_for_posts', $fs_page_id );
	WP_CLI::log( sprintf( '  posts page: assigned to ID %d', $fs_page_id ) );
} elseif ( $fs_assigned === $fs_page_id ) {
	WP_CLI::log( '  posts page: already assigned to this page' );
} else {
	$fs_other = get_post( $fs_assigned );

	WP_CLI::warning(
		sprintf(
			'Posts page is already set to ID %1$d (%2$s) — left alone. Reassigning it would move the blog archive URL, so change it in Settings > Reading only if that is what you intend.',
			$fs_assigned,
			$fs_other ? $fs_other->post_title : __( 'missing page', 'focused-schools-core' )
		)
	);
}

// page_for_posts only takes effect when the front page is a static page.
if ( 'page' !== get_option( 'show_on_front' ) ) {
	WP_CLI::warning(
		'Settings > Reading is set to show latest posts on the front page, so the Posts page assignment has no effect until a static front page is chosen.'
	);
}

WP_CLI::success( 'Blog page ready.' );
WP_CLI::log( sprintf( '  archive URL : %s', get_permalink( $fs_page_id ) ) );
WP_CLI::log( sprintf( '  permalinks  : %s', get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'plain — posts need /%postname%/' ) );
WP_CLI::log( sprintf( '  published posts: %d', (int) wp_count_posts( 'post' )->publish ) );
