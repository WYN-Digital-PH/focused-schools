<?php
/**
 * Component: Podcast latest-episode panel (teal split: copy + Buzzsprout
 * single-episode player on the left, cover art on the right).
 *
 * Contract ($args):
 * - podcast_id     (string|int, required) numeric Buzzsprout podcast ID.
 * - episode_id     (string|int, required) numeric Buzzsprout episode ID.
 * - title          (string, required)
 * - summary        (string)
 * - eyebrow        (string) e.g. "Episode 14 · March 4, 2026 · 28:14".
 * - image_url      (string) cover art; the white brand mark on a darker teal
 *                  ground is shown when empty, as in the design.
 * - show_title     (string) used for the cover art alt text.
 *
 * The player is Buzzsprout's own hosted iframe, built from the two numeric
 * IDs (no raw embed HTML is stored or echoed); only the wrapper is themed.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_podcast_id = isset( $args['podcast_id'] ) ? preg_replace( '/[^0-9]/', '', (string) $args['podcast_id'] ) : '';
$fs_episode_id = isset( $args['episode_id'] ) ? preg_replace( '/[^0-9]/', '', (string) $args['episode_id'] ) : '';
$fs_title      = isset( $args['title'] ) ? $args['title'] : '';
$fs_summary    = isset( $args['summary'] ) ? $args['summary'] : '';
$fs_eyebrow    = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_image_url  = isset( $args['image_url'] ) ? $args['image_url'] : '';
$fs_show_title = isset( $args['show_title'] ) ? $args['show_title'] : '';

if ( '' === $fs_podcast_id || '' === $fs_episode_id || '' === trim( (string) $fs_title ) ) {
	return;
}

/* translators: %s: podcast show name. */
$fs_art_alt = sprintf( __( 'Cover art for %s.', 'focused-schools' ), $fs_show_title );

$fs_src = add_query_arg(
	array(
		'client_source' => 'small_player',
		'iframe'        => 'true',
	),
	'https://www.buzzsprout.com/' . rawurlencode( $fs_podcast_id ) . '/' . rawurlencode( $fs_episode_id )
);
?>
<article class="fs-podcast-latest">
	<div class="fs-podcast-latest__copy">
		<?php if ( $fs_eyebrow ) : ?>
			<p class="fs-podcast-latest__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
		<?php endif; ?>
		<h2 class="fs-podcast-latest__title"><?php echo esc_html( $fs_title ); ?></h2>
		<?php if ( $fs_summary ) : ?>
			<p class="fs-podcast-latest__summary"><?php echo esc_html( $fs_summary ); ?></p>
		<?php endif; ?>
		<div class="fs-podcast-latest__player">
			<iframe
				class="fs-podcast-latest__frame"
				src="<?php echo esc_url( $fs_src ); ?>"
				title="<?php echo esc_attr( $fs_title ); ?>"
				loading="lazy"
				width="100%"
				height="200"
				frameborder="0"
				scrolling="no"
			></iframe>
			<p class="fs-podcast-latest__note"><?php esc_html_e( 'Buzzsprout player', 'focused-schools' ); ?></p>
		</div>
	</div>
	<div class="fs-podcast-latest__media">
		<?php if ( $fs_image_url ) : ?>
			<img class="fs-podcast-latest__art" src="<?php echo esc_url( $fs_image_url ); ?>" alt="<?php echo esc_attr( $fs_art_alt ); ?>" loading="lazy" />
		<?php else : ?>
			<img class="fs-podcast-latest__mark" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg' ); ?>" alt="" />
		<?php endif; ?>
	</div>
</article>
