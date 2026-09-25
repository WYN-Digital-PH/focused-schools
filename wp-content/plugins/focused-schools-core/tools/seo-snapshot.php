<?php
/**
 * Capture an SEO snapshot of every public URL, as CSV.
 *
 * Run it once before the theme switch and once after, then diff the two
 * files. That comparison is the launch gate: it shows whether any URL,
 * status code, canonical, title, meta description, indexability or heading
 * changed.
 *
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seo-snapshot.php before.csv
 *     # ...deploy and activate the theme...
 *     wp eval-file wp-content/plugins/focused-schools-core/tools/seo-snapshot.php after.csv
 *
 * Behind a staging password, pass the credentials as a second argument:
 *
 *     wp eval-file .../seo-snapshot.php before.csv user:password
 *
 * Read-only: it fetches pages over HTTP exactly as a visitor would and
 * writes one CSV. It changes nothing.
 *
 * @package FocusedSchoolsCore
 */

defined( 'ABSPATH' ) || exit;

$fs_out  = isset( $args[0] ) ? $args[0] : 'seo-snapshot.csv';
$fs_auth = isset( $args[1] ) ? $args[1] : '';

/**
 * Collect every URL a search engine could reach.
 *
 * @return string[]
 */
function fs_seo_urls() {
	$urls = array( home_url( '/' ) );

	foreach ( get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	) as $id ) {
		$urls[] = get_permalink( $id );
	}

	foreach ( get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	) as $id ) {
		$urls[] = get_permalink( $id );
	}

	// Public custom post types, if any are publicly queryable.
	foreach ( get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		)
	) as $type ) {
		foreach ( get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		) as $id ) {
			$urls[] = get_permalink( $id );
		}
	}

	foreach ( array( 'category', 'post_tag' ) as $tax ) {
		foreach ( get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => true,
			)
		) as $term ) {
			if ( ! is_wp_error( $term ) ) {
				$urls[] = get_term_link( $term );
			}
		}
	}

	// The blog archive and its pages.
	$posts_page = (int) get_option( 'page_for_posts' );

	if ( $posts_page ) {
		$per   = max( 1, (int) get_option( 'posts_per_page' ) );
		$total = (int) wp_count_posts( 'post' )->publish;

		for ( $page = 2; $page <= (int) ceil( $total / $per ); $page++ ) {
			$urls[] = trailingslashit( get_permalink( $posts_page ) ) . 'page/' . $page . '/';
		}
	}

	$urls = array_values( array_unique( array_filter( $urls, 'is_string' ) ) );
	sort( $urls );

	return $urls;
}

/**
 * Pull one value out of a chunk of HTML.
 *
 * @param string $pattern Regex with one capture group.
 * @param string $html    Document.
 * @return string
 */
function fs_seo_match( $pattern, $html ) {
	return preg_match( $pattern, $html, $m ) ? trim( html_entity_decode( $m[1], ENT_QUOTES, 'UTF-8' ) ) : '';
}

$fs_urls = fs_seo_urls();
WP_CLI::log( sprintf( 'Fetching %d URLs...', count( $fs_urls ) ) );

$fs_headers = array();

if ( '' !== $fs_auth ) {
	$fs_headers['Authorization'] = 'Basic ' . base64_encode( $fs_auth );
}

$fs_rows = array(
	array( 'url', 'status', 'title', 'meta_description', 'canonical', 'robots', 'h1_count', 'h1_first', 'og_title', 'og_url' ),
);

$fs_done = 0;

foreach ( $fs_urls as $fs_url ) {
	$fs_response = wp_remote_get(
		$fs_url,
		array(
			'timeout'     => 30,
			'redirection' => 0,
			'headers'     => $fs_headers,
			'sslverify'   => false,
		)
	);

	if ( is_wp_error( $fs_response ) ) {
		$fs_rows[] = array( $fs_url, 'ERROR: ' . $fs_response->get_error_message(), '', '', '', '', '', '', '', '' );
		continue;
	}

	$fs_code = (int) wp_remote_retrieve_response_code( $fs_response );
	$fs_html = (string) wp_remote_retrieve_body( $fs_response );
	$fs_head = substr( $fs_html, 0, 60000 );

	preg_match_all( '/<h1[\s>]/i', $fs_html, $fs_h1s );

	$fs_rows[] = array(
		$fs_url,
		$fs_code,
		fs_seo_match( '#<title[^>]*>(.*?)</title>#is', $fs_head ),
		fs_seo_match( '#<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']*)#i', $fs_head ),
		fs_seo_match( '#<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']*)#i', $fs_head ),
		fs_seo_match( '#<meta[^>]+name=["\']robots["\'][^>]+content=["\']([^"\']*)#i', $fs_head ),
		count( $fs_h1s[0] ),
		trim( wp_strip_all_tags( fs_seo_match( '#<h1[^>]*>(.*?)</h1>#is', $fs_html ) ) ),
		fs_seo_match( '#<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']*)#i', $fs_head ),
		fs_seo_match( '#<meta[^>]+property=["\']og:url["\'][^>]+content=["\']([^"\']*)#i', $fs_head ),
	);

	++$fs_done;

	if ( 0 === $fs_done % 25 ) {
		WP_CLI::log( sprintf( '  %d/%d', $fs_done, count( $fs_urls ) ) );
	}
}

// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPress.WP.AlternativeFunctions.file_system_operations_fclose -- CLI-only QA tool; WP_Filesystem has no streaming CSV writer.
$fs_handle = fopen( $fs_out, 'w' );

if ( ! $fs_handle ) {
	WP_CLI::error( 'Could not write to ' . $fs_out );
}

foreach ( $fs_rows as $fs_row ) {
	fputcsv( $fs_handle, $fs_row );
}

fclose( $fs_handle );
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPress.WP.AlternativeFunctions.file_system_operations_fclose

// A quick read on the snapshot, so problems are obvious without opening it.
$fs_bad     = 0;
$fs_nocanon = 0;
$fs_noh1    = 0;
$fs_multih1 = 0;

foreach ( array_slice( $fs_rows, 1 ) as $fs_row ) {
	if ( 200 !== (int) $fs_row[1] ) {
		++$fs_bad;
	}
	if ( '' === $fs_row[4] ) {
		++$fs_nocanon;
	}
	if ( 0 === (int) $fs_row[6] ) {
		++$fs_noh1;
	}
	if ( (int) $fs_row[6] > 1 ) {
		++$fs_multih1;
	}
}

WP_CLI::success( sprintf( 'Wrote %s (%d URLs).', $fs_out, count( $fs_rows ) - 1 ) );
WP_CLI::log( sprintf( '  non-200          : %d', $fs_bad ) );
WP_CLI::log( sprintf( '  missing canonical: %d', $fs_nocanon ) );
WP_CLI::log( sprintf( '  no H1            : %d', $fs_noh1 ) );
WP_CLI::log( sprintf( '  more than one H1 : %d', $fs_multih1 ) );
