<?php
/**
 * Component: Podcast video card.
 *
 * Contract ($args):
 * - title         (string, required)
 * - youtube_id    (string, required) bare 11-character YouTube video ID.
 * - date          (string) already-formatted publish date.
 * - eyebrow       (string) optional small label above the title (episode
 *                 number/series). Omitted, not blanked, when empty.
 * - duration      (string) optional. The badge is omitted, not zeroed, when
 *                 the source has no duration — the cached playlist helper
 *                 doesn't supply one today.
 * - thumbnail_url (string) optional https thumbnail; falls back to YouTube's
 *                 predictable hqdefault URL.
 *
 * The card never loads a YouTube iframe on page load: the facade (thumbnail
 * + raspberry play button) is swapped for a youtube-nocookie iframe by
 * assets/js/components/podcast-video.js only after a click, inside the same
 * 16:9 box so nothing shifts. Without JS the "Watch" link and the YouTube
 * tile are plain links to the video, so the card always works.
 *
 * Only the Podcast page uses this card; audio is Buzzsprout's own player
 * (podcast-player.php), not a card.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_title      = isset( $args['title'] ) ? $args['title'] : '';
$fs_youtube_id = isset( $args['youtube_id'] ) ? $args['youtube_id'] : '';
$fs_date       = isset( $args['date'] ) ? $args['date'] : '';
$fs_eyebrow    = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_duration   = isset( $args['duration'] ) ? $args['duration'] : '';
$fs_thumb      = isset( $args['thumbnail_url'] ) ? $args['thumbnail_url'] : '';

// YouTube video IDs are exactly 11 characters of [A-Za-z0-9_-]; reject
// anything else rather than build a thumbnail/embed URL from bad input.
if ( '' === trim( (string) $fs_title ) || ! preg_match( '/^[A-Za-z0-9_-]{11}$/', (string) $fs_youtube_id ) ) {
	return;
}

if ( '' === $fs_thumb || 0 !== strpos( $fs_thumb, 'https://' ) ) {
	$fs_thumb = 'https://i.ytimg.com/vi/' . $fs_youtube_id . '/hqdefault.jpg';
}

$fs_watch_url = 'https://www.youtube.com/watch?v=' . $fs_youtube_id;
?>
<article class="fs-podcast-card">
	<div
		class="fs-podcast-card__video"
		data-youtube-id="<?php echo esc_attr( $fs_youtube_id ); ?>"
		data-youtube-title="<?php echo esc_attr( $fs_title ); ?>"
	>
		<button
			type="button"
			class="fs-podcast-card__video-facade"
			aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title. */ __( 'Play video: %s', 'focused-schools' ), $fs_title ) ); ?>"
		>
			<img class="fs-podcast-card__video-thumb" src="<?php echo esc_url( $fs_thumb ); ?>" alt="" loading="lazy" />
			<span class="fs-podcast-card__play" aria-hidden="true">&#9654;</span>
		</button>
		<?php if ( $fs_duration ) : ?>
			<span class="fs-podcast-card__duration" aria-hidden="true"><?php echo esc_html( $fs_duration ); ?></span>
		<?php endif; ?>
	</div>
	<div class="fs-podcast-card__body">
		<?php if ( $fs_eyebrow ) : ?>
			<p class="fs-podcast-card__eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
		<?php endif; ?>
		<h3 class="fs-podcast-card__title"><?php echo esc_html( $fs_title ); ?></h3>
		<?php if ( $fs_date ) : ?>
			<p class="fs-podcast-card__date"><?php echo esc_html( $fs_date ); ?></p>
		<?php endif; ?>
		<div class="fs-podcast-card__foot">
			<a class="fs-podcast-card__watch" href="<?php echo esc_url( $fs_watch_url ); ?>" data-fs-video-play target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Watch episode', 'focused-schools' ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
			<a
				class="fs-podcast-card__yt"
				href="<?php echo esc_url( $fs_watch_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title. */ __( 'Open “%s” on YouTube', 'focused-schools' ), $fs_title ) ); ?>"
			>&#9654;</a>
		</div>
	</div>
</article>
