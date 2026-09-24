<?php
/**
 * Component: Post hero.
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * The article's own title block: category and date badges, the title, the
 * standfirst, and the byline. The standfirst uses the post's excerpt, which
 * is auto-generated when no manual one is set, so an editor gets a sensible
 * opening without extra work and can override it by writing one.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_id    = $fs_post->ID;
$fs_cats  = get_the_category( $fs_id );
$fs_lede  = get_the_excerpt( $fs_post );
$fs_by    = (int) $fs_post->post_author;
$fs_name  = get_the_author_meta( 'display_name', $fs_by );
$fs_desc  = get_the_author_meta( 'description', $fs_by );
$fs_words = str_word_count( wp_strip_all_tags( $fs_post->post_content ) );
$fs_mins  = max( 1, (int) round( $fs_words / 200 ) );
?>
<section class="fs-post-hero">
	<div class="fs-container fs-container--shell">
		<?php get_template_part( 'template-parts/components/post-breadcrumb', null, array( 'post' => $fs_post ) ); ?>

		<?php if ( has_post_thumbnail( $fs_id ) ) : ?>
			<div class="fs-post-hero__media">
				<?php
				echo get_the_post_thumbnail(
					$fs_id,
					'full',
					array(
						'class'         => 'fs-post-hero__image',
						'alt'           => '',
						'fetchpriority' => 'high',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<div class="fs-post-hero__slab">
			<?php if ( $fs_cats ) : ?>
				<div class="fs-metas">
					<?php foreach ( array_slice( $fs_cats, 0, 2 ) as $fs_cat ) : ?>
						<a class="fs-meta" href="<?php echo esc_url( get_category_link( $fs_cat->term_id ) ); ?>"><?php echo esc_html( $fs_cat->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="fs-post-hero__title"><?php echo esc_html( get_the_title( $fs_id ) ); ?></h1>

			<?php if ( $fs_lede ) : ?>
				<p class="fs-post-hero__lede"><?php echo esc_html( $fs_lede ); ?></p>
			<?php endif; ?>

			<div class="fs-byline">
				<?php echo get_avatar( $fs_by, 52, '', '', array( 'class' => 'fs-byline__avatar' ) ); ?>
				<div>
					<p class="fs-byline__name"><?php echo esc_html( $fs_name ); ?></p>
					<?php if ( $fs_desc ) : ?>
						<p class="fs-byline__line"><?php echo esc_html( wp_trim_words( $fs_desc, 14 ) ); ?></p>
					<?php endif; ?>
				</div>
				<p class="fs-byline__when">
					<time datetime="<?php echo esc_attr( get_the_date( 'c', $fs_id ) ); ?>"><?php echo esc_html( get_the_date( '', $fs_id ) ); ?></time>
					<span aria-hidden="true">&middot;</span>
					<?php
					printf(
						/* translators: %d: estimated reading time in minutes. */
						esc_html( _n( '%d min read', '%d min read', $fs_mins, 'focused-schools' ) ),
						(int) $fs_mins
					);
					?>
				</p>
			</div>
		</div>
	</div>
</section>
