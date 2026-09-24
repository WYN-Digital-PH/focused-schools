<?php
/**
 * Component: Partner Map.
 *
 * Contract ($args):
 * - states (array, required) each: {
 *       name (string), lat (float), lng (float),
 *       current (string[]), previous (string[])
 *   }
 * - active (string) state name to open on load, from ?state= on the URL.
 *
 * Renders the container and the data only. Leaflet is initialised by
 * partner-map.js, so with JavaScript off — or if the tile service is
 * unreachable — the section simply does not appear and the district chip
 * list below it still carries every partner. The map is a way of reading the
 * list, never the only copy of it.
 *
 * Leaflet is vendored into the theme rather than loaded from a CDN, so the
 * site makes no third-party request for the library itself. Map tiles are
 * still fetched from the tile provider at view time — that is inherent to any
 * web map.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_states = isset( $args['states'] ) && is_array( $args['states'] ) ? $args['states'] : array();
$fs_active = isset( $args['active'] ) ? (string) $args['active'] : '';

// A state with no coordinates is simply not mapped.
$fs_states = array_values(
	array_filter(
		$fs_states,
		static function ( $fs_state ) {
			return ! empty( $fs_state['lat'] ) && ! empty( $fs_state['lng'] );
		}
	)
);

if ( empty( $fs_states ) ) {
	return;
}
?>
<div class="fs-partner-map">
	<div
		class="fs-partner-map__canvas"
		data-fs-partner-map
		data-fs-active="<?php echo esc_attr( $fs_active ); ?>"
		role="img"
		aria-label="<?php esc_attr_e( 'Map of the states where Focused Schools partners with districts. The same districts are listed below.', 'focused-schools' ); ?>"
	></div>

	<script type="application/json" data-fs-partner-map-data>
		<?php echo wp_json_encode( $fs_states ); ?>
	</script>

	<p class="fs-partner-map__legend">
		<span class="fs-partner-map__key fs-partner-map__key--current"><?php esc_html_e( 'Current partner', 'focused-schools' ); ?></span>
		<span class="fs-partner-map__key fs-partner-map__key--previous"><?php esc_html_e( 'Previous partner', 'focused-schools' ); ?></span>
		<span class="fs-partner-map__note"><?php esc_html_e( 'Pins mark the state, not a single campus.', 'focused-schools' ); ?></span>
	</p>
</div>
