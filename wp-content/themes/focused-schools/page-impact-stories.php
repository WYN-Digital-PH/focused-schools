<?php
/**
 * Template Name: Impact Stories
 *
 * Landing page for Impact Stories. WordPress's native page-{slug}.php
 * hierarchy applies this to the Page whose slug is "impact-stories" — the ID
 * (Page 3785 in production) is never referenced, so the real Page is never
 * replaced, recreated or re-slugged.
 *
 * Two sources, one grid. Approved legacy Impact Story **Pages** keep their
 * post type, slugs and URLs untouched: they are opted in only by the
 * `_fs_legacy_impact_story` meta flag the plugin's Legacy Page Bridge writes,
 * which is additive and reversible. Nothing here migrates, rewrites or
 * re-types legacy content — see docs/migration-qa-rules.md §9.
 *
 * New fs_impact_story records publish into the listing automatically: the
 * query below is live, so nothing on this page is ever hand-maintained.
 *
 * Design source: docs/page-specs/impact-stories.md (approved mockup).
 *
 * Elementor coexistence: if `_elementor_edit_mode` === 'builder' on this Page,
 * only its own filtered content renders.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! function_exists( 'focused_schools_story_filter_terms' ) ) {
	/**
	 * Meta values that actually exist on published stories, for the chips.
	 *
	 * An option that would return nothing is never offered, so a chip can
	 * never lead to a dead grid.
	 *
	 * @param string $meta_key Meta key to collect.
	 * @return string[]
	 */
	function focused_schools_story_filter_terms( $meta_key ) {
		$stories = get_posts(
			array(
				'post_type'      => 'fs_impact_story',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		$values = array();

		foreach ( $stories as $story_id ) {
			$value = trim( (string) get_post_meta( $story_id, $meta_key, true ) );

			if ( '' !== $value ) {
				$values[ $value ] = true;
			}
		}

		$values = array_keys( $values );
		sort( $values );

		return $values;
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

		$fs_img = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';

		// Server-rendered on first paint, so a filtered view is shareable,
		// back-button safe and works with JavaScript off.
		$fs_state_filter = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only public filter, changes no state.

		/*
		 * Deliberately `story_year`, not `year`: `year` is a reserved
		 * WordPress query var for date archives, so `?year=2025` on a Page
		 * URL resolves as a date archive and 404s. The spec's example URL
		 * uses `year`; this is the one place it cannot be taken literally.
		 */
		$fs_year_filter = isset( $_GET['story_year'] ) ? sanitize_text_field( wp_unslash( $_GET['story_year'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only public filter, changes no state.

		$fs_meta_query = array();

		if ( '' !== $fs_state_filter ) {
			$fs_meta_query[] = array(
				'key'   => '_fs_impact_story_state',
				'value' => $fs_state_filter,
			);
		}

		if ( '' !== $fs_year_filter ) {
			$fs_meta_query[] = array(
				'key'   => '_fs_impact_story_year',
				'value' => $fs_year_filter,
			);
		}

		$fs_cpt_args = array(
			'post_type'      => 'fs_impact_story',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		);

		if ( $fs_meta_query ) {
			$fs_meta_query['relation'] = 'AND';
			$fs_cpt_args['meta_query'] = $fs_meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- small, admin-managed set; acceptable at this scale.
		}

		$fs_cpt_query = new WP_Query( $fs_cpt_args );

		/*
		 * Legacy Pages carry no structured state or year, so a narrowing
		 * filter correctly excludes them rather than showing them under a
		 * term they do not have. The empty state says so in plain language.
		 */
		$fs_legacy = array();

		if ( '' === $fs_state_filter && '' === $fs_year_filter ) {
			$fs_legacy_query = new WP_Query(
				array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
					'meta_key'       => '_fs_legacy_impact_story', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small, admin-managed set.
					'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- see above.
				)
			);

			$fs_legacy = $fs_legacy_query->posts;
		}

		$fs_stories = array_merge( $fs_cpt_query->posts, $fs_legacy );

		// Year DESC, then post date, so the two sources interleave
		// chronologically instead of clumping by source.
		usort(
			$fs_stories,
			static function ( $fs_a, $fs_b ) {
				$fs_year_a = (int) get_post_meta( $fs_a->ID, '_fs_impact_story_year', true );
				$fs_year_b = (int) get_post_meta( $fs_b->ID, '_fs_impact_story_year', true );

				if ( $fs_year_a !== $fs_year_b ) {
					return $fs_year_b <=> $fs_year_a;
				}

				return strtotime( $fs_b->post_date ) <=> strtotime( $fs_a->post_date );
			}
		);

		/*
		 * Newest Featured story only. Two or more Featured: newest wins and
		 * the rest stay ordinary cards. It is not removed from the grid.
		 * Legacy Pages can never reach this slot.
		 */
		$fs_featured = null;

		foreach ( $fs_stories as $fs_story ) {
			if ( 'fs_impact_story' === $fs_story->post_type && get_post_meta( $fs_story->ID, '_fs_impact_story_featured', true ) ) {
				$fs_featured = $fs_story;
				break;
			}
		}

		$fs_total      = count( $fs_stories );
		$fs_per_page   = 9;
		$fs_showing    = min( $fs_per_page, $fs_total );
		$fs_has_filter = '' !== $fs_state_filter || '' !== $fs_year_filter;
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'eyebrow'    => __( 'Impact stories', 'focused-schools' ),
					'heading'    => __( 'The measure of our work is what changed because of it.', 'focused-schools' ),
					'subheading' => __( 'Districts and schools that partnered with us, and what actually moved.', 'focused-schools' ),
					'image_url'  => $fs_img . 'retreat-1.jpg',
					'image_alt'  => __( 'District leaders reviewing progress together.', 'focused-schools' ),
					'cta_label'  => __( 'Browse Stories', 'focused-schools' ),
					'cta_url'    => '#stories',
				)
			);

			// Preserve any Gutenberg/native content an editor added directly.
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-impact-stories__intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;

			// Omitted entirely when nothing is Featured, and the grid moves up.
			if ( $fs_featured && ! $fs_has_filter ) {
				get_template_part( 'template-parts/components/story-spotlight', null, array( 'post' => $fs_featured ) );
			}
			?>

			<section class="fs-stories" id="stories" aria-labelledby="fs-stories-title">
				<div class="fs-container fs-container--shell">
					<header class="fs-stories__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'All stories', 'focused-schools' ); ?></p>
							<h2 id="fs-stories-title"><?php esc_html_e( 'Browse by state or year.', 'focused-schools' ); ?></h2>
						</div>
						<?php if ( $fs_total ) : ?>
							<p class="fs-stories__count" aria-live="polite" data-fs-story-count>
								<?php
								printf(
									/* translators: 1: number of stories shown, 2: total number of stories. */
									esc_html__( 'Showing %1$d of %2$d', 'focused-schools' ),
									(int) $fs_showing,
									(int) $fs_total
								);
								?>
							</p>
						<?php endif; ?>
					</header>

					<?php
					// Nothing worth filtering below four stories.
					if ( $fs_total >= 4 || $fs_has_filter ) {
						get_template_part(
							'template-parts/components/story-filters',
							null,
							array(
								'active' => array(
									'state'      => $fs_state_filter,
									'story_year' => $fs_year_filter,
								),
								'groups' => array(
									array(
										'key'   => 'state',
										'label' => __( 'State', 'focused-schools' ),
										'name'  => __( 'Filter stories by state', 'focused-schools' ),
										'terms' => focused_schools_story_filter_terms( '_fs_impact_story_state' ),
									),
									array(
										'key'   => 'story_year',
										'label' => __( 'Year', 'focused-schools' ),
										'name'  => __( 'Filter stories by year', 'focused-schools' ),
										'terms' => array_reverse( focused_schools_story_filter_terms( '_fs_impact_story_year' ) ),
									),
								),
							)
						);
					}
					?>

					<?php if ( $fs_stories ) : ?>
						<div class="fs-card-grid fs-stories__grid" id="fs-stories-grid">
							<?php
							$fs_index = 0;

							foreach ( $fs_stories as $fs_story ) :
								?>
								<div<?php echo $fs_index >= $fs_per_page ? ' hidden' : ''; ?>>
									<?php
									get_template_part(
										'template-parts/components/impact-story-card',
										null,
										array(
											'post' => $fs_story,
											'placeholder_mark_url' => $fs_img . 'mark-white.svg',
										)
									);
									?>
								</div>
								<?php
								++$fs_index;
							endforeach;
							?>
						</div>

						<?php if ( $fs_total > $fs_per_page ) : ?>
							<div class="fs-loadmore">
								<button
									class="fs-btn fs-btn--secondary"
									type="button"
									data-fs-story-load-more
									data-fs-total="<?php echo esc_attr( $fs_total ); ?>"
									aria-controls="fs-stories-grid"
								>
									<?php esc_html_e( 'Load more', 'focused-schools' ); ?>
									<span aria-hidden="true">&darr;</span>
								</button>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="fs-stories__empty">
							<h3><?php esc_html_e( 'No stories match that combination yet.', 'focused-schools' ); ?></h3>
							<p><?php esc_html_e( 'Older stories carry less structured detail than newer ones, so a narrow filter can come back empty. Try widening it.', 'focused-schools' ); ?></p>
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label' => __( 'Clear all filters', 'focused-schools' ),
									'url'   => get_permalink(),
									'style' => 'secondary',
								)
							);
							?>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<?php
			get_template_part(
				'template-parts/components/content-image-split',
				null,
				array(
					'eyebrow'        => __( 'Write the next one', 'focused-schools' ),
					'heading'        => __( 'Your district could be the next story.', 'focused-schools' ),
					'body'           => '<p>' . esc_html__( 'Tell us where your district is headed. If we are a fit we will say so — and if we are not, we will say that too.', 'focused-schools' ) . '</p>',
					'image_url'      => $fs_img . 'retreat-2.jpg',
					'image_alt'      => __( 'Educators celebrating progress together.', 'focused-schools' ),
					'image_position' => 'right',
					'cta_label'      => __( "Let's Talk", 'focused-schools' ),
					'cta_url'        => home_url( '/contact/' ),
					'cta2_label'     => __( 'Explore Our Services', 'focused-schools' ),
					'cta2_url'       => home_url( '/services/' ),
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
