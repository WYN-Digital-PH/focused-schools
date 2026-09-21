<?php
/**
 * Component: Statistics counter block.
 *
 * Contract ($args):
 * - stats (array, required) each item: { value (number), suffix (string), label (string) }
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

$fs_stats = isset( $args['stats'] ) && is_array( $args['stats'] ) ? $args['stats'] : array();

if ( empty( $fs_stats ) ) {
	return;
}
?>
<div class="fs-stats" data-fs-stats>
	<?php
	foreach ( $fs_stats as $fs_stat ) :
		$fs_value  = isset( $fs_stat['value'] ) ? $fs_stat['value'] : 0;
		$fs_suffix = isset( $fs_stat['suffix'] ) ? $fs_stat['suffix'] : '';
		$fs_label  = isset( $fs_stat['label'] ) ? $fs_stat['label'] : '';
		?>
		<div class="fs-stats__item" role="group" <?php echo $fs_label ? 'aria-label="' . esc_attr( $fs_label ) . '"' : ''; ?>>
			<p class="fs-stats__value" data-fs-count-to="<?php echo esc_attr( $fs_value ); ?>">
				<span class="fs-stats__number"><?php echo esc_html( $fs_value ); ?></span>
				<?php
				if ( $fs_suffix ) :
					?>
					<span class="fs-stats__suffix"><?php echo esc_html( $fs_suffix ); ?></span><?php endif; ?>
			</p>
			<?php if ( $fs_label ) : ?>
				<p class="fs-stats__label"><?php echo esc_html( $fs_label ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
