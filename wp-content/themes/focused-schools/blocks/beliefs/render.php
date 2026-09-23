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
