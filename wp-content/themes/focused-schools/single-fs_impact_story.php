<?php
/**
 * Single Impact Story.
 *
 * Renders an fs_impact_story at the approved /impact-stories/{slug}/ URL. The
 * post type's own rewrite owns that structure; nothing here hardcodes a path.
 *
 * Narrative order is what happened → what changed → how: breadcrumb and hero,
 * the At a glance results band, then the story body beside a sticky detail
 * rail, then related stories. See docs/page-specs/impact-story-single.md.
 *
 * Degradation is the point of the design, not an afterthought — every
 * structured section disappears rather than rendering a placeholder:
 * - no results          → the At a glance band is omitted entirely
 * - fewer than two rail → the rail drops and the prose spans 72ch
 * - no featured image   → the slab sits on a plain teal band, never a
 *                         stretched upscale
 * - no related matches  → that section is omitted
 *
 * Yoast SEO: the standard Loop plus wp_head() via header.php, with the theme
 * declaring title-tag support. No custom <title> or meta is emitted here, so
 * Yoast owns the document head exactly as it does for any other post type.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$fs_id      = get_the_ID();
	$fs_img     = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
	$fs_stories = home_url( '/impact-stories/' );

	$fs_district = (string) get_post_meta( $fs_id, '_fs_impact_story_district_or_school', true );
	$fs_state    = (string) get_post_meta( $fs_id, '_fs_impact_story_state', true );
	$fs_year     = (string) get_post_meta( $fs_id, '_fs_impact_story_year', true );
	$fs_lane     = (string) get_post_meta( $fs_id, '_fs_impact_story_service_lane', true );
	$fs_length   = (string) get_post_meta( $fs_id, '_fs_impact_story_partnership_length', true );

	$fs_chips = array_values( array_filter( array( $fs_district, $fs_state, $fs_year ), 'strlen' ) );

	// Same split the plugin's Meta::results_list() performs — duplicated, not
	// referenced, so this degrades rather than fatals if the plugin is off.
	$fs_results = array();

	foreach ( preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $fs_id, '_fs_impact_story_results', true ) ) as $fs_line ) {
		$fs_parts = array_map( 'trim', explode( '|', (string) $fs_line ) );

		if ( '' === $fs_parts[0] || count( $fs_results ) >= 4 ) {
			continue;
		}

		$fs_results[] = array(
			'value' => $fs_parts[0],
			'unit'  => isset( $fs_parts[1] ) ? $fs_parts[1] : '',
			'label' => isset( $fs_parts[2] ) ? $fs_parts[2] : '',
		);
	}

	// The rail renders only the rows it has, and drops below two.
	$fs_rail = array_filter(
		array(
			__( 'District', 'focused-schools' )           => $fs_district,
			__( 'State', 'focused-schools' )              => $fs_state,
			__( 'Year', 'focused-schools' )               => $fs_year,
			__( 'Service lane', 'focused-schools' )       => $fs_lane,
			__( 'Partnership length', 'focused-schools' ) => $fs_length,
		),
		'strlen'
	);

	$fs_has_rail = count( $fs_rail ) >= 2;
	?>
	<main id="content" class="fs-story">
		<div class="fs-container fs-container--shell">
			<nav class="fs-story__crumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'focused-schools' ); ?>">
				<a href="<?php echo esc_url( $fs_stories ); ?>"><?php esc_html_e( 'Impact Stories', 'focused-schools' ); ?></a>
				<?php if ( '' !== $fs_district ) : ?>
					<span aria-hidden="true">/</span>
					<span class="fs-story__crumb-current"><?php echo esc_html( $fs_district ); ?></span>
				<?php endif; ?>
			</nav>
		</div>

		<section class="fs-story__hero">
			<div class="fs-container fs-container--shell fs-story__hero-inner">
				<div class="fs-story__media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
						the_post_thumbnail(
							'full',
							array(
								'class'         => 'fs-story__image',
								'fetchpriority' => 'high',
							)
						);
						?>
					<?php endif; ?>
				</div>

				<div class="fs-story__slab">
					<?php if ( $fs_chips ) : ?>
						<p class="fs-story__chips">
							<?php foreach ( $fs_chips as $fs_chip ) : ?>
								<span><?php echo esc_html( $fs_chip ); ?></span>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>

					<h1 class="fs-story__title"><?php the_title(); ?></h1>

					<?php $fs_excerpt = trim( (string) get_the_excerpt() ); ?>
					<?php if ( '' !== $fs_excerpt ) : ?>
						<p class="fs-story__standfirst"><?php echo esc_html( $fs_excerpt ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( $fs_results ) : ?>
			<section class="fs-story__glance" aria-labelledby="fs-story-glance">
				<img class="fs-story__watermark" src="<?php echo esc_url( $fs_img . 'mark-white.svg' ); ?>" alt="" aria-hidden="true" />
				<div class="fs-container fs-container--shell">
					<p class="fs-eyebrow fs-eyebrow--on-dark" id="fs-story-glance"><?php esc_html_e( 'At a glance', 'focused-schools' ); ?></p>
					<div class="fs-story__results">
						<?php foreach ( $fs_results as $fs_result ) : ?>
							<?php
							// Values are abbreviated, so each result carries a
							// full-sentence label for screen readers.
							$fs_spoken = trim( $fs_result['value'] . ' ' . $fs_result['unit'] . ' ' . $fs_result['label'] );
							?>
							<div class="fs-story__result" role="group" aria-label="<?php echo esc_attr( $fs_spoken ); ?>">
								<p class="fs-story__result-value" aria-hidden="true"><?php echo esc_html( $fs_result['value'] ); ?></p>
								<?php if ( '' !== $fs_result['unit'] ) : ?>
									<p class="fs-story__result-unit" aria-hidden="true"><?php echo esc_html( $fs_result['unit'] ); ?></p>
								<?php endif; ?>
								<?php if ( '' !== $fs_result['label'] ) : ?>
									<p class="fs-story__result-label" aria-hidden="true"><?php echo esc_html( $fs_result['label'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="fs-story__body<?php echo $fs_has_rail ? '' : ' fs-story__body--wide'; ?>">
			<div class="fs-container fs-container--shell fs-story__body-inner" data-single-body>
				<div class="fs-story__prose">
					<?php the_content(); ?>
				</div>

				<?php if ( $fs_has_rail ) : ?>
					<aside class="fs-story__rail">
						<p class="fs-eyebrow"><?php esc_html_e( 'Partnership details', 'focused-schools' ); ?></p>
						<dl>
							<?php foreach ( $fs_rail as $fs_label => $fs_value ) : ?>
								<div>
									<dt><?php echo esc_html( $fs_label ); ?></dt>
									<dd><?php echo esc_html( $fs_value ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => __( 'Start a Partnership', 'focused-schools' ),
								'url'   => home_url( '/contact/' ),
							)
						);
						?>
					</aside>
				<?php endif; ?>
			</div>
		</section>

		<?php
		/*
		 * Related: state, then service lane, then recency, with the current
		 * story always excluded. Runs the narrowest match first and tops up,
		 * so a story with no state still gets neighbours.
		 */
		$fs_related  = array();
		$fs_seen     = array( $fs_id );
		$fs_searches = array();

		if ( '' !== $fs_state ) {
			$fs_searches[] = array(
				'key'   => '_fs_impact_story_state',
				'value' => $fs_state,
			);
		}

		if ( '' !== $fs_lane ) {
			$fs_searches[] = array(
				'key'   => '_fs_impact_story_service_lane',
				'value' => $fs_lane,
			);
		}

		$fs_searches[] = null; // Recency backfill.

		foreach ( $fs_searches as $fs_search ) {
			if ( count( $fs_related ) >= 3 ) {
				break;
			}

			$fs_args = array(
				'post_type'      => 'fs_impact_story',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'post__not_in'   => $fs_seen,
				'no_found_rows'  => true,
			);

			if ( $fs_search ) {
				$fs_args['meta_query'] = array( $fs_search ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- small, admin-managed set.
			}

			foreach ( get_posts( $fs_args ) as $fs_match ) {
				if ( count( $fs_related ) >= 3 ) {
					break;
				}

				$fs_related[] = $fs_match;
				$fs_seen[]    = $fs_match->ID;
			}
		}

		if ( $fs_related ) :
			?>
			<section class="fs-story__related" aria-labelledby="fs-story-related">
				<div class="fs-container fs-container--shell">
					<header class="fs-stories__head">
						<div>
							<p class="fs-eyebrow"><?php esc_html_e( 'Keep reading', 'focused-schools' ); ?></p>
							<h2 id="fs-story-related"><?php esc_html_e( 'More stories from the work.', 'focused-schools' ); ?></h2>
						</div>
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => __( 'All Impact Stories', 'focused-schools' ),
								'url'   => $fs_stories,
								'style' => 'secondary',
							)
						);
						?>
					</header>

					<div class="fs-card-grid fs-stories__grid">
						<?php
						foreach ( $fs_related as $fs_match ) {
							get_template_part(
								'template-parts/components/impact-story-card',
								null,
								array(
									'post'                 => $fs_match,
									'placeholder_mark_url' => $fs_img . 'mark-white.svg',
								)
							);
						}
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
		?>
	</main>
	<?php
endwhile;

get_footer();
