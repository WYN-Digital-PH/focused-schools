<?php
/**
 * Compare two snapshots from seo-snapshot.php and report the regressions.
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seo-diff.php before.csv after.csv
 *
 * Reports, per URL: anything that disappeared, any status code change, and
 * any change to canonical, title, meta description, indexability or heading
 * count. Silent on URLs that are identical, so the output is the work list.
 *
 * Read-only.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_before_path = isset( $args[0] ) ? $args[0] : '';
$fs_after_path  = isset( $args[1] ) ? $args[1] : '';

if ( ! is_readable( $fs_before_path ) || ! is_readable( $fs_after_path ) ) {
	WP_CLI::error( 'Usage: wp eval-file seo-diff.php <before.csv> <after.csv>' );
}

/**
 * Read a snapshot into url => row.
 *
 * @param string $file CSV path.
 * @return array<string, array<string, string>>
 */
function fs_seo_read( $file ) {
	// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPress.WP.AlternativeFunctions.file_system_operations_fclose -- CLI-only QA tool; WP_Filesystem has no streaming CSV reader.
	$rows   = array();
	$handle = fopen( $file, 'r' );
	$head   = fgetcsv( $handle );

	$line = fgetcsv( $handle );

	while ( false !== $line ) {
		if ( count( $line ) !== count( $head ) ) {
			$line = fgetcsv( $handle );
			continue;
		}

		$row = array_combine( $head, $line );

		// Compare by path, so the two environments' domains do not matter.
		$key = wp_parse_url( $row['url'], PHP_URL_PATH );
		$key = $key ? untrailingslashit( $key ) : $row['url'];

		if ( '' === $key ) {
			$key = '/';
		}

		$rows[ $key ] = $row;
		$line         = fgetcsv( $handle );
	}

	fclose( $handle );
	// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPress.WP.AlternativeFunctions.file_system_operations_fclose

	return $rows;
}

$fs_before = fs_seo_read( $fs_before_path );
$fs_after  = fs_seo_read( $fs_after_path );

WP_CLI::log( sprintf( 'before: %d URLs   after: %d URLs', count( $fs_before ), count( $fs_after ) ) );
WP_CLI::log( '' );

$fs_watch = array(
	'status'           => 'status code',
	'canonical'        => 'canonical',
	'title'            => 'title',
	'meta_description' => 'meta description',
	'robots'           => 'robots',
	'h1_count'         => 'H1 count',
);

$fs_gone    = array();
$fs_changed = array();
$fs_added   = array();

foreach ( $fs_before as $fs_path => $row ) {
	if ( ! isset( $fs_after[ $fs_path ] ) ) {
		$fs_gone[] = $fs_path;
		continue;
	}

	$diffs = array();

	foreach ( $fs_watch as $field => $label ) {
		$was = isset( $row[ $field ] ) ? $row[ $field ] : '';
		$now = isset( $fs_after[ $fs_path ][ $field ] ) ? $fs_after[ $fs_path ][ $field ] : '';

		// Canonicals and OG URLs carry the domain, which differs by design.
		if ( 'canonical' === $field ) {
			$was = (string) wp_parse_url( $was, PHP_URL_PATH );
			$now = (string) wp_parse_url( $now, PHP_URL_PATH );
		}

		if ( $was !== $now ) {
			$diffs[] = sprintf( '%s: "%s" -> "%s"', $label, $was, $now );
		}
	}

	if ( $diffs ) {
		$fs_changed[ $fs_path ] = $diffs;
	}
}

foreach ( array_keys( $fs_after ) as $fs_path ) {
	if ( ! isset( $fs_before[ $fs_path ] ) ) {
		$fs_added[] = $fs_path;
	}
}

if ( $fs_gone ) {
	WP_CLI::warning( sprintf( 'URLs that stopped resolving (%d) — these are regressions:', count( $fs_gone ) ) );

	foreach ( $fs_gone as $fs_path ) {
		WP_CLI::log( '  ' . $fs_path );
	}

	WP_CLI::log( '' );
}

if ( $fs_changed ) {
	WP_CLI::warning( sprintf( 'URLs whose SEO fields changed (%d):', count( $fs_changed ) ) );

	foreach ( $fs_changed as $fs_path => $diffs ) {
		WP_CLI::log( '  ' . $fs_path );

		foreach ( $diffs as $diff ) {
			WP_CLI::log( '      ' . $diff );
		}
	}

	WP_CLI::log( '' );
}

if ( $fs_added ) {
	WP_CLI::log( sprintf( 'New URLs (%d) — expected only if you added pages:', count( $fs_added ) ) );

	foreach ( $fs_added as $fs_path ) {
		WP_CLI::log( '  ' . $fs_path );
	}

	WP_CLI::log( '' );
}

if ( ! $fs_gone && ! $fs_changed ) {
	WP_CLI::success( 'No regressions: every URL still resolves with the same status, canonical, title, meta, robots and H1 count.' );
} else {
	WP_CLI::log( sprintf( 'Summary: %d gone, %d changed, %d new.', count( $fs_gone ), count( $fs_changed ), count( $fs_added ) ) );
}
