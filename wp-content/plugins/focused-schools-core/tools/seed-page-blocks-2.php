<?php
/**
 * Seed the block content for the Impact Stories, Podcast and Contact pages.
 *
 * Run with WP-CLI from the site root:
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seed-page-blocks-2.php
 *
 * Safe to re-run, and deliberately cautious about what it will overwrite:
 *
 * - a page already holding Focused Schools blocks is left alone;
 * - an Elementor-built page is skipped entirely and reported, because its
 *   template renders Elementor's own output and writing blocks into it would
 *   change content Elementor still owns;
 * - any other existing content is copied to `_fs_pre_block_content` first.
 *
 * Contact gets one extra safeguard. Its content is where the form lives, so
 * whatever is there is carried into the Contact Form block's shortcode
 * attribute rather than discarded — the form keeps rendering, in the same
 * column, owned by the same plugin, with the same recipients and redirect.
 *
 * Pages are matched by slug and never created, so IDs, slugs and URLs are
 * untouched.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_pages = array(
	'impact-stories' => <<<'BLOCKS'
<!-- wp:focused-schools/page-hero {"eyebrow":"Impact stories","heading":"The measure of our work is what changed because of it.","subheading":"Districts and schools that partnered with us, and what actually moved.","ctaLabel":"Browse Stories","ctaUrl":"#stories","imageAlt":"District leaders reviewing progress together."} /-->

<!-- wp:focused-schools/story-spotlight /-->

<!-- wp:focused-schools/story-index /-->

<!-- wp:focused-schools/mission-close {"eyebrow":"Write the next one","heading":"Your district could be the next story.","body":"Tell us where your district is headed. If we are a fit we will say so — and if we are not, we will say that too.","ctaLabel":"Let's Talk","ctaUrl":"/contact/","cta2Label":"Explore Our Services","cta2Url":"/services/","imageAlt":"Educators celebrating progress together."} /-->
BLOCKS
	,
	'podcast'        => <<<'BLOCKS'
<!-- wp:focused-schools/page-hero {"eyebrow":"Podcast","heading":"Conversations on Learning","subheading":"Candid conversations with the leaders doing this work now.","ctaLabel":"Listen Now","ctaUrl":"#listen","imageAlt":"A Focused Schools conversation being recorded."} /-->

<!-- wp:focused-schools/podcast-subscribe /-->

<!-- wp:focused-schools/podcast-latest /-->

<!-- wp:focused-schools/podcast-listen /-->

<!-- wp:focused-schools/podcast-videos /-->

<!-- wp:focused-schools/mission-close {"eyebrow":"Say hello","heading":"Get to Know Us.","body":"Tell us where your district is headed and we will introduce you to the people who would carry the work with you.","ctaLabel":"Let's Talk","ctaUrl":"/contact/","cta2Label":"See Impact Stories","cta2Url":"/impact-stories/","imageAlt":"Two education leaders in conversation."} /-->
BLOCKS
	,
	'contact'        => <<<'BLOCKS'
<!-- wp:focused-schools/contact-hero /-->

<!-- wp:focused-schools/contact-form /-->

<!-- wp:focused-schools/contact-reach /-->
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

	if ( 'builder' === get_post_meta( $fs_page->ID, '_elementor_edit_mode', true ) ) {
		WP_CLI::warning(
			sprintf(
				'/%1$s/ (ID %2$d) is Elementor-built, so it was skipped. Its template already renders its Elementor output; convert it in wp-admin first if you want the blocks.',
				$fs_slug,
				$fs_page->ID
			)
		);
		++$fs_skipped;
		continue;
	}

	$fs_note = '';

	/*
	 * Contact's content is where the form lives. Carry it into the block
	 * rather than replacing it, so the form keeps working untouched.
	 */
	if ( 'contact' === $fs_slug && '' !== $fs_existing ) {
		$fs_blocks = str_replace(
			'<!-- wp:focused-schools/contact-form /-->',
			'<!-- wp:focused-schools/contact-form ' . wp_json_encode( array( 'formShortcode' => $fs_existing ) ) . ' /-->',
			$fs_blocks
		);
		$fs_note   = ' - existing form content moved into the Contact Form block';
	}

	if ( '' !== $fs_existing ) {
		update_post_meta( $fs_page->ID, '_fs_pre_block_content', $fs_existing );
	}

	/*
	 * wp_update_post() unslashes what it is given, which would strip the
	 * backslashes JSON needs inside a block's attributes and leave the block
	 * unparseable. Slashing first means it arrives intact.
	 */
	$fs_result = wp_update_post(
		array(
			'ID'           => $fs_page->ID,
			'post_content' => wp_slash( $fs_blocks ),
		),
		true
	);

	if ( is_wp_error( $fs_result ) ) {
		WP_CLI::warning( sprintf( '/%1$s/: %2$s', $fs_slug, $fs_result->get_error_message() ) );
		continue;
	}

	WP_CLI::log( sprintf( '  /%1$s/ (ID %2$d) seeded%3$s', $fs_slug, $fs_page->ID, $fs_note ) );
	++$fs_seeded;
}

WP_CLI::success( 'Page blocks seeded.' );
WP_CLI::log( sprintf( '  seeded  : %d', $fs_seeded ) );
WP_CLI::log( sprintf( '  skipped : %d', $fs_skipped ) );
WP_CLI::log( '  Page IDs, slugs and URLs were not touched.' );
