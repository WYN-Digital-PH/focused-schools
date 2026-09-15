<?php
/**
 * Component: Partner Strip / Logo grid.
 *
 * Contract ($args):
 * - logos   (array, required) each item: { image_id (int, req), name (string, req — used as alt text), url (string) }
 * - heading (string)
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_logos   = isset( $args['logos'] ) && is_array( $args['logos'] ) ? $args['logos'] : array();
$fs_heading = isset( $args['heading'] ) ? $args['heading'] : '';

if ( empty( $fs_logos ) ) {
	return;
}
?>
<div class="fs-partner-strip">
	<div class="fs-container">
		<?php if ( $fs_heading ) : ?>
			<p class="fs-partner-strip__heading"><?php echo esc_html( $fs_heading ); ?></p>
		<?php endif; ?>
		<ul class="fs-partner-strip__list">
			<?php
			foreach ( $fs_logos as $fs_logo ) :
				$fs_image_id = isset( $fs_logo['image_id'] ) ? absint( $fs_logo['image_id'] ) : 0;
				$fs_name     = isset( $fs_logo['name'] ) ? $fs_logo['name'] : '';
				$fs_url      = isset( $fs_logo['url'] ) ? $fs_logo['url'] : '';

				if ( ! $fs_image_id ) {
					continue;
				}

				$fs_image = wp_get_attachment_image(
					$fs_image_id,
					'medium',
					false,
					array(
						'class' => 'fs-partner-strip__logo',
						'alt'   => esc_attr( $fs_name ),
					)
				);
				?>
				<li class="fs-partner-strip__item">
					<?php if ( $fs_url ) : ?>
						<a href="<?php echo esc_url( $fs_url ); ?>" <?php echo $fs_name ? 'aria-label="' . esc_attr( $fs_name ) . '"' : ''; ?>>
							<?php echo $fs_image; ?>
						</a>
					<?php else : ?>
						<?php echo $fs_image; ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
