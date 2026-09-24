<?php
/**
 * Template Name: Podcast
 *
 * Page template for the Podcast page. WordPress's native page-{slug}.php
 * template hierarchy applies this automatically to any page whose slug is
 * "podcast" — no numeric Page ID was provided for this task (unlike
 * About/Services/Team/Impact Stories), but none is needed: this mechanism
 * never references an ID, so the real page is never replaced, recreated,
 * or touched regardless.
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
 * Design reference: the approved mockup's /#/podcast route. See
 * docs/page-specs/podcast.md for the full spec.
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
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'eyebrow'    => $fs_show_title,
					'heading'    => __( 'Podcast', 'focused-schools' ),
					'subheading' => __( 'Conversations with real leaders, just like you, who come to work each and every day with one goal in mind: to ensure that every student has the opportunity to reach their full potential. Nothing is more important than that.', 'focused-schools' ),
					'image_url'  => $fs_img . 'student-video.jpg',
					'image_alt'  => __( 'Recording a Focused Schools podcast conversation.', 'focused-schools' ),
					'cta_label'  => __( 'Listen Now', 'focused-schools' ),
					'cta_url'    => '#listen',
					'cta2_label' => __( 'Watch Episodes', 'focused-schools' ),
					'cta2_url'   => '#watch',
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

			<?php
			/*
			 * Latest episode. Buzzsprout's own single-episode player, so
			 * listening starts without scrolling to the full list. The
			 * episode id is optional: with none set this section simply does
			 * not appear, and the full player below still carries every
			 * episode — the page never depends on it.
			 */
			$fs_latest_id = focused_schools_podcast_setting( 'podcast_latest_episode_id' );

			if ( $fs_buzzsprout_id && $fs_latest_id ) :
				$fs_latest_src = add_query_arg(
					array(
						'client_source' => 'small_player',
						'iframe'        => 'true',
					),
					'https://www.buzzsprout.com/' . rawurlencode( $fs_buzzsprout_id ) . '/' . rawurlencode( $fs_latest_id )
				);

				$fs_latest_player = sprintf(
					'<iframe class="fs-spotlight__frame" src="%1$s" title="%2$s" loading="lazy" width="100%%" height="200" frameborder="0" scrolling="no"></iframe>',
					esc_url( $fs_latest_src ),
					esc_attr__( 'Latest episode player', 'focused-schools' )
				);
				?>
				<section class="fs-podcast__section fs-podcast__latest" aria-labelledby="fs-podcast-latest-title">
					<div class="fs-container fs-container--shell">
						<div class="fs-eyebrow-row">
							<span class="fs-rule fs-rule--rasp" aria-hidden="true"></span>
							<p class="fs-eyebrow"><?php esc_html_e( 'Latest episode', 'focused-schools' ); ?></p>
						</div>
						<?php
						get_template_part(
							'template-parts/components/episode-spotlight',
							null,
							array(
								'eyebrow'  => $fs_show_title,
								'title'    => focused_schools_podcast_setting( 'podcast_latest_episode_title' ) ? focused_schools_podcast_setting( 'podcast_latest_episode_title' ) : __( 'Start with the latest conversation.', 'focused-schools' ),
								'body'     => focused_schools_podcast_setting( 'podcast_latest_episode_summary' ),
								'mark_url' => $fs_img . 'mark-white.svg',
								'inner'    => $fs_latest_player,
							)
						);
						?>
					</div>
				</section>
				<?php
			endif;
			?>

			<section class="fs-podcast__section fs-podcast__listen" id="listen" aria-labelledby="fs-podcast-listen-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-podcast__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'Listen', 'focused-schools' ); ?></p>
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
									'style'  => 'text',
									'target' => '_blank',
								)
							);
						}
						?>
					</header>

					<?php
					/*
					 * Buzzsprout's own embed, captured so the design's shell
					 * can be drawn around it. The player markup itself is
					 * untouched — the shell only frames it.
					 */
					ob_start();
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
					$fs_player = ob_get_clean();

					if ( $fs_buzzsprout_id ) {
						get_template_part(
							'template-parts/components/embed-shell',
							null,
							array(
								'title'    => $fs_show_title,
								'kicker'   => __( 'Hosted on Buzzsprout', 'focused-schools' ),
								'mark_url' => $fs_img . 'mark-white.svg',
								'inner'    => $fs_player,
							)
						);
					} else {
						echo $fs_player; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the component's own escaped empty-state markup.
					}
					?>
				</div>
			</section>

			<section class="fs-podcast__section fs-podcast__watch" id="watch" aria-labelledby="fs-podcast-watch-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-podcast__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'Watch', 'focused-schools' ); ?></p>
							<h2 id="fs-podcast-watch-title"><?php esc_html_e( 'Prefer to watch our podcasts?', 'focused-schools' ); ?></h2>
							<p class="fs-podcast__lead"><?php esc_html_e( 'Every conversation is recorded on video. Thumbnails load instantly; the YouTube player only starts once you press play.', 'focused-schools' ); ?></p>
						</div>
						<?php
						if ( $fs_youtube_url ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'  => __( 'Visit Our YouTube Channel', 'focused-schools' ),
									'url'    => $fs_youtube_url,
									'style'  => 'text',
									'target' => '_blank',
								)
							);
						}
						?>
					</header>

					<?php if ( ! empty( $fs_videos ) ) : ?>
						<div class="fs-card-grid">
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
										'duration'   => ! empty( $fs_video['publish_date'] )
											? date_i18n( get_option( 'date_format' ), strtotime( $fs_video['publish_date'] ) )
											: '',
									)
								);
							endforeach;
							?>
						</div>
					<?php else : ?>
						<p class="fs-podcast__empty"><?php esc_html_e( 'Video episodes will appear here once the YouTube playlist integration is enabled in Site Settings.', 'focused-schools' ); ?></p>
					<?php endif; ?>
				</div>
			</section>

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'eyebrow'     => __( 'Never miss an episode', 'focused-schools' ),
					'heading'     => __( 'Subscribe wherever you listen.', 'focused-schools' ),
					'description' => __( 'New conversations with district and school leaders, published regularly.', 'focused-schools' ),
					'cta_label'   => $fs_buzzsprout_url ? __( 'Open in Buzzsprout', 'focused-schools' ) : __( 'Contact Us', 'focused-schools' ),
					'cta_url'     => $fs_buzzsprout_url ? $fs_buzzsprout_url : home_url( '/contact/' ),
				)
			);
			?>
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
