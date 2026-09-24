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
$fs_map    = array();
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

			// Split by partnership status for the map's legend.
			$fs_current  = array();
			$fs_previous = array();

			foreach ( $fs_query->posts as $fs_partner ) {
				$fs_status = get_post_meta( $fs_partner->ID, '_fs_partner_status', true );

				if ( 'previous' === $fs_status ) {
					$fs_previous[] = $fs_partner->post_title;
				} else {
					$fs_current[] = $fs_partner->post_title;
				}
			}

			$fs_map[] = array(
				'name'     => $fs_term->name,
				'lat'      => get_term_meta( $fs_term->term_id, '_fs_partner_state_lat', true ),
				'lng'      => get_term_meta( $fs_term->term_id, '_fs_partner_state_lng', true ),
				'current'  => $fs_current,
				'previous' => $fs_previous,
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

/*
 * The map sits above the district list and is enhancement only: the list
 * below carries every partner, so a failed tile service or JavaScript off
 * costs the reader nothing.
 */
get_template_part(
	'template-parts/components/partner-map',
	null,
	array(
		'states' => $fs_map,
		// A ?state= on the URL opens that state, so a view is shareable.
		'active' => isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only public filter, changes no state.
	)
);

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
