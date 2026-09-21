<?php
/**
 * Blog posts index (home.php).
 *
 * WordPress uses this specific template — not page-{slug}.php — for the
 * "Posts page" configured via Settings > Reading (get_option('page_for_posts')),
 * which per this project is Page ID 1191 at /blog/. This is why the file is
 * named home.php rather than page-blog.php; see docs/blog-coexistence.md.
 *
 * Elementor coexistence: checks the Posts Page (1191) itself — if it was
 * built with Elementor (_elementor_edit_mode === 'builder'), only that
 * page's the_content() renders, exactly like every other page template's
 * Elementor branch. Otherwise, native rendering: heading, a post-card grid
 * over the main query, and the_posts_pagination() (WordPress core — never
 * hand-rolled, so pagination cannot be broken by this template).
 *
 * Every post in the loop renders as a native excerpt card regardless of
 * its own Elementor status — see docs/blog-coexistence.md for why that's
 * the correct behavior for a listing context, and the known limitation
 * around thin excerpts on Elementor-authored posts.
 *
 * Known limitation: could not be verified against the real Page 1191 or
 * real Elementor content in this local environment — verify against
 * staging/production. See docs/blog-coexistence.md.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fs_posts_page_id = (int) get_option( 'page_for_posts' );
$fs_posts_page    = $fs_posts_page_id ? get_post( $fs_posts_page_id ) : null;

$fs_is_elementor = $fs_posts_page instanceof WP_Post
	&& 'builder' === get_post_meta( $fs_posts_page->ID, '_elementor_edit_mode', true );

if ( $fs_is_elementor ) :
	?>
	<main id="content">
		<?php echo apply_filters( 'the_content', $fs_posts_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- standard the_content filter output, same trust level as the_content(). ?>
	</main>
	<?php
else :
	?>
	<main id="content">
		<div class="fs-container fs-container--wide fs-blog__header">
			<h1 class="fs-blog__title">
				<?php echo esc_html( $fs_posts_page ? get_the_title( $fs_posts_page ) : __( 'Blog', 'focused-schools' ) ); ?>
			</h1>
		</div>

		<div class="fs-container fs-container--wide fs-blog__list">
			<?php if ( have_posts() ) : ?>
				<div class="fs-card-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/components/post-card', null, array( 'post' => get_post() ) );
					endwhile;
					?>
				</div>

				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p class="fs-blog__empty"><?php esc_html_e( 'No posts found.', 'focused-schools' ); ?></p>
			<?php endif; ?>
		</div>
	</main>
	<?php
endif;

get_footer();
