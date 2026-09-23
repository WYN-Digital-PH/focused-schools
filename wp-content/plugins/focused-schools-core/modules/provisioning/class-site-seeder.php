<?php
/**
 * Site provisioning: pages and navigation menus.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Provisioning;

defined( 'ABSPATH' ) || exit;

/**
 * Site_Seeder.
 *
 * Creates the Pages the theme's page-{slug}.php templates attach to, and the
 * navigation menus the header and footer render — but only the ones that are
 * missing.
 *
 * This class is deliberately additive-only. It has no code path that edits,
 * renames, re-slugs, re-IDs, trashes or reorders an existing Page, menu or
 * menu item, and it never touches Settings > Reading (show_on_front /
 * page_for_posts), so the Blog Posts Page and every existing URL are safe by
 * construction — see docs/AGENTS.md "URL Preservation".
 *
 * A theme menu location that already has a menu assigned is left alone; only
 * an empty location is filled.
 */
class Site_Seeder {

	/**
	 * Pages the theme's templates attach to, as slug => default title.
	 *
	 * The slug is the contract: WordPress's page-{slug}.php hierarchy is what
	 * binds each template to its page, so these must match the template
	 * filenames in the theme.
	 *
	 * 'home' is the exception: it has no page-home.php. It exists so a site
	 * can be switched to a static front page, which front-page.php then
	 * renders. This command creates the Page only — it never touches
	 * Settings > Reading, so making it the front page stays a deliberate
	 * manual step (see docs/AGENTS.md "URL Preservation").
	 *
	 * @var array<string, string>
	 */
	const PAGES = array(
		'home'                     => 'Home',
		'about-our-mission-vision' => 'About Our Mission & Vision',
		'services'                 => 'Services',
		'team'                     => 'Team',
		'impact-stories'           => 'Impact Stories',
		'podcast'                  => 'Podcast',
		'contact'                  => 'Contact',
		'thanks'                   => 'Thanks',
	);

	/**
	 * Menus to ensure, as theme location => [name, page slugs in order].
	 *
	 * @var array<string, array{name:string,items:string[]}>
	 */
	const MENUS = array(
		'primary' => array(
			'name'  => 'Primary',
			'items' => array( 'about-our-mission-vision', 'team', 'services', 'impact-stories', 'podcast' ),
		),
		'footer'  => array(
			'name'  => 'Footer',
			'items' => array( 'contact', 'thanks' ),
		),
	);

	/**
	 * Menu item labels that differ from the page title, as slug => label.
	 *
	 * @var array<string, string>
	 */
	const MENU_LABELS = array(
		'about-our-mission-vision' => 'About',
	);

	/**
	 * Find an existing published or draft Page by slug.
	 *
	 * @param string $slug Page slug.
	 * @return \WP_Post|null
	 */
	public static function find_page( $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );

		return $page instanceof \WP_Post ? $page : null;
	}

	/**
	 * Build a read-only report of what is present and what is missing.
	 * Makes no changes.
	 *
	 * @return array{pages:array<int,array>,menus:array<int,array>,locations:array<int,string>}
	 */
	public static function preview() {
		$pages = array();

		foreach ( self::PAGES as $slug => $title ) {
			$page = self::find_page( $slug );

			$pages[] = array(
				'slug'    => $slug,
				'title'   => $page ? $page->post_title : $title,
				'id'      => $page ? (int) $page->ID : 0,
				'status'  => $page ? $page->post_status : '',
				'exists'  => (bool) $page,
				'missing' => ! $page,
			);
		}

		$menus     = array();
		$locations = get_nav_menu_locations();

		foreach ( self::MENUS as $location => $menu ) {
			$object = wp_get_nav_menu_object( $menu['name'] );
			$items  = $object ? wp_get_nav_menu_items( $object->term_id ) : array();

			$menus[] = array(
				'location'         => $location,
				'name'             => $menu['name'],
				'exists'           => (bool) $object,
				'item_count'       => is_array( $items ) ? count( $items ) : 0,
				'assigned'         => ! empty( $locations[ $location ] ),
				'location_missing' => ! self::location_registered( $location ),
			);
		}

		return array(
			'pages'     => $pages,
			'menus'     => $menus,
			'locations' => array_keys( get_registered_nav_menus() ),
		);
	}

	/**
	 * Whether the active theme registers the given menu location.
	 *
	 * @param string $location Theme location key.
	 * @return bool
	 */
	public static function location_registered( $location ) {
		return array_key_exists( $location, get_registered_nav_menus() );
	}

	/**
	 * Create every missing Page. Existing Pages are returned untouched.
	 *
	 * @return array<int, array> Page rows with a 'created' flag and any error.
	 */
	public static function apply_pages() {
		$report = array();

		foreach ( self::PAGES as $slug => $title ) {
			$page = self::find_page( $slug );

			if ( $page ) {
				$report[] = array(
					'slug'    => $slug,
					'title'   => $page->post_title,
					'id'      => (int) $page->ID,
					'created' => false,
					'error'   => '',
				);
				continue;
			}

			$page_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => $title,
					'post_name'   => $slug,
				),
				true
			);

			$report[] = array(
				'slug'    => $slug,
				'title'   => $title,
				'id'      => is_wp_error( $page_id ) ? 0 : (int) $page_id,
				'created' => ! is_wp_error( $page_id ),
				'error'   => is_wp_error( $page_id ) ? $page_id->get_error_message() : '',
			);
		}

		return $report;
	}

	/**
	 * Create any missing menu, add an item for each of its pages that does
	 * not already have one, and fill the theme location only when it is
	 * empty.
	 *
	 * @return array<int, array> Menu rows describing what changed.
	 */
	public static function apply_menus() {
		$report    = array();
		$locations = get_nav_menu_locations();

		foreach ( self::MENUS as $location => $menu ) {
			$object       = wp_get_nav_menu_object( $menu['name'] );
			$menu_created = false;

			if ( ! $object ) {
				$menu_id = wp_create_nav_menu( $menu['name'] );

				if ( is_wp_error( $menu_id ) ) {
					$report[] = array(
						'location'     => $location,
						'name'         => $menu['name'],
						'menu_created' => false,
						'items_added'  => 0,
						'location_set' => false,
						'error'        => $menu_id->get_error_message(),
					);
					continue;
				}

				$object       = wp_get_nav_menu_object( $menu_id );
				$menu_created = true;
			}

			// Page IDs this menu already points at, so re-running adds nothing.
			$existing   = wp_get_nav_menu_items( $object->term_id );
			$existing   = is_array( $existing ) ? $existing : array();
			$linked_ids = array();

			foreach ( $existing as $item ) {
				if ( 'post_type' === $item->type && 'page' === $item->object ) {
					$linked_ids[] = (int) $item->object_id;
				}
			}

			$items_added = 0;

			foreach ( $menu['items'] as $slug ) {
				$page = self::find_page( $slug );

				if ( ! $page || in_array( (int) $page->ID, $linked_ids, true ) ) {
					continue;
				}

				$label = isset( self::MENU_LABELS[ $slug ] ) ? self::MENU_LABELS[ $slug ] : $page->post_title;

				$item_id = wp_update_nav_menu_item(
					$object->term_id,
					0,
					array(
						'menu-item-object-id' => (int) $page->ID,
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-title'     => $label,
						'menu-item-status'    => 'publish',
					)
				);

				if ( ! is_wp_error( $item_id ) ) {
					++$items_added;
				}
			}

			// Only fill an empty location — never reassign one an editor set.
			$location_set = false;

			if ( self::location_registered( $location ) && empty( $locations[ $location ] ) ) {
				$locations[ $location ] = (int) $object->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
				$location_set = true;
			}

			$report[] = array(
				'location'     => $location,
				'name'         => $menu['name'],
				'menu_created' => $menu_created,
				'items_added'  => $items_added,
				'location_set' => $location_set,
				'error'        => '',
			);
		}

		return $report;
	}
}
