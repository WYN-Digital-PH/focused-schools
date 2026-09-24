<?php
/**
 * Seed the block content for the Services and Team pages.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seed-page-blocks.php
 *
 * Both pages are block-built: their sections, order and copy live in the
 * page content rather than in a template. A fresh environment therefore has
 * the templates but an empty page, which renders as a bare header and
 * footer. This writes the approved block layout into each page.
 *
 * Safe to re-run, and safe on a page someone has already edited:
 *
 * - a page that already contains Focused Schools blocks is left alone, so an
 *   editor's arrangement is never overwritten;
 * - any other existing content is copied to the `_fs_pre_block_content` meta
 *   before the blocks are written, so nothing is lost;
 * - pages are matched by slug and never created, so Page IDs, slugs and URLs
 *   are untouched.
 *
 * Nothing else is changed: no option, no setting, no record.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_pages = array(
	'services' => <<<'BLOCKS'
<!-- wp:focused-schools/page-hero {"eyebrow":"Our services","heading":"Support shaped around where your district actually is.","subheading":"No two districts face the same challenges, which is why we don't believe in one-size-fits-all support.","ctaLabel":"See How We Help","ctaUrl":"#how-we-help","imageAlt":"District leaders working together in a Focused Schools session."} /-->

<!-- wp:focused-schools/service-index /-->

<!-- wp:focused-schools/service-lanes /-->

<!-- wp:focused-schools/cycle-steps /-->

<!-- wp:focused-schools/pull-quote /-->

<!-- wp:focused-schools/mission-close {"eyebrow":"Start somewhere","heading":"Not sure which lane you need?","body":"Most districts don't, at first. Tell us what is getting in the way and we will tell you honestly whether and how we can help — before anyone writes a proposal.","ctaLabel":"Let's Talk","ctaUrl":"/contact/","cta2Label":"See Impact Stories","cta2Url":"/impact-stories/","imageAlt":"Two education leaders in conversation."} /-->
BLOCKS
	,
	'team'     => <<<'BLOCKS'
<!-- wp:focused-schools/page-hero {"eyebrow":"Meet our team","heading":"We're educators first. That's what makes us different.","subheading":"Before we became consultants, we were principals, teachers, district leaders, and instructional coaches.","ctaLabel":"See the Team","ctaUrl":"#team-grid","imageAlt":"The Focused Schools team working alongside district leaders at a retreat."} /-->

<!-- wp:focused-schools/rail-text {"eyebrow":"Who you'll work with","heading":"We have sat in the seat you are sitting in.","headingMaxCh":15,"body":"We've celebrated student successes, supported educators through difficult seasons, and made the tough decisions that come with leading schools and districts. That experience is why we listen before we lead — and why the people you meet on day one are the people who stay with the work.","ctaLabel":"Explore Our Services","ctaUrl":"/services/"} /-->

<!-- wp:focused-schools/team-grid {"variant":"roster","eyebrow":"The team","heading":"Meet Our Team","headingEmphasis":"","visibleCount":6,"linkLabel":""} /-->

<!-- wp:focused-schools/mission-close {"eyebrow":"Say hello","heading":"Get to Know Us.","body":"Tell us where your district is headed and we will introduce you to the people who would carry the work with you.","ctaLabel":"Let's Talk","ctaUrl":"/contact/","cta2Label":"See Impact Stories","cta2Url":"/impact-stories/","imageAlt":"Two education leaders celebrating progress together."} /-->
BLOCKS
	,
);

$fs_seeded  = 0;
$fs_skipped = 0;

foreach ( $fs_pages as $fs_slug => $fs_blocks ) {
	$fs_page = get_page_by_path( $fs_slug );

	if ( ! $fs_page ) {
		WP_CLI::warning( sprintf( 'No page found at /%s/ - nothing written.', $fs_slug ) );
		continue;
	}

	$fs_existing = trim( (string) $fs_page->post_content );

	if ( false !== strpos( $fs_existing, 'wp:focused-schools/' ) ) {
		WP_CLI::log( sprintf( '  /%1$s/ (ID %2$d) already block-built - left as it is.', $fs_slug, $fs_page->ID ) );
		++$fs_skipped;
		continue;
	}

	// Keep whatever was there, so nothing an editor wrote is thrown away.
	if ( '' !== $fs_existing ) {
		update_post_meta( $fs_page->ID, '_fs_pre_block_content', $fs_existing );
	}

	$fs_result = wp_update_post(
		array(
			'ID'           => $fs_page->ID,
			'post_content' => $fs_blocks,
		),
		true
	);

	if ( is_wp_error( $fs_result ) ) {
		WP_CLI::warning( sprintf( '/%1$s/: %2$s', $fs_slug, $fs_result->get_error_message() ) );
		continue;
	}

	WP_CLI::log(
		sprintf(
			'  /%1$s/ (ID %2$d) seeded%3$s',
			$fs_slug,
			$fs_page->ID,
			'' !== $fs_existing ? ' - previous content saved to _fs_pre_block_content' : ''
		)
	);
	++$fs_seeded;
}

WP_CLI::success( 'Page blocks seeded.' );
WP_CLI::log( sprintf( '  seeded  : %d', $fs_seeded ) );
WP_CLI::log( sprintf( '  skipped : %d (already block-built)', $fs_skipped ) );
WP_CLI::log( '  Page IDs, slugs and URLs were not touched.' );
