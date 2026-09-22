<?php
/**
 * Component: Statistics counter block.
 *
 * Contract ($args):
 * - stats (array, required) each item: { value (number), suffix (string), unit (string), label (string) }
 * - on_teal (bool) true = teal-ground palette (coral rule → gold rule + lime unit
 *   text is inverted for light-ground contrast; see statistics-counter.css), default false
 *
 * The real final number is always in the HTML source (see fs-stats__number
 * below) so no-JS and screen-reader users get the correct value immediately.
 * assets/js/components/statistics-counter.js only animates the visual count
 * up to that number, and skips the animation entirely under
 * prefers-reduced-motion.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_stats   = isset( $args['stats'] ) && is_array( $args['stats'] ) ? $args['stats'] : array();
$fs_on_teal = ! empty( $args['on_teal'] );

if ( empty( $fs_stats ) ) {
	return;
}
?>
<div class="fs-stats<?php echo $fs_on_teal ? ' fs-stats--on-teal' : ''; ?>" data-fs-stats>
	<?php
	foreach ( $fs_stats as $fs_stat ) :
		$fs_value  = isset( $fs_stat['value'] ) ? $fs_stat['value'] : 0;
		$fs_suffix = isset( $fs_stat['suffix'] ) ? $fs_stat['suffix'] : '';
		$fs_unit   = isset( $fs_stat['unit'] ) ? $fs_stat['unit'] : '';
		$fs_label  = isset( $fs_stat['label'] ) ? $fs_stat['label'] : '';
		?>
		<article class="fs-stats__item" role="group" <?php echo $fs_label ? 'aria-label="' . esc_attr( $fs_label ) . '"' : ''; ?>>
			<p class="fs-stats__fig">
				<span class="fs-stats__value" data-fs-count-to="<?php echo esc_attr( $fs_value ); ?>">
					<span class="fs-stats__number"><?php echo esc_html( $fs_value ); ?></span><?php echo $fs_suffix ? esc_html( $fs_suffix ) : ''; ?>
				</span>
				<?php if ( $fs_unit ) : ?>
					<strong class="fs-stats__unit"><?php echo esc_html( $fs_unit ); ?></strong>
				<?php endif; ?>
			</p>
			<?php if ( $fs_label ) : ?>
				<h3 class="fs-stats__label"><?php echo esc_html( $fs_label ); ?></h3>
			<?php endif; ?>
		</article>
	<?php endforeach; ?>
</div>
