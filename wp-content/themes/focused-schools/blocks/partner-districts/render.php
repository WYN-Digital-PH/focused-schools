<?php
/**
 * Server render for focused-schools/partner-districts.
 *
 * Delegates to the same component the approved About layout used, so
 * block-built and component output cannot drift.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_badge = focused_schools_block_image( $attributes, 'badge', '/assets/img/chamber-badge.jpg', __( '2026 Proud Member — Manatee Chamber of Commerce', 'focused-schools' ) );
$fs_url   = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';

// Live query: adding a district in wp-admin adds a chip here, no page edit.
$fs_states = array();
$fs_terms  = get_terms(
	array(
		'taxonomy'   => 'fs_partner_state',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,
	)
);

if ( ! is_wp_error( $fs_terms ) ) {
	foreach ( $fs_terms as $fs_term ) {
		$fs_query = new WP_Query(
			array(
				'post_type'      => 'fs_partner',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- small, admin-managed taxonomy.
					array(
						'taxonomy' => 'fs_partner_state',
						'field'    => 'term_id',
						'terms'    => $fs_term->term_id,
					),
				),
			)
		);

		if ( $fs_query->have_posts() ) {
			$fs_states[] = array(
				'name'      => $fs_term->name,
				'districts' => wp_list_pluck( $fs_query->posts, 'post_title' ),
			);
		}
	}
}

if ( empty( $fs_states ) ) {
	?>
	<section class="fs-about__section fs-container fs-container--shell">
		<p class="fs-about__empty"><?php esc_html_e( 'Our partner district directory is being populated — please check back soon.', 'focused-schools' ); ?></p>
	</section>
	<?php
	return;
}

get_template_part(
	'template-parts/components/partner-districts',
	null,
	array(
		'eyebrow'      => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
		'heading'      => sprintf(
			/* translators: %s: emphasised tail of the heading. */
			__( '%1$s %2$s', 'focused-schools' ),
			isset( $attributes['heading'] ) ? $attributes['heading'] : '',
			'<strong>' . esc_html( isset( $attributes['headingEmphasis'] ) ? $attributes['headingEmphasis'] : '' ) . '</strong>'
		),
		'intro'        => isset( $attributes['intro'] ) ? $attributes['intro'] : '',
		'states'       => $fs_states,
		'badge_url'    => $fs_badge['url'],
		'badge_alt'    => $fs_badge['alt'],
		'note'         => sprintf(
			/* translators: %s: emphasised tail of the note. */
			__( '%1$s %2$s', 'focused-schools' ),
			isset( $attributes['note'] ) ? $attributes['note'] : '',
			'<strong>' . esc_html( isset( $attributes['noteEmphasis'] ) ? $attributes['noteEmphasis'] : '' ) . '</strong>'
		),
		'closing_text' => isset( $attributes['closingText'] ) ? $attributes['closingText'] : '',
		'cta_label'    => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
		'cta_url'      => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
	)
);
