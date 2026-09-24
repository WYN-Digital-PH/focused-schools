<?php
/**
 * Template Name: Podcast
 *
 * Page template for the Podcast page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "podcast" — the Page ID is never referenced or hardcoded, so the real page
 * is never replaced, recreated, or touched regardless.
 *
 * Elementor coexistence: same as the other page templates — if
 * `_elementor_edit_mode` === 'builder' on this page, only its filtered
 * content renders; otherwise the sections below render.
 *
 * Data sources, both real (this template holds no episode data of its own):
 *  - Audio: the Buzzsprout hosted player, built from the Buzzsprout podcast
 *    ID in Site Settings → Podcast. Buzzsprout stays the audio source of
 *    record per docs/AGENTS.md.
 *  - Video: FocusedSchoolsCore\get_podcast_youtube_videos(), the
 *    feature-flagged, cache-only YouTube playlist helper. Returns an empty
 *    array when the flag is off or nothing has been fetched, which this
 *    template treats as "no episodes" rather than an error.
 *
 * Design source: `Focused Schools Podcast.dc.html`. Sections in order: hero,
 * subscribe rail, latest episode (newest item of the Buzzsprout RSS feed via
 * FocusedSchoolsCore\get_podcast_latest_episode(); hidden until fetched — see
 * docs/page-specs/podcast.md §10), Buzzsprout list, video grid. The design
 * has no closing CTA banner.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! function_exists( 'focused_schools_podcast_setting' ) ) {
	/**
	 * Read a Site Settings value, tolerating the plugin being deactivated
	 * and treating a saved-but-empty value the same as unset.
	 *
	 * @param string $key      Setting key.
	 * @param string $fallback Value when unset, empty, or the plugin is inactive.
	 * @return string
	 */
	function focused_schools_podcast_setting( $key, $fallback = '' ) {
		if ( ! function_exists( 'focused_schools_get_setting' ) ) {
			return $fallback;
		}

		$fs_value = (string) focused_schools_get_setting( $key, $fallback );

		return '' !== $fs_value ? $fs_value : $fallback;
	}
}

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) :
			?>
			<main id="content">
				<?php the_content(); ?>
			</main>
			<?php
			continue;
		endif;

		$fs_img            = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
		$fs_show_title     = focused_schools_podcast_setting( 'podcast_title', __( 'Conversations on Learning', 'focused-schools' ) );
		$fs_buzzsprout_id  = focused_schools_podcast_setting( 'podcast_buzzsprout_id' );
		$fs_buzzsprout_url = focused_schools_podcast_setting( 'podcast_buzzsprout_url' );
		$fs_youtube_url    = focused_schools_podcast_setting( 'youtube_url' );

		// Cache-only helper: never triggers a live HTTP request on page load.
		$fs_videos = function_exists( 'FocusedSchoolsCore\\get_podcast_youtube_videos' )
			? FocusedSchoolsCore\get_podcast_youtube_videos()
			: array();

		// The design paginates the video grid at six, with a "Load more" button.
		$fs_videos        = array_values(
			array_filter(
				$fs_videos,
				static function ( $fs_video ) {
					return ! empty( $fs_video['video_id'] );
				}
			)
		);
		$fs_video_visible = 6;

		// Cache-only Buzzsprout RSS helper; null hides the Latest Episode panel.
		$fs_latest = function_exists( 'FocusedSchoolsCore\\get_podcast_latest_episode' )
			? FocusedSchoolsCore\get_podcast_latest_episode()
			: null;

		$fs_latest_eyebrow = array();
		if ( $fs_latest ) {
			if ( '' !== $fs_latest['episode_number'] ) {
				/* translators: %s: episode number. */
				$fs_latest_eyebrow[] = sprintf( __( 'Episode %s', 'focused-schools' ), $fs_latest['episode_number'] );
			}
			if ( '' !== $fs_latest['publish_date'] ) {
				$fs_latest_eyebrow[] = date_i18n( get_option( 'date_format' ), strtotime( $fs_latest['publish_date'] ) );
			}
			if ( '' !== $fs_latest['duration'] ) {
				$fs_latest_eyebrow[] = $fs_latest['duration'];
			}
		}
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'accent'       => 'raspberry',
					'eyebrow'      => $fs_show_title,
					'heading'      => __( 'Podcast', 'focused-schools' ),
					'heading_size' => 56,
					'slab_width'   => 700,
					'subheading'   => __( 'Conversations with real leaders, just like you, who come to work each and every day with one goal in mind: to ensure that every student has the opportunity to reach their full potential. Nothing is more important than that.', 'focused-schools' ),
					'image_url'    => $fs_img . 'retreat-1.jpg',
					'image_alt'    => __( 'A school leader speaking in a recorded conversation.', 'focused-schools' ),
					'cta_label'    => __( 'Listen Now', 'focused-schools' ),
					'cta_url'      => '#listen',
					'cta2_label'   => __( 'Watch Episodes', 'focused-schools' ),
					'cta2_url'     => '#watch',
					'cta2_style'   => 'ghost',
				)
			);

			// Preserve any Gutenberg/native content an editor has added
			// directly to this page, rather than discarding it.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-podcast__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;

			get_template_part(
				'template-parts/components/podcast-subscribe',
				null,
				array(
					'label' => __( 'Subscribe anywhere', 'focused-schools' ),
					'links' => array(
						array(
							'label' => __( 'Apple Podcasts', 'focused-schools' ),
							'url'   => focused_schools_podcast_setting( 'podcast_apple_url' ),
							'kind'  => __( 'Audio', 'focused-schools' ),
						),
						array(
							'label' => __( 'Spotify', 'focused-schools' ),
							'url'   => focused_schools_podcast_setting( 'podcast_spotify_url' ),
							'kind'  => __( 'Audio', 'focused-schools' ),
						),
						array(
							'label' => __( 'Buzzsprout', 'focused-schools' ),
							'url'   => $fs_buzzsprout_url,
							'kind'  => __( 'All episodes', 'focused-schools' ),
						),
						array(
							'label' => __( 'YouTube', 'focused-schools' ),
							'url'   => $fs_youtube_url,
							'kind'  => __( 'Video', 'focused-schools' ),
						),
					),
				)
			);
		?>

			<?php if ( $fs_latest ) : ?>
				<section class="fs-podcast__section fs-podcast__latest" aria-labelledby="fs-podcast-latest-label">
					<div class="fs-container fs-container--shell">
						<div class="fs-podcast__eyebrow-row">
							<span class="fs-rule fs-rule--rasp" aria-hidden="true"></span>
							<p class="fs-podcast__eyebrow" id="fs-podcast-latest-label"><?php esc_html_e( 'Latest episode', 'focused-schools' ); ?></p>
						</div>
						<?php
						get_template_part(
							'template-parts/components/podcast-latest',
							null,
							array(
								'podcast_id' => $fs_latest['podcast_id'],
								'episode_id' => $fs_latest['episode_id'],
								'title'      => $fs_latest['title'],
								'summary'    => $fs_latest['summary'],
								'eyebrow'    => implode( ' · ', $fs_latest_eyebrow ),
								'image_url'  => $fs_latest['image_url'],
								'show_title' => $fs_show_title,
							)
						);
						?>
					</div>
				</section>
			<?php endif; ?>

			<section class="fs-podcast__section fs-podcast__listen" id="listen" aria-labelledby="fs-podcast-listen-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-podcast__head">
						<div>
							<p class="fs-podcast__eyebrow"><?php esc_html_e( 'Listen', 'focused-schools' ); ?></p>
							<h2 id="fs-podcast-listen-title"><?php esc_html_e( 'Every episode, in one place.', 'focused-schools' ); ?></h2>
						</div>
						<?php
						if ( $fs_buzzsprout_url ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'  => __( 'Open in Buzzsprout', 'focused-schools' ),
									'url'    => $fs_buzzsprout_url,
									'style'  => 'secondary',
									'target' => '_blank',
								)
							);
						}
						?>
					</header>

					<div class="fs-podcast__shell">
						<div class="fs-podcast__shell-head">
							<span class="fs-podcast__shell-mark" aria-hidden="true">
								<img src="<?php echo esc_url( $fs_img . 'mark-white.svg' ); ?>" alt="" />
							</span>
							<div>
								<strong class="fs-podcast__show"><?php echo esc_html( $fs_show_title ); ?></strong>
								<span class="fs-podcast__hosted-by"><?php esc_html_e( 'Hosted on Buzzsprout', 'focused-schools' ); ?></span>
							</div>
						</div>
						<?php
						get_template_part(
							'template-parts/components/podcast-player',
							null,
							array(
								'podcast_id' => $fs_buzzsprout_id,
								/* translators: %s: podcast show name. */
								'title'      => sprintf( __( '%s episodes', 'focused-schools' ), $fs_show_title ),
								'empty_text' => __( 'Episodes will appear here once the Buzzsprout podcast ID is set in Site Settings → Podcast.', 'focused-schools' ),
							)
						);
						?>
					</div>
				</div>
			</section>

			<section class="fs-podcast__section fs-podcast__watch" id="watch" aria-labelledby="fs-podcast-watch-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-podcast__watch-head">
						<div>
							<div class="fs-podcast__eyebrow-row">
								<span class="fs-rule fs-rule--rasp" aria-hidden="true"></span>
								<p class="fs-podcast__eyebrow"><?php esc_html_e( 'Watch', 'focused-schools' ); ?></p>
							</div>
							<h2 id="fs-podcast-watch-title">
								<?php esc_html_e( 'Prefer to watch', 'focused-schools' ); ?>
								<strong><?php esc_html_e( 'our podcasts?', 'focused-schools' ); ?></strong>
							</h2>
						</div>
						<div class="fs-podcast__watch-aside">
							<p class="fs-podcast__lead"><?php esc_html_e( 'Every conversation is recorded on video. Thumbnails load instantly; the YouTube player only starts once you press play.', 'focused-schools' ); ?></p>
							<?php
							if ( $fs_youtube_url ) {
								get_template_part(
									'template-parts/components/button',
									null,
									array(
										'label'  => __( 'Visit our YouTube Channel', 'focused-schools' ),
										'url'    => $fs_youtube_url,
										'style'  => 'secondary',
										'target' => '_blank',
									)
								);
							}
							?>
						</div>
					</header>

					<?php if ( ! empty( $fs_videos ) ) : ?>
						<div class="fs-podcast__video-grid" id="fs-podcast-video-grid">
							<?php
							foreach ( $fs_videos as $fs_video_index => $fs_video ) :
								?>
								<div<?php echo $fs_video_index >= $fs_video_visible ? ' hidden' : ''; ?>>
									<?php
									get_template_part(
										'template-parts/components/podcast-card',
										null,
										array(
											'title'      => isset( $fs_video['title'] ) ? $fs_video['title'] : '',
											'youtube_id' => $fs_video['video_id'],
											'thumbnail_url' => isset( $fs_video['thumbnail_url'] ) ? $fs_video['thumbnail_url'] : '',
											'date'       => ! empty( $fs_video['publish_date'] )
												? date_i18n( get_option( 'date_format' ), strtotime( $fs_video['publish_date'] ) )
												: '',
										)
									);
									?>
								</div>
								<?php
							endforeach;
							?>
						</div>

						<?php if ( count( $fs_videos ) > $fs_video_visible ) : ?>
							<div class="fs-loadmore">
								<button class="fs-btn fs-btn--secondary" type="button" data-fs-team-load-more aria-controls="fs-podcast-video-grid">
									<?php esc_html_e( 'Load more episodes', 'focused-schools' ); ?>
									<span aria-hidden="true">&darr;</span>
								</button>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="fs-podcast__empty">
							<p><?php esc_html_e( 'Video episodes will appear here once the YouTube playlist integration is enabled in Site Settings.', 'focused-schools' ); ?></p>
							<?php if ( $fs_youtube_url ) : ?>
								<a class="fs-text-link" href="<?php echo esc_url( $fs_youtube_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Watch on our YouTube channel', 'focused-schools' ); ?>
									<span aria-hidden="true">&rarr;</span>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</main>
		<?php
	endwhile;
else :
	?>
	<main id="content" class="fs-container">
		<p><?php esc_html_e( 'Nothing found.', 'focused-schools' ); ?></p>
	</main>
	<?php
endif;

get_footer();
