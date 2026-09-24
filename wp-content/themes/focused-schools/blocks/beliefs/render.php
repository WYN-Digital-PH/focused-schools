<?php
/**
 * Server render for focused-schools/beliefs.
 *
 * Wraps rail-text.php in the scroll-held container, exactly as the hardcoded
 * template did — home-hold.js finds it by the same data attribute.
 *
 * The image strip only renders when one or more valid images have been
 * explicitly uploaded through the block's image attributes.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_body = isset( $attributes['body'] )
	? trim( (string) $attributes['body'] )
	: '';

/*
 * Build the image strip from uploaded attachment IDs only.
 *
 * No fallback/placeholder images are used. If an image has not been
 * uploaded, that slot is simply skipped.
 */
$fs_strip = array();

foreach ( array( 'strip1', 'strip2', 'strip3' ) as $fs_strip_key ) {
	$fs_image_id = isset( $attributes[ $fs_strip_key . 'Id' ] )
		? absint( $attributes[ $fs_strip_key . 'Id' ] )
		: 0;

	if ( ! $fs_image_id || ! wp_attachment_is_image( $fs_image_id ) ) {
		continue;
	}

	$fs_image_url = wp_get_attachment_image_url( $fs_image_id, 'full' );

	if ( ! $fs_image_url ) {
		continue;
	}

	$fs_strip[] = array(
		'url' => $fs_image_url,
		'alt' => isset( $attributes[ $fs_strip_key . 'Alt' ] )
			? (string) $attributes[ $fs_strip_key . 'Alt' ]
			: '',
	);
}
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
				'body'      => '' !== $fs_body
					? preg_split( '/\r\n\r\n|\n\n/', $fs_body )
					: array(),
				'emphasis'  => isset( $attributes['emphasis'] ) ? $attributes['emphasis'] : '',
				'cta_label' => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
				'cta_url'   => ! empty( $attributes['ctaUrl'] )
					? $attributes['ctaUrl']
					: home_url( '/about-our-mission-vision/' ),
			)
		);
		?>
	</div>
</div>

<?php if ( ! empty( $fs_strip ) ) : ?>
	<div
		class="fs-home__strip"
		aria-label="<?php esc_attr_e( 'Focused Schools leadership retreat', 'focused-schools' ); ?>"
	>
		<?php foreach ( $fs_strip as $fs_photo ) : ?>
			<figure>
				<img
					src="<?php echo esc_url( $fs_photo['url'] ); ?>"
					alt="<?php echo esc_attr( $fs_photo['alt'] ); ?>"
					loading="lazy"
				/>
			</figure>
		<?php endforeach; ?>
	</div>
<?php endif; ?>