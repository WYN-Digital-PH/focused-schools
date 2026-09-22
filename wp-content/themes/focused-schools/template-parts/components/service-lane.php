<?php
/**
 * Component: Service Lane (fs_service full-width detail section).
 *
 * Contract ($args):
 * - post      (WP_Post|int, required)
 * - index     (int, required) 1-based position, rendered as the 01/02/03 accent
 * - reverse   (bool) photo on the left, copy on the right — the mockup flips
 *              every second lane. Source order is unchanged either way.
 * - cta_label (string) defaults to "Start This Conversation"
 * - cta_url   (string) defaults to /contact/
 * - image_url (string) fallback photo used only when the service has no
 *              featured image, e.g. a theme-bundled asset via
 *              get_template_directory_uri() — same pattern as hero.php
 * - image_alt (string) alt text for image_url
 *
 * The third fs_service display integration, alongside service-card.php (grid,
 * used by the Home page teaser) and service-list.php (numbered index rows).
 * This one is the Services page's full detail band: copy on one side, photo
 * with an optional video control and a proof caption on the other.
 *
 * Meta key literals mirror FocusedSchoolsCore\Modules\Services\Meta so this
 * template degrades gracefully (empty values) rather than fataling if the
 * plugin is deactivated — same reasoning as the two sibling components.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id = $fs_post->ID;
$fs_index   = isset( $args['index'] ) ? (int) $args['index'] : 0;
$fs_title   = get_the_title( $fs_post_id );
$fs_tagline = get_post_meta( $fs_post_id, '_fs_service_tagline', true );
$fs_video   = get_post_meta( $fs_post_id, '_fs_service_video_url', true );
$fs_proof   = get_post_meta( $fs_post_id, '_fs_service_proof', true );
$fs_raw     = (string) get_post_meta( $fs_post_id, '_fs_service_offerings', true );

// Same split the plugin's Meta::offerings_list() performs — duplicated, not
// referenced, for the plugin-deactivated case noted above.
$fs_offerings = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $fs_raw ) ), 'strlen' ) );

$fs_cta_label = isset( $args['cta_label'] ) ? $args['cta_label'] : __( 'Start This Conversation', 'focused-schools' );
$fs_cta_url   = isset( $args['cta_url'] ) ? $args['cta_url'] : home_url( '/contact/' );
$fs_head_id   = 'fs-lane-' . $fs_post->post_name;
$fs_reverse   = ! empty( $args['reverse'] );
?>
<div class="fs-lane<?php echo $fs_reverse ? ' fs-lane--reverse' : ''; ?>" id="<?php echo esc_attr( $fs_post->post_name ); ?>">
	<div class="fs-lane__copy">
		<?php if ( $fs_index ) : ?>
			<p class="fs-lane__accent" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></p>
		<?php endif; ?>
		<h2 id="<?php echo esc_attr( $fs_head_id ); ?>"><?php echo esc_html( $fs_title ); ?></h2>
		<?php if ( $fs_tagline ) : ?>
			<p class="fs-lane__tagline"><?php echo esc_html( $fs_tagline ); ?></p>
		<?php endif; ?>
		<?php
		/*
		 * The lane shows the service's full body, not its excerpt — the
		 * excerpt is the short card/index-row description, and on this page
		 * that text already appears as the tagline above.
		 */
		$fs_body = trim( (string) $fs_post->post_content );

		if ( '' !== $fs_body ) :
			?>
			<div class="fs-body fs-lane__desc"><?php echo wp_kses_post( apply_filters( 'the_content', $fs_body ) ); ?></div>
			<?php
		endif;
		?>

		<?php if ( ! empty( $fs_offerings ) ) : ?>
			<div class="fs-offerings">
				<p class="fs-offerings__label"><?php esc_html_e( 'Signature offerings', 'focused-schools' ); ?></p>
				<ul class="fs-offerings__list">
					<?php foreach ( $fs_offerings as $fs_offering ) : ?>
						<li><?php echo esc_html( $fs_offering ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="fs-btn-row">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
				)
			);
			?>
		</div>
	</div>

	<figure class="fs-lane__media fs-figure<?php echo $fs_video ? ' fs-lane__media--video' : ''; ?>">
		<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$fs_post_id,
				'large',
				array(
					'class'   => 'fs-photo',
					'alt'     => esc_attr( $fs_title ),
					'loading' => 'lazy',
				)
			);
			?>
		<?php elseif ( ! empty( $args['image_url'] ) ) : ?>
			<img
				class="fs-photo"
				src="<?php echo esc_url( $args['image_url'] ); ?>"
				alt="<?php echo esc_attr( isset( $args['image_alt'] ) ? $args['image_alt'] : $fs_title ); ?>"
				loading="lazy"
			/>
		<?php else : ?>
			<div class="fs-photo fs-photo--placeholder" role="presentation"></div>
		<?php endif; ?>

		<?php if ( $fs_video ) : ?>
			<a class="fs-playover" href="<?php echo esc_url( $fs_video ); ?>" target="_blank" rel="noopener noreferrer">
				<span class="fs-playover__glyph" aria-hidden="true">&#9654;</span>
				<span class="fs-playover__label">
					<?php
					printf(
						/* translators: %s: service name. */
						esc_html__( 'Watch %s overview video', 'focused-schools' ),
						esc_html( $fs_title )
					);
					?>
				</span>
			</a>
		<?php endif; ?>

		<?php if ( $fs_proof ) : ?>
			<figcaption class="fs-figure__caption">
				<span class="fs-figure__kicker"><?php echo esc_html( $fs_title ); ?></span>
				<span class="fs-figure__text"><?php echo esc_html( $fs_proof ); ?></span>
			</figcaption>
		<?php endif; ?>
	</figure>
</div>
