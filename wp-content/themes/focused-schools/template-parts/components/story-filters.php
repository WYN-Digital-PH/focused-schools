<?php
/**
 * Component: Story Filter Bar.
 *
 * Contract ($args):
 * - groups (array, required) each: {
 *       key   (string) query var, e.g. 'state'
 *       label (string) rail label
 *       name  (string) accessible name for the chip group
 *       terms (string[]) values that actually exist on published stories
 *   }
 * - active (array) current selection keyed by group key.
 *
 * The one genuinely new component on this page. Chips are real <button>s so
 * they are reachable and operable by keyboard, each group is a labelled
 * role="group", and every chip clears 44px.
 *
 * Chips are only ever built from values that exist on published stories, so
 * an option that would return nothing is never offered. The caller decides
 * that; this component just renders what it is given, and renders nothing at
 * all when there is nothing worth filtering.
 *
 * Progressive enhancement: the links carry a real href so filtering works
 * server-side with JavaScript off. story-filters.js upgrades them in place.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_groups = isset( $args['groups'] ) && is_array( $args['groups'] ) ? $args['groups'] : array();
$fs_active = isset( $args['active'] ) && is_array( $args['active'] ) ? $args['active'] : array();

$fs_groups = array_values(
	array_filter(
		$fs_groups,
		static function ( $fs_group ) {
			return ! empty( $fs_group['terms'] );
		}
	)
);

if ( empty( $fs_groups ) ) {
	return;
}

/**
 * Build the URL for one chip, preserving the other group's selection.
 *
 * @param array  $active Current selection.
 * @param string $key    Group being changed.
 * @param string $value  New value, '' for All.
 * @return string
 */
$fs_chip_url = static function ( $active, $key, $value ) {
	$query = array_filter( array_merge( $active, array( $key => $value ) ), 'strlen' );

	return $query ? add_query_arg( $query, get_permalink() ) : get_permalink();
};
?>
<div class="fs-story-filters" data-fs-story-filters>
	<?php foreach ( $fs_groups as $fs_group ) : ?>
		<?php
		$fs_key     = $fs_group['key'];
		$fs_current = isset( $fs_active[ $fs_key ] ) ? (string) $fs_active[ $fs_key ] : '';
		?>
		<div class="fs-story-filters__row">
			<p class="fs-story-filters__label" id="fs-filter-<?php echo esc_attr( $fs_key ); ?>"><?php echo esc_html( $fs_group['label'] ); ?></p>
			<div class="fs-story-filters__chips" role="group" aria-label="<?php echo esc_attr( $fs_group['name'] ); ?>">
				<a
					class="fs-chip<?php echo '' === $fs_current ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $fs_chip_url( $fs_active, $fs_key, '' ) ); ?>"
					data-fs-filter="<?php echo esc_attr( $fs_key ); ?>"
					data-fs-value=""
					<?php echo '' === $fs_current ? 'aria-current="true"' : ''; ?>
				><?php esc_html_e( 'All', 'focused-schools' ); ?></a>

				<?php foreach ( $fs_group['terms'] as $fs_term ) : ?>
					<a
						class="fs-chip<?php echo (string) $fs_term === $fs_current ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $fs_chip_url( $fs_active, $fs_key, (string) $fs_term ) ); ?>"
						data-fs-filter="<?php echo esc_attr( $fs_key ); ?>"
						data-fs-value="<?php echo esc_attr( $fs_term ); ?>"
						<?php echo (string) $fs_term === $fs_current ? 'aria-current="true"' : ''; ?>
					><?php echo esc_html( $fs_term ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
