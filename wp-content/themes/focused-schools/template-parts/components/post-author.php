<?php
/**
 * Component: Author box.
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Renders nothing when the author has written no biography — an empty card
 * with just a name and an avatar is worse than no card at all.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_by   = (int) $fs_post->post_author;
$fs_desc = trim( (string) get_the_author_meta( 'description', $fs_by ) );

if ( '' === $fs_desc ) {
	return;
}
?>
<section class="fs-post-author">
	<div class="fs-container fs-container--shell">
		<div class="fs-authorbox">
			<?php echo get_avatar( $fs_by, 148, '', '', array( 'class' => 'fs-authorbox__photo' ) ); ?>
			<div>
				<p class="fs-eyebrow"><?php esc_html_e( 'Written by', 'focused-schools' ); ?></p>
				<p class="fs-authorbox__name"><?php echo esc_html( get_the_author_meta( 'display_name', $fs_by ) ); ?></p>
				<p class="fs-authorbox__bio"><?php echo esc_html( $fs_desc ); ?></p>
			</div>
		</div>
	</div>
</section>
