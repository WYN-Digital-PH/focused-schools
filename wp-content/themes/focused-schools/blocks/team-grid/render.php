<?php
/**
 * Server render for focused-schools/team-grid.
 *
 * Delegates to the same components the hardcoded layouts used, so
 * block-built and template-built output cannot drift.
 *
 * Two treatments of one grid, chosen by the `variant` attribute. The teaser
 * is the About page's: an intro paragraph and a link on to the full team.
 * The roster is the Team page's: a paper ground and a live count of how many
 * of the team are currently shown. The cards, the reveal behaviour and the
 * bio dialog are identical, which is why this is one block and not two.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_img      = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
$fs_visible  = isset( $attributes['visibleCount'] ) ? max( 1, (int) $attributes['visibleCount'] ) : 9;
$fs_url      = isset( $attributes['linkUrl'] ) ? (string) $attributes['linkUrl'] : '';
$fs_roster   = isset( $attributes['variant'] ) && 'roster' === $attributes['variant'];
$fs_grid_id  = $fs_roster ? 'fs-team-grid' : 'fs-about-team-grid';
$fs_title_id = $fs_roster ? 'fs-team-heading' : 'fs-about-team-title';

$fs_team = get_posts(
	array(
		'post_type'      => 'fs_team_member',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

$fs_total   = count( $fs_team );
$fs_showing = min( $fs_visible, $fs_total );
?>
<section
	class="<?php echo esc_attr( $fs_roster ? 'fs-team__section fs-team__grid-section' : 'fs-about__section fs-about__team fs-container fs-container--shell' ); ?>"
	id="<?php echo esc_attr( $fs_roster ? 'team-grid' : 'team' ); ?>"
	aria-labelledby="<?php echo esc_attr( $fs_title_id ); ?>"
>
	<?php if ( $fs_roster ) : ?>
	<div class="fs-container fs-container--shell">
	<?php endif; ?>

		<header class="<?php echo esc_attr( $fs_roster ? 'fs-team__head' : 'fs-about__team-intro' ); ?>">
			<div>
				<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				<h2 id="<?php echo esc_attr( $fs_title_id ); ?>">
					<?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?>
					<?php if ( ! empty( $attributes['headingEmphasis'] ) ) : ?>
						<strong><?php echo esc_html( $attributes['headingEmphasis'] ); ?></strong>
					<?php endif; ?>
				</h2>
			</div>

			<?php
			if ( $fs_roster ) :
				if ( $fs_total > 0 ) :
					/* translators: 1: number of members shown, 2: total number of members. */
					$fs_format = __( 'Showing %1$d of %2$d', 'focused-schools' );
					?>
					<?php // Rewritten when Load more runs, so it is announced. ?>
					<p
						class="fs-team__count"
						aria-live="polite"
						data-fs-team-count
						data-fs-team-count-all="<?php echo esc_attr( sprintf( $fs_format, $fs_total, $fs_total ) ); ?>"
					>
						<?php echo esc_html( sprintf( $fs_format, $fs_showing, $fs_total ) ); ?>
					</p>
					<?php
				endif;
			elseif ( ! empty( $attributes['intro'] ) ) :
				?>
				<p class="fs-about__team-lead"><?php echo esc_html( $attributes['intro'] ); ?></p>
				<?php
			endif;
			?>
		</header>

		<?php if ( $fs_team ) : ?>
			<div class="fs-card-grid" id="<?php echo esc_attr( $fs_grid_id ); ?>">
				<?php
				$fs_index = 0;

				foreach ( $fs_team as $fs_member ) :
					?>
					<div<?php echo $fs_index >= $fs_visible ? ' hidden' : ''; ?>>
						<?php
						get_template_part(
							'template-parts/components/team-card',
							null,
							array(
								'post'                 => $fs_member,
								'bio_modal'            => true,
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

			<?php if ( $fs_total > $fs_visible ) : ?>
				<div class="fs-loadmore">
					<button class="fs-btn fs-btn--secondary" type="button" data-fs-team-load-more aria-controls="<?php echo esc_attr( $fs_grid_id ); ?>">
						<?php esc_html_e( 'Load more', 'focused-schools' ); ?>
						<span aria-hidden="true">&darr;</span>
					</button>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/components/team-bio-modal' ); ?>
		<?php else : ?>
			<p class="<?php echo esc_attr( $fs_roster ? 'fs-team__empty' : 'fs-about__empty' ); ?>">
				<?php esc_html_e( 'Our team profiles are on the way — please check back soon.', 'focused-schools' ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! $fs_roster && ! empty( $attributes['linkLabel'] ) ) : ?>
			<p class="fs-mini">
				<a class="fs-text-link" href="<?php echo esc_url( 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url ); ?>">
					<?php echo esc_html( $attributes['linkLabel'] ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</p>
		<?php endif; ?>

	<?php if ( $fs_roster ) : ?>
	</div>
	<?php endif; ?>
</section>
