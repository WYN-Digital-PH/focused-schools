<?php
/**
 * WP-CLI command for the Impact Stories legacy bridge.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Impact_Stories;

defined( 'ABSPATH' ) || exit;

/**
 * CLI_Command.
 *
 * Thin wrapper around Legacy_Bridge: parses arguments, prints a preview
 * table, and only calls Legacy_Bridge::apply() when --write is passed and
 * the user confirms. Dry-run (preview only, no writes) is the default.
 */
class CLI_Command {

	/**
	 * Tag approved legacy Page IDs with the Impact Story bridge meta.
	 *
	 * Dry-run by default: prints the affected pages without changing
	 * anything. Pass --write to actually apply the tag; you will be asked
	 * to confirm unless the global --yes flag is also passed. Only pages
	 * that exist, are post_type "page", and are not already tagged are
	 * eligible — everything else is skipped and reported as such.
	 *
	 * ## OPTIONS
	 *
	 * --ids=<ids>
	 * : Comma-separated list of Page IDs to consider.
	 *
	 * [--write]
	 * : Actually write the meta instead of previewing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp focused-schools tag-legacy-impact-stories --ids=12,45,67
	 *     wp focused-schools tag-legacy-impact-stories --ids=12,45,67 --write
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments (unused).
	 * @param array $assoc_args Associative arguments (--ids, --write).
	 * @return void
	 */
	public function __invoke( $args, $assoc_args ) {
		if ( empty( $assoc_args['ids'] ) ) {
			\WP_CLI::error( 'Pass --ids=<comma-separated Page IDs>.' );
			return;
		}

		$page_ids = array_values( array_filter( array_map( 'absint', explode( ',', (string) $assoc_args['ids'] ) ) ) );

		if ( empty( $page_ids ) ) {
			\WP_CLI::error( 'No valid numeric Page IDs found in --ids.' );
			return;
		}

		$write = (bool) \WP_CLI\Utils\get_flag_value( $assoc_args, 'write', false );

		if ( ! $write ) {
			$preview = Legacy_Bridge::preview( $page_ids );
			$this->print_report( $preview, 'eligible' );
			\WP_CLI::log( '' );
			\WP_CLI::log( 'Dry run only — no changes made. Re-run with --write to apply.' );
			return;
		}

		$preview        = Legacy_Bridge::preview( $page_ids );
		$eligible_count = count(
			array_filter(
				$preview,
				function ( $row ) {
					return $row['eligible'];
				}
			)
		);

		$this->print_report( $preview, 'eligible' );

		if ( 0 === $eligible_count ) {
			\WP_CLI::warning( 'No eligible pages to tag. Nothing to do.' );
			return;
		}

		\WP_CLI::confirm(
			sprintf(
				'Tag %d page(s) with %s = 1?',
				$eligible_count,
				Legacy_Bridge::META_KEY
			)
		);

		$applied = Legacy_Bridge::apply( $page_ids );
		$this->print_report( $applied, 'applied' );

		$applied_count = count(
			array_filter(
				$applied,
				function ( $row ) {
					return ! empty( $row['applied'] );
				}
			)
		);

		\WP_CLI::success( sprintf( 'Tagged %d page(s).', $applied_count ) );
	}

	/**
	 * Print a report table for a set of Legacy_Bridge rows.
	 *
	 * @param array  $report      Rows from Legacy_Bridge::preview() or ::apply().
	 * @param string $flag_column Row key to render as the final status column ('eligible' or 'applied').
	 * @return void
	 */
	private function print_report( array $report, $flag_column ) {
		if ( empty( $report ) ) {
			\WP_CLI::log( 'No IDs to report.' );
			return;
		}

		$status_label = 'applied' === $flag_column ? 'Applied' : 'Eligible';
		$rows         = array();

		foreach ( $report as $row ) {
			$rows[] = array(
				'ID'             => $row['id'],
				'Title'          => $row['title'],
				'Exists'         => $row['exists'] ? 'yes' : 'no',
				'Is Page'        => $row['is_page'] ? 'yes' : 'no',
				'Already Tagged' => $row['already_tagged'] ? 'yes' : 'no',
				$status_label    => ! empty( $row[ $flag_column ] ) ? 'yes' : 'no',
			);
		}

		\WP_CLI\Utils\format_items( 'table', $rows, array_keys( $rows[0] ) );
	}
}
