<?php
/**
 * Server render for focused-schools/story-spotlight.
 *
 * Shows whichever story is marked Featured. It is omitted entirely when
 * nothing is featured, and while a filter is applied — a spotlight that
 * ignores the filter the reader just set would be misleading.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_data = focused_schools_impact_stories();

if ( empty( $fs_data['featured'] ) || $fs_data['filtered'] ) {
	return;
}

get_template_part( 'template-parts/components/story-spotlight', null, array( 'post' => $fs_data['featured'] ) );
