<?php
/**
 * Server render for focused-schools/service-index.
 *
 * Runs the same query as the lanes below, so the two can never disagree
 * about which services exist or their order. Editors manage the list
 * entirely through the fs_service records.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_services = focused_schools_service_query();
$fs_anchor   = isset( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
?>
<section
	class="fs-services__section fs-container fs-container--shell"
	<?php echo $fs_anchor ? 'id="' . esc_attr( $fs_anchor ) . '"' : ''; ?>
	aria-labelledby="fs-services-heading"
>
	<?php
	get_template_part(
		'template-parts/components/section-heading',
		null,
		array(
			'eyebrow'       => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
			'heading'       => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
			'description'   => isset( $attributes['description'] ) ? $attributes['description'] : '',
			'heading_level' => 2,
			'heading_id'    => 'fs-services-heading',
		)
	);

	if ( $fs_services ) {
		get_template_part(
			'template-parts/components/service-list',
			null,
			array(
				'posts'     => $fs_services,
				'variant'   => 'index',
				'nav_label' => isset( $attributes['navLabel'] ) ? $attributes['navLabel'] : '',
			)
		);
	} elseif ( ! empty( $attributes['emptyText'] ) ) {
		printf( '<p class="fs-services__empty">%s</p>', esc_html( $attributes['emptyText'] ) );
	}
	?>
</section>
