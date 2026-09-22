<?php
/**
 * WP-CLI command for site provisioning.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Provisioning;

defined( 'ABSPATH' ) || exit;

/**
 * CLI_Command.
 *
 * Thin wrapper around Site_Seeder: prints a preview table, and only writes
 * when --write is passed and the user confirms. Dry-run is the default.
 */
class CLI_Command {

	/**
	 * Create the Pages the theme's templates attach to, plus the header and
	 * footer navigation menus.
	 *
	 * Dry-run by default: prints what is missing without changing anything.
	 * Pass --write to create it; you will be asked to confirm unless --yes is
	 * also passed.
	 *
	 * Only missing things are created. An existing Page, menu, menu item or
	 * filled menu location is left exactly as it is — this command has no code
	 * path that edits or deletes one, and it never touches Settings > Reading,
	 * so existing Page IDs, slugs and the Blog Posts Page are safe.
	 *
	 * ## OPTIONS
	 *
	 * [--write]
	 * : Actually create what is missing instead of previewing.
	 *
	 * [--yes]
	 * : Skip the confirmation prompt. Only meaningful alongside --write.
	 *
	 * ## EXAMPLES
	 *
	 *     wp focused-schools seed-site
	 *     wp focused-schools seed-site --write
	 *     wp focused-schools seed-site --write --yes
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments (unused).
	 * @param array $assoc_args Associative arguments (--write, --yes).
	 * @return void
	 */
	public function __invoke( $args, $assoc_args ) {
		$write   = (bool) \WP_CLI\Utils\get_flag_value( $assoc_args, 'write', false );
		$preview = Site_Seeder::preview();

		$this->print_preview( $preview );

		$missing_pages = count(
			array_filter(
				$preview['pages'],
				static function ( $row ) {
					return $row['missing'];
				}
			)
		);

		$menu_work = count(
			array_filter(
				$preview['menus'],
				static function ( $row ) {
					return ! $row['exists'] || ! $row['assigned'];
				}
			)
		);

		foreach ( $preview['menus'] as $row ) {
			if ( $row['location_missing'] ) {
				\WP_CLI::warning(
					sprintf(
						'The active theme does not register a "%s" menu location — a menu can be created but not assigned to it. Is the Focused Schools theme active?',
						$row['location']
					)
				);
			}
		}

		if ( ! $write ) {
			\WP_CLI::log( '' );
			\WP_CLI::log( 'Dry run only — no changes made. Re-run with --write to apply.' );
			return;
		}

		if ( 0 === $missing_pages && 0 === $menu_work ) {
			\WP_CLI::success( 'Nothing to do: every page and menu is already in place.' );
			return;
		}

		\WP_CLI::confirm(
			sprintf( 'Create %d missing page(s) and set up %d menu(s)?', $missing_pages, $menu_work ),
			$assoc_args
		);

		$pages = Site_Seeder::apply_pages();
		$menus = Site_Seeder::apply_menus();

		$created = 0;

		foreach ( $pages as $row ) {
			if ( $row['created'] ) {
				++$created;
			}

			if ( '' !== $row['error'] ) {
				\WP_CLI::warning( sprintf( 'Page "%1$s": %2$s', $row['slug'], $row['error'] ) );
			}
		}

		\WP_CLI\Utils\format_items(
			'table',
			array_map(
				static function ( $row ) {
					return array(
						'Slug'    => $row['slug'],
						'ID'      => $row['id'],
						'Created' => $row['created'] ? 'yes' : 'no (already existed)',
					);
				},
				$pages
			),
			array( 'Slug', 'ID', 'Created' )
		);

		\WP_CLI\Utils\format_items(
			'table',
			array_map(
				static function ( $row ) {
					return array(
						'Location'     => $row['location'],
						'Menu'         => $row['name'],
						'Menu created' => $row['menu_created'] ? 'yes' : 'no',
						'Items added'  => $row['items_added'],
						'Location set' => $row['location_set'] ? 'yes' : 'no',
					);
				},
				$menus
			),
			array( 'Location', 'Menu', 'Menu created', 'Items added', 'Location set' )
		);

		\WP_CLI::success( sprintf( 'Created %d page(s) and provisioned %d menu(s).', $created, count( $menus ) ) );
	}

	/**
	 * Print the read-only preview tables.
	 *
	 * @param array $preview Result of Site_Seeder::preview().
	 * @return void
	 */
	private function print_preview( array $preview ) {
		\WP_CLI::log( 'Pages the theme templates attach to:' );
		\WP_CLI\Utils\format_items(
			'table',
			array_map(
				static function ( $row ) {
					return array(
						'Slug'   => $row['slug'],
						'ID'     => $row['id'] ? $row['id'] : '—',
						'Status' => $row['status'] ? $row['status'] : '—',
						'Action' => $row['missing'] ? 'CREATE' : 'keep',
					);
				},
				$preview['pages']
			),
			array( 'Slug', 'ID', 'Status', 'Action' )
		);

		\WP_CLI::log( '' );
		\WP_CLI::log( 'Navigation menus:' );
		\WP_CLI\Utils\format_items(
			'table',
			array_map(
				static function ( $row ) {
					$action = array();

					if ( ! $row['exists'] ) {
						$action[] = 'create menu';
					}

					if ( ! $row['assigned'] ) {
						$action[] = 'assign location';
					}

					return array(
						'Location' => $row['location'],
						'Menu'     => $row['name'],
						'Exists'   => $row['exists'] ? 'yes' : 'no',
						'Items'    => $row['item_count'],
						'Assigned' => $row['assigned'] ? 'yes' : 'no',
						'Action'   => empty( $action ) ? 'keep' : implode( ' + ', $action ),
					);
				},
				$preview['menus']
			),
			array( 'Location', 'Menu', 'Exists', 'Items', 'Assigned', 'Action' )
		);

		\WP_CLI::log( '' );
		\WP_CLI::log( 'Theme menu locations registered: ' . ( empty( $preview['locations'] ) ? '(none)' : implode( ', ', $preview['locations'] ) ) );
	}
}
