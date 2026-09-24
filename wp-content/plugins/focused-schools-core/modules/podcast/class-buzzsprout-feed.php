<?php
/**
 * Buzzsprout RSS reader.
 *
 * Reads the show's public RSS feed to find the newest episode, so the
 * Latest Episode panel keeps itself current instead of an editor pasting an
 * episode id after every publish.
 *
 * This only reads metadata. Playback is still Buzzsprout's own embed, which
 * this never touches — the feed supplies the episode id, and Buzzsprout's
 * script does the rest.
 *
 * Pure fetch and normalize: no caching or options are read or written here,
 * so the caller decides when a request happens. See Podcast for the cache.
 *
 * @package FocusedSchoolsCore
 */

namespace FocusedSchoolsCore\Modules\Podcast;

defined( 'ABSPATH' ) || exit;

/**
 * Buzzsprout feed client.
 */
class Buzzsprout_Feed {

	/**
	 * Fetch the newest episode from a show's RSS feed.
	 *
	 * @param string $podcast_id Buzzsprout podcast id.
	 * @return array{episode_id:string,title:string,summary:string,published:string,duration:string}|\WP_Error
	 */
	public static function fetch_latest_episode( $podcast_id ) {
		$podcast_id = preg_replace( '/[^0-9]/', '', (string) $podcast_id );

		if ( '' === $podcast_id ) {
			return new \WP_Error(
				'fs_podcast_missing_id',
				__( 'No Buzzsprout podcast ID is set in Site Settings.', 'focused-schools-core' )
			);
		}

		$response = wp_remote_get(
			'https://feeds.buzzsprout.com/' . $podcast_id . '.rss',
			array( 'timeout' => 15 )
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			return new \WP_Error(
				'fs_podcast_feed_error',
				sprintf(
					/* translators: %d: HTTP status code. */
					__( 'The Buzzsprout feed responded with HTTP %d.', 'focused-schools-core' ),
					$code
				)
			);
		}

		$body = wp_remote_retrieve_body( $response );

		if ( '' === trim( (string) $body ) ) {
			return new \WP_Error(
				'fs_podcast_feed_empty',
				__( 'The Buzzsprout feed was empty.', 'focused-schools-core' )
			);
		}

		return self::parse_latest( $body );
	}

	/**
	 * Pull the first item out of a feed body.
	 *
	 * Parsed with SimpleXML rather than by pattern matching, with entity
	 * loading left off so a feed can never pull in an external document.
	 *
	 * @param string $body Raw RSS.
	 * @return array{episode_id:string,title:string,summary:string,published:string,duration:string}|\WP_Error
	 */
	private static function parse_latest( $body ) {
		$previous = libxml_use_internal_errors( true );
		$xml      = simplexml_load_string( $body, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET );
		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		if ( false === $xml || ! isset( $xml->channel->item[0] ) ) {
			return new \WP_Error(
				'fs_podcast_feed_invalid',
				__( 'The Buzzsprout feed could not be parsed.', 'focused-schools-core' )
			);
		}

		$item    = $xml->channel->item[0];
		$itunes  = $item->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' );
		$episode = '';

		// Buzzsprout's guid is "Buzzsprout-<episode id>"; the enclosure URL
		// carries the same id, so it serves as a fallback if that changes.
		if ( isset( $item->guid ) && preg_match( '/(\d+)/', (string) $item->guid, $matches ) ) {
			$episode = $matches[1];
		}

		if ( '' === $episode && isset( $item->enclosure['url'] ) && preg_match( '#/episodes/(\d+)#', (string) $item->enclosure['url'], $matches ) ) {
			$episode = $matches[1];
		}

		if ( '' === $episode ) {
			return new \WP_Error(
				'fs_podcast_feed_no_episode',
				__( 'The newest item in the Buzzsprout feed has no episode ID.', 'focused-schools-core' )
			);
		}

		$summary = '';

		foreach ( array( $item->description, isset( $itunes->summary ) ? $itunes->summary : null ) as $candidate ) {
			$text = trim( wp_strip_all_tags( (string) $candidate ) );

			if ( '' !== $text ) {
				$summary = $text;
				break;
			}
		}

		$published = isset( $item->pubDate ) ? strtotime( (string) $item->pubDate ) : false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- RSS element name.

		return array(
			'episode_id' => sanitize_text_field( $episode ),
			'title'      => sanitize_text_field( trim( (string) $item->title ) ),
			'summary'    => sanitize_textarea_field( $summary ),
			'published'  => $published ? gmdate( 'c', $published ) : '',
			'duration'   => isset( $itunes->duration ) ? sanitize_text_field( (string) $itunes->duration ) : '',
		);
	}
}
