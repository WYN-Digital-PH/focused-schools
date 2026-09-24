<?php
/**
 * Component: Buzzsprout player.
 *
 * Contract ($args):
 * - podcast_id (string, required) the Buzzsprout podcast id
 * - episode_id (string) renders that single episode instead of the full list
 * - player     (string) Buzzsprout player size, 'large' (default) or 'small'
 * - empty_text (string) shown when no podcast id is configured
 *
 * This prints Buzzsprout's own script embed — the same one the site uses
 * today — rather than an iframe. They are not equivalent: the script embed is
 * what Buzzsprout supports, what their player controls and styling expect,
 * and what was already in place, so reproducing it exactly is what keeps the
 * audio behaving as it does now.
 *
 * The script is left to load on its own, as Buzzsprout intend, and writes
 * into the container div by id. Nothing here modifies the player.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_podcast_id = isset( $args['podcast_id'] ) ? preg_replace( '/[^0-9]/', '', (string) $args['podcast_id'] ) : '';
$fs_episode_id = isset( $args['episode_id'] ) ? preg_replace( '/[^0-9]/', '', (string) $args['episode_id'] ) : '';
$fs_player     = isset( $args['player'] ) && 'small' === $args['player'] ? 'small' : 'large';
$fs_empty_text = isset( $args['empty_text'] ) ? $args['empty_text'] : __( 'Episodes will appear here once the podcast is connected.', 'focused-schools' );

if ( '' === $fs_podcast_id ) :
	?>
	<p class="fs-podcast__empty"><?php echo esc_html( $fs_empty_text ); ?></p>
	<?php
	return;
endif;

/*
 * Buzzsprout addresses the whole show as {podcast}.js and one episode as
 * {podcast}/{episode}.js, and writes into the container whose id it is given.
 * The id has to be unique on the page, so the episode player and the full
 * list can both appear without one overwriting the other.
 */
$fs_container = $fs_episode_id
	? 'buzzsprout-player-' . $fs_episode_id
	: 'buzzsprout-large-player';

$fs_path = $fs_episode_id
	? $fs_podcast_id . '/' . $fs_episode_id . '.js'
	: $fs_podcast_id . '.js';

$fs_src = add_query_arg(
	array(
		'container_id' => $fs_container,
		'player'       => $fs_player,
	),
	'https://www.buzzsprout.com/' . $fs_path
);
?>
<div class="fs-podcast-player">
	<div id="<?php echo esc_attr( $fs_container ); ?>"></div>
	<?php
	/*
	 * Printed inline rather than enqueued, deliberately. This reproduces the
	 * embed the site already runs through Elementor exactly as Buzzsprout
	 * publish it, which is what keeps the audio behaving as it does today.
	 * Enqueuing would move it to the footer and change the order it runs in
	 * relative to its container — a change to working third-party audio for
	 * no benefit to the reader.
	 */
	?>
	<script type="text/javascript" charset="utf-8" src="<?php echo esc_url( $fs_src ); ?>"></script><?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- vendor embed, see above. ?>
</div>
