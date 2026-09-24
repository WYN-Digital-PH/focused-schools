<?php
/**
 * Component: Podcast Subscribe strip.
 *
 * Contract ($args):
 * - label    (string) small heading above the row, e.g. "Subscribe anywhere"
 * - links    (array, required) each item: {
 *       label (string, required), url (string, required), kind (string)
 *   } — kind is the small qualifier under the name ("Audio", "Video", …).
 *
 * Renders nothing when no link has both a label and a URL, so a site that
 * has not filled in its podcast Site Settings simply omits the strip
 * instead of showing dead placeholders.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_label = isset( $args['label'] ) ? $args['label'] : '';
$fs_links = isset( $args['links'] ) && is_array( $args['links'] ) ? $args['links'] : array();

$fs_links = array_values(
	array_filter(
		$fs_links,
		static function ( $fs_link ) {
			return ! empty( $fs_link['label'] ) && ! empty( $fs_link['url'] );
		}
	)
);

if ( empty( $fs_links ) ) {
	return;
}
?>
<section class="fs-subscribe" aria-label="<?php echo esc_attr( $fs_label ? $fs_label : __( 'Subscribe to the podcast', 'focused-schools' ) ); ?>">
	<div class="fs-container fs-container--shell fs-subscribe__inner">
		<?php if ( $fs_label ) : ?>
			<p class="fs-subscribe__label"><?php echo esc_html( $fs_label ); ?></p>
		<?php endif; ?>
		<ul class="fs-subscribe__list">
			<?php foreach ( $fs_links as $fs_link ) : ?>
				<li class="fs-subscribe__item">
					<a class="fs-subscribe__link" href="<?php echo esc_url( $fs_link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="fs-subscribe__text">
							<strong class="fs-subscribe__name"><?php echo esc_html( $fs_link['label'] ); ?></strong>
							<?php if ( ! empty( $fs_link['kind'] ) ) : ?>
								<span class="fs-subscribe__kind"><?php echo esc_html( $fs_link['kind'] ); ?></span>
							<?php endif; ?>
						</span>
						<span class="fs-subscribe__arrow" aria-hidden="true">&rarr;</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
