<?php
/**
 * Server render for focused-schools/where-we-work.
 *
 * The approved homepage shows the partner map and, under it, the districts we
 * are currently partnered with. Both come from the same Partners records, so
 * adding a district in wp-admin updates the map and the list together.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_all = focused_schools_partner_states();

if ( empty( $fs_all ) ) {
	return;
}

// The list is the current partnerships only; the map still plots every state.
$fs_current = array();

foreach ( $fs_all as $fs_state ) {
	if ( ! empty( $fs_state['current'] ) ) {
		$fs_current[] = array(
			'name'      => $fs_state['name'],
			'districts' => $fs_state['current'],
		);
	}
}

$fs_url = isset( $attributes['ctaUrl'] ) ? (string) $attributes['ctaUrl'] : '';
?>
<section class="fs-home__section fs-home__where">
	<div class="fs-container fs-container--shell">
		<?php
		get_template_part(
			'template-parts/components/where-we-work',
			null,
			array(
				'eyebrow'      => isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '',
				'heading'      => isset( $attributes['heading'] ) ? $attributes['heading'] : '',
				'list_eyebrow' => isset( $attributes['listEyebrow'] ) ? $attributes['listEyebrow'] : '',
				'map_states'   => $fs_all,
				// A ?state= on the URL opens that state, so a view is shareable.
				'map_active'   => isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only public filter, changes no state.
				'states'       => $fs_current,
				'cta_line'     => isset( $attributes['ctaLine'] ) ? $attributes['ctaLine'] : '',
				'cta_label'    => isset( $attributes['ctaLabel'] ) ? $attributes['ctaLabel'] : '',
				'cta_url'      => 0 === strpos( $fs_url, '/' ) ? home_url( $fs_url ) : $fs_url,
			)
		);
		?>
	</div>
</section>
