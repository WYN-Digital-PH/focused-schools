<?php
/**
 * Component: Reach cards.
 *
 * Contract ($args):
 * - cards (array) each: { title, body, label, url, rule }
 *
 * The four routes offered to a reader who is not ready to write yet. Each
 * card is one link, so the whole card is the target rather than a small
 * label inside it.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_cards = isset( $args['cards'] ) && is_array( $args['cards'] ) ? $args['cards'] : array();

$fs_cards = array_values(
	array_filter(
		$fs_cards,
		static function ( $fs_card ) {
			return ! empty( $fs_card['title'] ) && ! empty( $fs_card['url'] );
		}
	)
);

if ( empty( $fs_cards ) ) {
	return;
}
?>
<div class="fs-grid-4">
	<?php foreach ( $fs_cards as $fs_card ) : ?>
		<a class="fs-reach-card" href="<?php echo esc_url( $fs_card['url'] ); ?>">
			<span>
				<span class="fs-rule<?php echo ! empty( $fs_card['rule'] ) ? ' fs-rule--' . esc_attr( $fs_card['rule'] ) : ''; ?>" aria-hidden="true"></span>
				<strong><?php echo esc_html( $fs_card['title'] ); ?></strong>
				<?php if ( ! empty( $fs_card['body'] ) ) : ?>
					<span class="fs-small"><?php echo esc_html( $fs_card['body'] ); ?></span>
				<?php endif; ?>
			</span>
			<?php if ( ! empty( $fs_card['label'] ) ) : ?>
				<span class="fs-link">
					<?php echo esc_html( $fs_card['label'] ); ?>
					<span class="fs-link__arrow" aria-hidden="true">&rarr;</span>
				</span>
			<?php endif; ?>
		</a>
	<?php endforeach; ?>
</div>
