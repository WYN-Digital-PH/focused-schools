<?php
/**
 * Server render for focused-schools/beliefs.
 *
 * Wraps rail-text.php in the scroll-held container, exactly as the hardcoded
 * template did — home-hold.js finds it by the same data attribute.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_body = isset( $attributes['body'] ) ? trim( (string) $attributes['body'] ) : '';

$fs_strip = array(
	focused_schools_block_image( $attributes, 'strip1', '/assets/img/retreat-2.jpg', __( 'Two education leaders celebrating progress with a fist bump.', 'focused-schools' ) ),
	focused_schools_block_image( $attributes, 'strip2', '/assets/img/retreat-3.jpg', __( 'A facilitator and district leader discussing a system map.', 'focused-schools' ) ),
	focused_schools_block_image( $attributes, 'strip3', '/assets/img/student-video.jpg', __( 'Students learning together in a classroom.', 'focused-schools' ) ),
);
?>
<div class="fs-home__hold" data-fs-home-hold>
	<div class="fs-home__hold-inner">
		<?php
		get_template_part(
			'template-parts/components/rail-text',
			null,
			array(
				'eyebrow'   => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
				'icon_url'  => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-1.svg',
				'heading'   => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
				'body'      => '' !== $fs_body ? preg_split( '/\r\n\r\n|\n\n/', $fs_body ) : array(),
				'emphasis'  => isset( $attributes['emphasis'] ) ? $attributes['emphasis'] : '',
				'cta_label' => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
				'cta_url'   => ! empty( $attributes['ctaUrl'] ) ? $attributes['ctaUrl'] : home_url( '/about-our-mission-vision/' ),
			)
		);
		?>
	</div>
</div>

<div class="fs-home__strip" aria-label="<?php esc_attr_e( 'Focused Schools leadership retreat', 'focused-schools' ); ?>">
	<?php foreach ( $fs_strip as $fs_photo ) : ?>
		<figure><img src="<?php echo esc_url( $fs_photo['url'] ); ?>" alt="<?php echo esc_attr( $fs_photo['alt'] ); ?>" loading="lazy" /></figure>
	<?php endforeach; ?>
</div>
