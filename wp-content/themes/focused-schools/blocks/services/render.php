<?php
/**
 * Server render for focused-schools/services.
 *
 * Delegates to the same components the hardcoded layout uses, so block-built
 * and template-built output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_photo = focused_schools_block_image( $attributes, 'image', '/assets/img/retreat-2.jpg', __( 'A Focused Schools facilitator working with district leaders at a strategy session.', 'focused-schools' ) );
$fs_link  = isset( $attributes['linkUrl'] ) ? (string) $attributes['linkUrl'] : '/services/';
$fs_link  = 0 === strpos( $fs_link, '/' ) ? home_url( $fs_link ) : $fs_link;
$fs_limit = isset( $attributes['limit'] ) ? max( 1, (int) $attributes['limit'] ) : 4;

// Live query: publishing a service adds it here, with no page edit.
$fs_services = new WP_Query(
	array(
		'post_type'      => 'fs_service',
		'post_status'    => 'publish',
		'posts_per_page' => $fs_limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="fs-home__section" aria-labelledby="fs-services-title">
	<div class="fs-container fs-container--shell">
		<div class="fs-home__services-head">
			<div>
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'eyebrow'     => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
						'heading'     => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
						'description' => isset( $attributes['description'] ) ? $attributes['description'] : '',
						'heading_id'  => 'fs-services-title',
					)
				);
				?>
				<?php if ( ! empty( $attributes['linkLabel'] ) ) : ?>
					<p class="fs-home__view-all">
						<a class="fs-btn fs-btn--text" href="<?php echo esc_url( $fs_link ); ?>">
							<?php echo esc_html( $attributes['linkLabel'] ); ?>
							<span class="fs-btn__arrow" aria-hidden="true">&rarr;</span>
						</a>
					</p>
				<?php endif; ?>
			</div>
			<figure class="fs-home__services-photo">
				<img src="<?php echo esc_url( $fs_photo['url'] ); ?>" alt="<?php echo esc_attr( $fs_photo['alt'] ); ?>" loading="lazy" />
				<figcaption class="fs-figure-caption">
					<span class="fs-figure-caption__kicker"><?php echo esc_html( isset( $attributes['captionKicker'] ) ? $attributes['captionKicker'] : '' ); ?></span>
					<strong><?php echo esc_html( isset( $attributes['captionText'] ) ? $attributes['captionText'] : '' ); ?></strong>
				</figcaption>
			</figure>
		</div>

		<?php
		if ( $fs_services->have_posts() ) :
			get_template_part( 'template-parts/components/service-list', null, array( 'posts' => $fs_services->posts ) );
		else :
			?>
			<p class="fs-home__empty"><?php esc_html_e( 'Our services list is being updated — please check back soon.', 'focused-schools' ); ?></p>
			<?php
		endif;
		wp_reset_postdata();
		?>
	</div>
</section>
