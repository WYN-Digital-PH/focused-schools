<?php
/**
 * Server render for focused-schools/impact-stories.
 *
 * Delegates to the same components the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_limit = isset( $attributes['limit'] ) ? max( 1, (int) $attributes['limit'] ) : 6;

$fs_testimonials = new WP_Query(
	array(
		'post_type'      => 'fs_testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => $fs_limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="fs-home__section fs-home__section--teal" aria-labelledby="fs-impact-title">
	<div class="fs-container fs-container--shell">
		<div class="fs-home__impact-head">
			<div>
				<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				<h2 id="fs-impact-title" class="fs-home__impact-heading"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
			</div>
			<?php
			if ( ! empty( $attributes['ctaLabel'] ) ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $attributes['ctaLabel'],
						'url'   => home_url( '/impact-stories/' ),
						'style' => 'white',
					)
				);
			}
			?>
		</div>

		<?php
		if ( $fs_testimonials->have_posts() ) :
			get_template_part( 'template-parts/components/testimonial-carousel', null, array( 'posts' => $fs_testimonials->posts ) );
		else :
			?>
			<p class="fs-home__empty fs-home__empty--on-dark"><?php esc_html_e( 'Testimonials from the leaders we partner with are on the way.', 'focused-schools' ); ?></p>
			<?php
		endif;
		wp_reset_postdata();
		?>
	</div>
</section>
