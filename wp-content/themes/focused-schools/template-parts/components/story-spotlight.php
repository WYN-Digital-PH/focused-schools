<?php
/**
 * Component: Featured Story Spotlight.
 *
 * Contract ($args):
 * - post (WP_Post|int, required) — always an fs_impact_story.
 *
 * A full-bleed teal split, not the biggest card, so the newest Featured story
 * reads as a statement. Legacy Pages can never reach this slot, which is
 * deliberate: the most prominent block on the page can never render a
 * degraded record (docs/page-specs/impact-stories.md §3).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id = $fs_post->ID;
$fs_chips   = array_values(
	array_filter(
		array(
			(string) get_post_meta( $fs_post_id, '_fs_impact_story_district_or_school', true ),
			(string) get_post_meta( $fs_post_id, '_fs_impact_story_state', true ),
			(string) get_post_meta( $fs_post_id, '_fs_impact_story_year', true ),
		),
		'strlen'
	)
);
?>
<section class="fs-spotlight" aria-labelledby="fs-spotlight-title">
	<div class="fs-container fs-container--shell">
		<p class="fs-spotlight__label">
			<span class="fs-rule fs-rule--gold" aria-hidden="true"></span>
			<span class="fs-eyebrow fs-eyebrow--on-dark"><?php esc_html_e( 'Featured story', 'focused-schools' ); ?></span>
		</p>

		<article class="fs-spotlight__card">
			<div class="fs-spotlight__copy">
				<?php if ( $fs_chips ) : ?>
					<p class="fs-spotlight__meta">
						<?php foreach ( $fs_chips as $fs_chip ) : ?>
							<span><?php echo esc_html( $fs_chip ); ?></span>
						<?php endforeach; ?>
					</p>
				<?php endif; ?>

				<h2 id="fs-spotlight-title"><?php echo esc_html( get_the_title( $fs_post_id ) ); ?></h2>

				<?php $fs_excerpt = trim( (string) get_the_excerpt( $fs_post_id ) ); ?>
				<?php if ( '' !== $fs_excerpt ) : ?>
					<p class="fs-spotlight__excerpt"><?php echo esc_html( $fs_excerpt ); ?></p>
				<?php endif; ?>

				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => __( 'Read the Story', 'focused-schools' ),
						'url'   => get_permalink( $fs_post_id ),
						'style' => 'white',
					)
				);
				?>
			</div>

			<div class="fs-spotlight__media">
				<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
					<?php
					echo get_the_post_thumbnail(
						$fs_post_id,
						'large',
						array(
							'class' => 'fs-spotlight__image',
							'alt'   => esc_attr( get_the_title( $fs_post_id ) ),
						)
					);
					?>
				<?php endif; ?>
			</div>
		</article>
	</div>
</section>
