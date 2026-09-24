<?php
/**
 * Component: Episode spotlight.
 *
 * Contract ($args):
 * - eyebrow  (string)
 * - title    (string)
 * - body     (string)
 * - mark_url (string) cover art shown in the media panel
 * - inner    (string) pre-rendered player markup for this one episode
 *
 * The teal panel the approved design leads the podcast page with, so
 * listening can start without scrolling. The player inside is the vendor's
 * own; this component owns only the panel around it.
 *
 * Renders nothing without a player, rather than showing an empty teal slab
 * with a heading and no way to listen.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_title   = isset( $args['title'] ) ? $args['title'] : '';
$fs_body    = isset( $args['body'] ) ? $args['body'] : '';
$fs_mark    = isset( $args['mark_url'] ) ? $args['mark_url'] : '';
$fs_inner   = isset( $args['inner'] ) ? $args['inner'] : '';

if ( '' === trim( (string) $fs_inner ) || '' === trim( (string) $fs_title ) ) {
	return;
}
?>
<article class="fs-spotlight fs-spotlight--audio">
	<div class="fs-spotlight__copy">
		<?php if ( $fs_eyebrow ) : ?>
			<p class="fs-eyebrow fs-eyebrow--on-dark"><?php echo esc_html( $fs_eyebrow ); ?></p>
		<?php endif; ?>

		<h2><?php echo esc_html( $fs_title ); ?></h2>

		<?php if ( $fs_body ) : ?>
			<p><?php echo esc_html( $fs_body ); ?></p>
		<?php endif; ?>

		<div class="fs-spotlight__player">
			<?php echo $fs_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-rendered trusted embed markup supplied by the calling template. ?>
		</div>
	</div>

	<?php if ( $fs_mark ) : ?>
		<div class="fs-spotlight__media">
			<img src="<?php echo esc_url( $fs_mark ); ?>" alt="" aria-hidden="true" />
		</div>
	<?php endif; ?>
</article>
