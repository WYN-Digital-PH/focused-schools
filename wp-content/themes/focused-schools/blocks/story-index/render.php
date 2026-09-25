<?php
/**
 * Server render for focused-schools/story-index.
 *
 * The filterable grid. Filters are read from the URL and rendered on the
 * server, so a filtered view is shareable, survives the back button and
 * works with JavaScript off — the script only saves a round trip.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_data     = focused_schools_impact_stories();
$fs_stories  = $fs_data['stories'];
$fs_total    = $fs_data['total'];
$fs_per_page = isset( $attributes['perPage'] ) ? max( 1, (int) $attributes['perPage'] ) : 9;
$fs_showing  = min( $fs_per_page, $fs_total );
$fs_anchor   = isset( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'stories';
$fs_img      = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
?>
<section class="fs-stories" id="<?php echo esc_attr( $fs_anchor ); ?>" aria-labelledby="fs-stories-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-stories__head">
			<div>
				<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				<h2 id="fs-stories-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
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
		if ( $fs_total >= 4 || $fs_data['filtered'] ) {
			get_template_part(
				'template-parts/components/story-filters',
				null,
				array(
					'active' => array(
						'state'      => $fs_data['state'],
						'story_year' => $fs_data['year'],
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
								'post'                 => $fs_story,
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
				<h3><?php echo esc_html( isset( $attributes['emptyText'] ) ? $attributes['emptyText'] : '' ); ?></h3>
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
