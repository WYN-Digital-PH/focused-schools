<?php
/**
 * Server render for focused-schools/pull-quote.
 *
 * Shows the first Testimonials record, so the quote stays editorial content
 * rather than template copy: reordering the records changes which quote
 * appears here, with no page edit.
 *
 * The reviews destination comes from Site Settings with the other global
 * business details, so it is set once for the whole site.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_quotes = get_posts(
	array(
		'post_type'      => 'fs_testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( ! $fs_quotes ) {
	return;
}
?>
<section class="fs-services__quote">
	<div class="fs-container fs-container--shell">
		<?php
		get_template_part(
			'template-parts/components/pull-quote',
			null,
			array(
				'post'       => $fs_quotes[0],
				'link_label' => isset( $attributes['linkLabel'] ) ? $attributes['linkLabel'] : '',
				'link_url'   => function_exists( 'focused_schools_get_setting' ) ? focused_schools_get_setting( 'google_reviews_url' ) : '',
			)
		);
		?>
	</div>
</section>
