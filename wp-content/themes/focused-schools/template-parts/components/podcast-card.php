<?php
/**
 * Component: Podcast Card.
 *
 * Contract ($args):
 * - title          (string, required)
 * - description    (string)
 * - embed_html     (string) e.g. a Buzzsprout <iframe> embed — lightweight
 *                  (an audio widget), so this renders immediately.
 * - youtube_id     (string) bare YouTube video ID. Renders a lazy-load
 *                  facade (thumbnail + play button) instead of an iframe;
 *                  the real iframe is only created by
 *                  assets/js/components/podcast-video.js after a click, to
 *                  avoid loading a heavy YouTube iframe on initial page
 *                  load. See docs/page-specs/podcast.md §4.
 * - episode_number (string|int)
 * - duration       (string)
 * - cta_label      (string)
 * - cta_url        (string)
 *
 * Generic/args-driven: no fs_podcast post type exists yet
 * (see docs/architecture.md §3.5). Ready to wire to real data once that
 * module is built.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_title       = isset( $args['title'] ) ? $args['title'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_embed       = isset( $args['embed_html'] ) ? $args['embed_html'] : '';
$fs_youtube_id  = isset( $args['youtube_id'] ) ? $args['youtube_id'] : '';
$fs_episode     = isset( $args['episode_number'] ) ? $args['episode_number'] : '';
$fs_duration    = isset( $args['duration'] ) ? $args['duration'] : '';
$fs_cta_label   = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url     = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_title ) ) {
	return;
}

// YouTube video IDs are exactly 11 characters of [A-Za-z0-9_-]; reject
// anything else rather than build a thumbnail/embed URL from bad input.
if ( $fs_youtube_id && ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $fs_youtube_id ) ) {
	$fs_youtube_id = '';
}

// Allow common podcast-embed iframe attributes on top of the standard post
// allowlist, rather than trusting embed_html verbatim.
$fs_allowed_embed_html = array_merge(
	wp_kses_allowed_html( 'post' ),
	array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'scrolling'       => true,
			'seamless'        => true,
			'allow'           => true,
			'allowfullscreen' => true,
			'title'           => true,
			'loading'         => true,
		),
	)
);
?>
<article class="fs-card fs-podcast-card">
	<div class="fs-card__body">
		<?php if ( $fs_episode ) : ?>
			<p class="fs-podcast-card__episode">
				<?php
				/* translators: %s: episode number. */
				echo esc_html( sprintf( __( 'Episode %s', 'focused-schools' ), $fs_episode ) );
				?>
			</p>
		<?php endif; ?>
		<h3 class="fs-card__heading"><?php echo esc_html( $fs_title ); ?></h3>
		<?php if ( $fs_duration ) : ?>
			<p class="fs-podcast-card__duration"><?php echo esc_html( $fs_duration ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_description ) : ?>
			<div class="fs-card__excerpt"><?php echo esc_html( $fs_description ); ?></div>
		<?php endif; ?>
		<?php if ( $fs_youtube_id ) : ?>
			<div
				class="fs-podcast-card__video"
				data-youtube-id="<?php echo esc_attr( $fs_youtube_id ); ?>"
				data-youtube-title="<?php echo esc_attr( $fs_title ); ?>"
			>
				<button
					type="button"
					class="fs-podcast-card__video-facade"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %s: episode title. */ __( 'Play video: %s', 'focused-schools' ), $fs_title ) ); ?>"
				>
					<img
						src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $fs_youtube_id . '/hqdefault.jpg' ); ?>"
						alt=""
						loading="lazy"
						class="fs-podcast-card__video-thumb"
					/>
					<span class="fs-podcast-card__play-icon" aria-hidden="true"></span>
				</button>
			</div>
		<?php endif; ?>
		<?php if ( $fs_embed ) : ?>
			<div class="fs-podcast-card__embed"><?php echo wp_kses( $fs_embed, $fs_allowed_embed_html ); ?></div>
		<?php endif; ?>
		<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
				)
			);
			?>
		<?php endif; ?>
	</div>
</article>
