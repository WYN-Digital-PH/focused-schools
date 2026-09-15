<?php
/**
 * Legacy Page bridge for Impact Stories.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Impact_Stories;

defined( 'ABSPATH' ) || exit;

/**
 * Legacy_Bridge.
 *
 * Tags approved, existing legacy Page IDs as impact stories via a single
 * dedicated meta key. This class performs exactly one kind of write —
 * update_post_meta() for META_KEY — and never calls wp_update_post() or
 * touches any other meta key. It cannot alter post_type, post_name (slug),
 * post content, or Yoast (or any other) metadata, because it has no code
 * path that does so.
 */
class Legacy_Bridge {

	/**
	 * Meta key used to mark a legacy Page as an impact story.
	 *
	 * @var string
	 */
	const META_KEY = '_fs_legacy_impact_story';

	/**
	 * Build a read-only report for the given Page IDs. Makes no changes.
	 *
	 * @param int[] $page_ids Candidate Page IDs.
	 * @return array<int, array{id:int,title:string,exists:bool,is_page:bool,already_tagged:bool,eligible:bool}>
	 */
	public static function preview( array $page_ids ) {
		$report = array();

		foreach ( $page_ids as $page_id ) {
			$page_id = absint( $page_id );
			$post    = $page_id ? get_post( $page_id ) : null;

			$exists         = (bool) $post;
			$is_page        = $exists && 'page' === $post->post_type;
			$already_tagged = $exists ? (bool) get_post_meta( $page_id, self::META_KEY, true ) : false;

			$report[] = array(
				'id'             => $page_id,
				'title'          => $exists ? $post->post_title : '',
				'exists'         => $exists,
				'is_page'        => $is_page,
				'already_tagged' => $already_tagged,
				'eligible'       => $exists && $is_page && ! $already_tagged,
			);
		}

		return $report;
	}

	/**
	 * Apply the legacy bridge meta to eligible pages only.
	 *
	 * Eligible = exists, is post_type "page", and not already tagged.
	 * Anything else is left completely untouched and reported as ineligible.
	 *
	 * @param int[] $page_ids Candidate Page IDs.
	 * @return array<int, array{id:int,title:string,exists:bool,is_page:bool,already_tagged:bool,eligible:bool,applied:bool}>
	 */
	public static function apply( array $page_ids ) {
		$report = self::preview( $page_ids );

		foreach ( $report as &$row ) {
			$row['applied'] = false;

			if ( $row['eligible'] ) {
				$row['applied'] = (bool) update_post_meta( $row['id'], self::META_KEY, 1 );
			}
		}
		unset( $row );

		return $report;
	}
}
