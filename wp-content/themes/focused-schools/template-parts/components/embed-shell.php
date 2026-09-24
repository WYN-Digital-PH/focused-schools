<?php
/**
 * Component: Embed shell.
 *
 * Contract ($args):
 * - title    (string) the show or service name
 * - kicker   (string) e.g. "Hosted on Buzzsprout"
 * - mark_url (string) small tile image
 * - inner    (string) pre-rendered embed markup
 *
 * A branded frame for a third-party player. It wraps the vendor's own markup
 * and never alters it: Buzzsprout keeps rendering exactly what it renders
 * today, and only the container around it belongs to this design.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_title  = isset( $args['title'] ) ? $args['title'] : '';
$fs_kicker = isset( $args['kicker'] ) ? $args['kicker'] : '';
$fs_mark   = isset( $args['mark_url'] ) ? $args['mark_url'] : '';
$fs_inner  = isset( $args['inner'] ) ? $args['inner'] : '';

if ( '' === trim( (string) $fs_inner ) ) {
	return;
}
?>
<div class="fs-embed">
	<?php if ( $fs_title ) : ?>
		<div class="fs-embed__head">
			<div class="fs-embed__id">
				<?php if ( $fs_mark ) : ?>
					<span class="fs-embed__tile" aria-hidden="true">
						<img src="<?php echo esc_url( $fs_mark ); ?>" alt="" />
					</span>
				<?php endif; ?>
				<div>
					<strong><?php echo esc_html( $fs_title ); ?></strong>
					<?php if ( $fs_kicker ) : ?>
						<em><?php echo esc_html( $fs_kicker ); ?></em>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="fs-embed__body">
		<?php echo $fs_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-rendered trusted embed markup supplied by the calling template. ?>
	</div>
</div>
