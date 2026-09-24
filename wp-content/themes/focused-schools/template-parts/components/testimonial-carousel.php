<?php
/**
 * Component: Testimonial Carousel (fs_testimonial display integration).
 *
 * Contract ($args):
 * - posts    (WP_Post[], required) — post_title = attribution, post_content = quote
 * - on_light (bool, optional, default false) — Home uses this component on
 *   its teal "Impact stories" band, so text/controls default to white;
 *   Services' own `.dc` source puts its testimonial section on a light
 *   paper ground instead, where white text is invisible. Adds a
 *   `--on-light` modifier class switching text/control colors to teal/ink,
 *   rather than changing the shared white default Home correctly needs.
 *
 * Renders nothing if $posts is empty — the caller decides the empty-state
 * fallback. All slides are always present in the markup (not injected by
 * JS); assets/js/components/testimonial-carousel.js only toggles which one
 * is visible/focusable, so no-JS and screen-reader users get every
 * testimonial regardless. Respects prefers-reduced-motion (no JS timer
 * auto-advance is used at all, by design — advancing is always
 * user-initiated via the arrows/dots).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_on_light = ! empty( $args['on_light'] );
$fs_posts    = isset( $args['posts'] ) && is_array( $args['posts'] ) ? $args['posts'] : array();
$fs_posts    = array_values(
	array_filter(
		array_map( 'get_post', $fs_posts ),
		function ( $fs_post ) {
			return $fs_post instanceof WP_Post;
		}
	)
);

if ( empty( $fs_posts ) ) {
	return;
}

$fs_count = count( $fs_posts );
?>
<div class="fs-testimonial-carousel<?php echo $fs_on_light ? ' fs-testimonial-carousel--on-light' : ''; ?>" data-fs-carousel aria-roledescription="carousel">
	<div class="fs-testimonial-carousel__track" data-fs-carousel-track tabindex="0" aria-label="<?php esc_attr_e( 'Testimonials', 'focused-schools' ); ?>">
		<?php
		foreach ( $fs_posts as $fs_index => $fs_post ) :
			$fs_slide_id = 'fs-testimonial-' . $fs_post->ID;
			?>
			<figure
				class="fs-testimonial-carousel__slide<?php echo 0 === $fs_index ? ' is-active' : ''; ?>"
				id="<?php echo esc_attr( $fs_slide_id ); ?>"
				data-fs-carousel-slide
				<?php echo 0 !== $fs_index ? 'aria-hidden="true"' : ''; ?>
			>
				<span class="fs-testimonial-carousel__marks" aria-hidden="true"><span></span><span></span></span>
				<blockquote><?php echo wp_kses_post( wpautop( get_the_content( null, false, $fs_post ) ) ); ?></blockquote>
				<figcaption><span class="fs-testimonial-carousel__rule" aria-hidden="true"></span><?php echo esc_html( get_the_title( $fs_post ) ); ?></figcaption>
			</figure>
		<?php endforeach; ?>
	</div>

	<?php if ( $fs_count > 1 ) : ?>
		<div class="fs-testimonial-carousel__controls">
			<span aria-hidden="true"></span>
			<div class="fs-testimonial-carousel__nav">
				<button type="button" class="fs-testimonial-carousel__arrow" data-fs-carousel-prev aria-label="<?php esc_attr_e( 'Previous testimonial', 'focused-schools' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 5-7 7 7 7" /></svg>
				</button>
				<div class="fs-testimonial-carousel__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose testimonial', 'focused-schools' ); ?>">
					<?php foreach ( $fs_posts as $fs_index => $fs_post ) : ?>
						<button
							type="button"
							class="fs-testimonial-carousel__dot<?php echo 0 === $fs_index ? ' is-active' : ''; ?>"
							role="tab"
							data-fs-carousel-dot="<?php echo esc_attr( $fs_index ); ?>"
							aria-controls="fs-testimonial-<?php echo esc_attr( $fs_post->ID ); ?>"
							<?php echo 0 === $fs_index ? 'aria-selected="true"' : 'aria-selected="false"'; ?>
							aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number 2: total slides. */ __( 'Testimonial %1$d of %2$d', 'focused-schools' ), $fs_index + 1, $fs_count ) ); ?>"
						><span></span></button>
					<?php endforeach; ?>
				</div>
				<button type="button" class="fs-testimonial-carousel__arrow" data-fs-carousel-next aria-label="<?php esc_attr_e( 'Next testimonial', 'focused-schools' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 5 7 7-7 7" /></svg>
				</button>
			</div>
			<p class="fs-testimonial-carousel__status" data-fs-carousel-status aria-live="polite">
				<?php echo esc_html( sprintf( /* translators: 1: slide number 2: total slides. */ __( '%1$d / %2$d', 'focused-schools' ), 1, $fs_count ) ); ?>
			</p>
		</div>
	<?php endif; ?>
</div>
