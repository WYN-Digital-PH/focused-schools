<?php
/**
 * Component: Post Feature (the lead article on the blog archive).
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * The wide card the approved design leads with. Like post-card.php it always
 * renders an excerpt-style presentation regardless of how the post itself
 * was authored — a listing shows a preview, never the post's own layout, so
 * an Elementor-built post is summarised here exactly like any other. See
 * docs/blog-coexistence.md.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_id      = $fs_post->ID;
$fs_url     = get_permalink( $fs_id );
$fs_cats    = get_the_category( $fs_id );
$fs_excerpt = get_the_excerpt( $fs_post );
?>
<article class="fs-feature">
	<?php if ( has_post_thumbnail( $fs_id ) ) : ?>
		<a class="fs-feature__media" href="<?php echo esc_url( $fs_url ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			echo get_the_post_thumbnail(
				$fs_id,
				'large',
				array(
					'alt'     => '',
					'loading' => 'lazy',
				)
			);
			?>
		</a>
	<?php endif; ?>

	<div class="fs-feature__copy">
		<?php if ( $fs_cats ) : ?>
			<p class="fs-eyebrow fs-feature__kicker"><?php echo esc_html( $fs_cats[0]->name ); ?></p>
		<?php endif; ?>

		<h3 class="fs-feature__title">
			<a href="<?php echo esc_url( $fs_url ); ?>"><?php echo esc_html( get_the_title( $fs_id ) ); ?></a>
		</h3>

		<?php if ( $fs_excerpt ) : ?>
			<p class="fs-feature__excerpt"><?php echo esc_html( $fs_excerpt ); ?></p>
		<?php endif; ?>

		<p class="fs-feature__meta">
			<?php
			printf(
				/* translators: 1: author name, 2: publication date. */
				esc_html__( 'By %1$s on %2$s', 'focused-schools' ),
				esc_html( get_the_author_meta( 'display_name', $fs_post->post_author ) ),
				esc_html( get_the_date( '', $fs_id ) )
			);
			?>
		</p>

		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'label' => __( 'Read the article', 'focused-schools' ),
				'url'   => $fs_url,
				'style' => 'primary',
			)
		);
		?>
	</div>
</article>
