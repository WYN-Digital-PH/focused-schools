<?php
/**
 * Component: Podcast video card.
 *
 * Contract ($args):
 * - title          (string, required)
 * - youtube_id     (string) 11-character video id
 * - episode_number (string)
 * - duration       (string) shown as a badge over the thumbnail
 * - published      (string) display date under the title
 * - description    (string)
 *
 * The card leads with the thumbnail, as the approved design draws it: media
 * on top, then the episode label, title, date and actions.
 *
 * No iframe is rendered. The thumbnail is a still image and the play control
 * is a link to YouTube, so the page makes no request to YouTube's player
 * until a reader asks for one. podcast-video.js upgrades the link into an
 * in-card player where it can.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_title       = isset( $args['title'] ) ? $args['title'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_youtube_id  = isset( $args['youtube_id'] ) ? $args['youtube_id'] : '';
$fs_episode     = isset( $args['episode_number'] ) ? $args['episode_number'] : '';
$fs_duration    = isset( $args['duration'] ) ? $args['duration'] : '';
$fs_published   = isset( $args['published'] ) ? $args['published'] : '';

if ( '' === trim( (string) $fs_title ) ) {
	return;
}

// YouTube video IDs are exactly 11 characters of [A-Za-z0-9_-]; reject
// anything else rather than build a thumbnail/watch URL from bad input.
if ( $fs_youtube_id && ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $fs_youtube_id ) ) {
	$fs_youtube_id = '';
}

$fs_watch = $fs_youtube_id
	? 'https://www.youtube.com/watch?v=' . rawurlencode( $fs_youtube_id )
	: '';
?>
<article class="fs-card fs-podcast-card"<?php echo $fs_youtube_id ? ' data-video="' . esc_attr( $fs_youtube_id ) . '"' : ''; ?>>
	<?php if ( $fs_youtube_id ) : ?>
		<div class="fs-video__shell" data-video-shell>
			<img
				class="fs-video__photo"
				src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $fs_youtube_id . '/hqdefault.jpg' ); ?>"
				alt=""
				width="1280"
				height="720"
				loading="lazy"
			/>
			<a
				class="fs-video__facade"
				href="<?php echo esc_url( $fs_watch ); ?>"
				data-video-play
				target="_blank"
				rel="noopener"
				aria-label="<?php echo esc_attr( sprintf( /* translators: %s: episode title. */ __( 'Watch: %s', 'focused-schools' ), $fs_title ) ); ?>"
			>
				<span class="fs-video__btn" aria-hidden="true">&#9654;</span>
			</a>
			<span class="fs-video__dim" aria-hidden="true"></span>
			<?php if ( $fs_duration ) : ?>
				<span class="fs-video__dur" aria-hidden="true"><?php echo esc_html( $fs_duration ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="fs-card__body">
		<p class="fs-eyebrow fs-eyebrow--rasp">
			<?php
			if ( $fs_episode ) {
				/* translators: %s: episode number. */
				echo esc_html( sprintf( __( 'Episode %s', 'focused-schools' ), $fs_episode ) );
			} else {
				esc_html_e( 'Video', 'focused-schools' );
			}
			?>
		</p>

		<h3 class="fs-video__title"><?php echo esc_html( $fs_title ); ?></h3>

		<?php if ( $fs_published ) : ?>
			<p class="fs-video__date"><?php echo esc_html( $fs_published ); ?></p>
		<?php endif; ?>

		<?php if ( $fs_description ) : ?>
			<div class="fs-card__excerpt"><?php echo esc_html( $fs_description ); ?></div>
		<?php endif; ?>

		<?php if ( $fs_watch ) : ?>
			<div class="fs-card__foot">
				<a class="fs-link fs-link--rasp" href="<?php echo esc_url( $fs_watch ); ?>" data-video-play target="_blank" rel="noopener">
					<?php esc_html_e( 'Watch', 'focused-schools' ); ?>
					<span class="fs-link__arrow" aria-hidden="true">&rarr;</span>
				</a>
				<a
					class="fs-icon-tile fs-icon-tile--rasp"
					href="<?php echo esc_url( $fs_watch ); ?>"
					target="_blank"
					rel="noopener"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %s: episode title. */ __( 'Open “%s” on YouTube', 'focused-schools' ), $fs_title ) ); ?>"
				><span aria-hidden="true">&#9654;</span></a>
			</div>
		<?php endif; ?>
	</div>
</article>
