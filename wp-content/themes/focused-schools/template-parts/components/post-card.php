<?php
/**
 * Component: Post Card (native WP `post` display).
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Generic card for the standard `post` type — used in the archive/blog
 * index loop (home.php, archive.php) and for related posts (single.php).
 * Always renders an excerpt-style card regardless of whether the post's
 * own content was authored with Elementor — see docs/blog-coexistence.md
 * for why that's the correct behavior for a listing context.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id   = $fs_post->ID;
$fs_permalink = get_permalink( $fs_post_id );
$fs_excerpt   = get_the_excerpt( $fs_post_id );
?>
<article class="fs-card fs-post-card">
	<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
		<a href="<?php echo esc_url( $fs_permalink ); ?>" class="fs-card__media">
			<?php echo get_the_post_thumbnail( $fs_post_id, 'medium', array( 'class' => 'fs-card__image' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="fs-card__body">
		<p class="fs-post-card__date"><?php echo esc_html( get_the_date( '', $fs_post_id ) ); ?></p>
		<h3 class="fs-card__heading">
			<a href="<?php echo esc_url( $fs_permalink ); ?>"><?php echo esc_html( get_the_title( $fs_post_id ) ); ?></a>
		</h3>
		<?php if ( $fs_excerpt ) : ?>
			<div class="fs-card__excerpt"><?php echo esc_html( $fs_excerpt ); ?></div>
		<?php endif; ?>
	</div>
</article>
