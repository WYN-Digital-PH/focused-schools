<?php
/**
 * Server render for focused-schools/podcast-videos.
 *
 * Reads the cached playlist. The helper never performs a live request, so a
 * page view never waits on YouTube, and no player iframe exists until a
 * reader presses play — the cards carry still thumbnails and real links.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_videos = function_exists( 'FocusedSchoolsCore\get_podcast_youtube_videos' )
	? FocusedSchoolsCore\get_podcast_youtube_videos()
	: array();

$fs_channel = function_exists( 'focused_schools_get_setting' ) ? (string) focused_schools_get_setting( 'youtube_url' ) : '';
$fs_anchor  = isset( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'watch';
?>
<section class="fs-podcast__section fs-podcast__watch" id="<?php echo esc_attr( $fs_anchor ); ?>" aria-labelledby="fs-podcast-watch-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-podcast__head">
			<div>
				<div class="fs-eyebrow-row">
					<span class="fs-rule fs-rule--rasp" aria-hidden="true"></span>
					<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				</div>
				<h2 id="fs-podcast-watch-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
			</div>
			<div class="fs-podcast__head-aside">
				<?php if ( ! empty( $attributes['intro'] ) ) : ?>
					<p class="fs-podcast__lead"><?php echo esc_html( $attributes['intro'] ); ?></p>
				<?php endif; ?>
				<?php
				if ( $fs_channel && ! empty( $attributes['ctaLabel'] ) ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'  => $attributes['ctaLabel'],
							'url'    => $fs_channel,
							'style'  => 'secondary',
							'target' => '_blank',
						)
					);
				}
				?>
			</div>
		</header>

		<?php if ( ! empty( $fs_videos ) ) : ?>
			<div class="fs-podcast__grid">
				<?php
				foreach ( $fs_videos as $fs_video ) :
					if ( empty( $fs_video['video_id'] ) ) {
						continue;
					}

					get_template_part(
						'template-parts/components/podcast-card',
						null,
						array(
							'title'      => isset( $fs_video['title'] ) ? $fs_video['title'] : '',
							'youtube_id' => $fs_video['video_id'],
							// The playlist API returns no runtime, so the card
							// shows the publish date rather than inventing one.
							'published'  => ! empty( $fs_video['publish_date'] )
								? date_i18n( get_option( 'date_format' ), strtotime( $fs_video['publish_date'] ) )
								: '',
						)
					);
				endforeach;
				?>
			</div>
		<?php else : ?>
			<p class="fs-podcast__empty"><?php echo esc_html( isset( $attributes['emptyText'] ) ? $attributes['emptyText'] : '' ); ?></p>
		<?php endif; ?>
	</div>
</section>
