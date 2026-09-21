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
 * No fs_podcast post type exists yet (see docs/architecture.md §3.5), so
 * the episodes grid below loops over a defined array rather than a live
 * query — ready to swap for one without changing podcast-card.php. Episode
 * data is clearly-marked placeholder; replace with real Buzzsprout embed
 * codes / YouTube video IDs before launch.
 *
 * See docs/page-specs/podcast.md for the full spec.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

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
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'heading'    => get_the_title() ? get_the_title() : __( 'The Podcast', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( 'Conversations with the leaders doing the work — practical ideas for districts and schools, one episode at a time.', 'focused-schools' ),
					'cta_label'  => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_label' ) : '',
					'cta_url'    => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'cta_url' ) : '',
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
			?>

			<section class="fs-podcast__section fs-container fs-container--wide" aria-labelledby="fs-podcast-heading">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'heading'       => __( 'Episodes', 'focused-schools' ),
						'heading_level' => 2,
						'heading_id'    => 'fs-podcast-heading',
					)
				);

				/*
				 * TODO: placeholder episode data — no fs_podcast post type
				 * or feed exists yet (docs/architecture.md §3.5). Replace
				 * with real Buzzsprout embed codes / YouTube video IDs, or
				 * a real query, before launch. Looped rather than
				 * hand-duplicated per episode, so wiring a real source
				 * later only touches this array.
				 */
				$fs_episodes = array(
					array(
						'title'          => __( 'TODO: episode title — audio (Buzzsprout)', 'focused-schools' ),
						'description'    => __( 'TODO: episode description.', 'focused-schools' ),
						'episode_number' => 1,
						'duration'       => 'TODO: 00:00',
						'embed_html'     => '<iframe src="https://www.buzzsprout.com/TODO/episodes/TODO.js" width="100%" height="200" frameborder="0" loading="lazy" title="TODO: episode title"></iframe>',
					),
					array(
						'title'          => __( 'TODO: episode title — video (YouTube)', 'focused-schools' ),
						'description'    => __( 'TODO: episode description.', 'focused-schools' ),
						'episode_number' => 2,
						'duration'       => 'TODO: 00:00',
						'youtube_id'     => 'TODOTODOTOD',
					),
				);

				if ( ! empty( $fs_episodes ) ) :
					?>
					<div class="fs-card-grid">
						<?php
						foreach ( $fs_episodes as $fs_episode ) :
							get_template_part( 'template-parts/components/podcast-card', null, $fs_episode );
						endforeach;
						?>
					</div>
					<?php
				else :
					?>
					<p class="fs-podcast__empty"><?php esc_html_e( 'New episodes are on the way — check back soon.', 'focused-schools' ); ?></p>
					<?php
				endif;
				?>
			</section>

			<?php
			get_template_part(
				'template-parts/components/cta-banner',
				null,
				array(
					'heading'   => __( 'Never miss an episode', 'focused-schools' ),
					'cta_label' => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_label' )
						? focused_schools_get_setting( 'cta_label' )
						: __( 'Contact Us', 'focused-schools' ),
					'cta_url'   => function_exists( 'focused_schools_get_setting' ) && focused_schools_get_setting( 'cta_url' )
						? focused_schools_get_setting( 'cta_url' )
						: '#',
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
