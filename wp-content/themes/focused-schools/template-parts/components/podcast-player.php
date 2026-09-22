<?php
/**
 * Component: Buzzsprout podcast player.
 *
 * Contract ($args):
 * - podcast_id (string|int, required) the numeric Buzzsprout podcast ID,
 *   from Site Settings → Podcast. The <iframe> src is built from it here,
 *   so no raw embed HTML is ever stored in the database or echoed.
 * - title      (string) accessible title for the iframe.
 * - empty_text (string) message shown when no podcast ID is configured.
 *
 * Buzzsprout remains the audio source of record for this project (see
 * docs/AGENTS.md) — this component embeds their hosted player rather than
 * reimplementing playback or storing episode data locally.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_podcast_id = isset( $args['podcast_id'] ) ? preg_replace( '/[^0-9]/', '', (string) $args['podcast_id'] ) : '';
$fs_title      = isset( $args['title'] ) ? $args['title'] : __( 'Podcast episodes', 'focused-schools' );
$fs_empty_text = isset( $args['empty_text'] ) ? $args['empty_text'] : __( 'Episodes will appear here once the podcast is connected.', 'focused-schools' );

if ( '' === $fs_podcast_id ) :
	?>
	<p class="fs-podcast__empty"><?php echo esc_html( $fs_empty_text ); ?></p>
	<?php
	return;
endif;

$fs_src = add_query_arg(
	array(
		'client_source' => 'large_player',
		'iframe'        => 'true',
	),
	'https://www.buzzsprout.com/' . rawurlencode( $fs_podcast_id )
);
?>
<div class="fs-podcast-player">
	<iframe
		class="fs-podcast-player__frame"
		src="<?php echo esc_url( $fs_src ); ?>"
		title="<?php echo esc_attr( $fs_title ); ?>"
		loading="lazy"
		width="100%"
		height="600"
		frameborder="0"
		scrolling="no"
	></iframe>
</div>
