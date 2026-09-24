<?php
/**
 * Server render for focused-schools/team-grid.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_img     = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';
$fs_visible = isset( $attributes['visibleCount'] ) ? max( 1, (int) $attributes['visibleCount'] ) : 9;
$fs_url     = isset( $attributes['linkUrl'] ) ? (string) $attributes['linkUrl'] : '';

$fs_team = new WP_Query(
	array(
		'post_type'      => 'fs_team_member',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="fs-about__section fs-about__team fs-container fs-container--shell" id="team" aria-labelledby="fs-about-team-title">
	<header class="fs-about__team-intro">
		<div>
			<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
			<h2 id="fs-about-team-title">
				<?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?>
				<strong><?php echo esc_html( isset( $attributes['headingEmphasis'] ) ? $attributes['headingEmphasis'] : '' ); ?></strong>
			</h2>
		</div>
		<p class="fs-about__team-lead"><?php echo esc_html( isset( $attributes['intro'] ) ? $attributes['intro'] : '' ); ?></p>
	</header>

	<?php if ( $fs_team->have_posts() ) : ?>
		<div class="fs-card-grid" id="fs-about-team-grid">
			<?php
			$fs_index = 0;

			while ( $fs_team->have_posts() ) :
				$fs_team->the_post();
				?>
				<div<?php echo $fs_index >= $fs_visible ? ' hidden' : ''; ?>>
					<?php
					get_template_part(
						'template-parts/components/team-card',
						null,
						array(
							'post'                 => get_post(),
							'bio_modal'            => true,
							'compact'              => true,
							'placeholder_mark_url' => $fs_img . 'mark-white.svg',
						)
					);
					?>
				</div>
				<?php
				++$fs_index;
			endwhile;
			?>
		</div>

		<?php if ( $fs_index > $fs_visible ) : ?>
			<div class="fs-loadmore">
				<button class="fs-btn fs-btn--secondary" type="button" data-fs-team-load-more aria-controls="fs-about-team-grid">
					<?php esc_html_e( 'Load more', 'focused-schools' ); ?>
					<span aria-hidden="true">&darr;</span>
				</button>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/components/team-bio-modal' ); ?>
	<?php else : ?>
		<p class="fs-about__empty"><?php esc_html_e( 'Our full leadership team profiles are on the way — please check back soon.', 'focused-schools' ); ?></p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php if ( ! empty( $attributes['linkLabel'] ) ) : ?>
		<p class="fs-mini">
			<a class="fs-text-link" href="<?php echo esc_url( 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url ); ?>">
				<?php echo esc_html( $attributes['linkLabel'] ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</p>
	<?php endif; ?>
</section>
