<?php
/**
 * Component: Link cards (Contact page "Get to know how we work first").
 *
 * Contract ($args):
 * - cards (array, required) each: title, note, cta (labels), url (link),
 *   accent ('cerulean'|'coral'|'raspberry'|'lime') colours the short bar.
 *
 * Four-up grid that steps to two columns at 1023px and one at 767px. Each card
 * is a single link, so the whole card is the click target.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_cards = isset( $args['cards'] ) && is_array( $args['cards'] ) ? $args['cards'] : array();

if ( empty( $fs_cards ) ) {
	return;
}
?>
<div class="fs-link-cards">
	<?php
	foreach ( $fs_cards as $fs_card ) :
		if ( empty( $fs_card['title'] ) || empty( $fs_card['url'] ) ) {
			continue;
		}

		$fs_accent = isset( $fs_card['accent'] ) && in_array( $fs_card['accent'], array( 'cerulean', 'coral', 'raspberry', 'lime' ), true ) ? $fs_card['accent'] : 'cerulean';
		?>
		<a class="fs-link-cards__card" href="<?php echo esc_url( $fs_card['url'] ); ?>">
			<span>
				<span class="fs-link-cards__bar fs-link-cards__bar--<?php echo esc_attr( $fs_accent ); ?>" aria-hidden="true"></span>
				<strong class="fs-link-cards__title"><?php echo esc_html( $fs_card['title'] ); ?></strong>
				<?php if ( ! empty( $fs_card['note'] ) ) : ?>
					<span class="fs-link-cards__note"><?php echo esc_html( $fs_card['note'] ); ?></span>
				<?php endif; ?>
			</span>
			<?php if ( ! empty( $fs_card['cta'] ) ) : ?>
				<span class="fs-link-cards__cta"><?php echo esc_html( $fs_card['cta'] ); ?> <span aria-hidden="true">&rarr;</span></span>
			<?php endif; ?>
		</a>
	<?php endforeach; ?>
</div>
