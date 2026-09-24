<?php
/**
 * Blog archive — the page assigned as Settings → Reading → Posts page.
 *
 * WordPress uses home.php for that page, not page-{slug}.php, so this is the
 * file that governs /blog/.
 *
 * Elementor coexistence is unchanged from the original template: if the
 * Posts page itself is Elementor-built, its own saved output is rendered
 * untouched and none of the native markup below runs. See
 * docs/blog-coexistence.md.
 *
 * The native branch never modifies the main query — no pre_get_posts, no
 * posts_per_page override, no post exclusions — so the post count, ordering,
 * pagination and every /blog/page/N/ URL behave exactly as before. This
 * template only changes how those results are presented.
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
	$fs_title = $fs_posts_page ? get_the_title( $fs_posts_page ) : __( 'Blog', 'focused-schools' );
	?>
	<main id="content" class="fs-blog">
		<section class="fs-blog__section fs-blog__intro">
			<div class="fs-container fs-container--shell">
				<div class="fs-eyebrow-row">
					<span class="fs-rule fs-rule--lime" aria-hidden="true"></span>
					<p class="fs-eyebrow"><?php echo esc_html( $fs_title ); ?></p>
				</div>

				<h1 class="fs-blog__title">
					<?php esc_html_e( 'Ideas from leaders', 'focused-schools' ); ?>
					<strong><?php esc_html_e( 'doing the work.', 'focused-schools' ); ?></strong>
				</h1>

				<?php
				/*
				 * The Posts page's own content is the natural home for this
				 * standfirst, so an editor can change it without a deploy.
				 */
				$fs_intro = $fs_posts_page ? trim( (string) $fs_posts_page->post_content ) : '';

				if ( '' !== $fs_intro ) :
					?>
					<div class="fs-blog__lead"><?php echo apply_filters( 'the_content', $fs_intro ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- standard the_content filter output. ?></div>
					<?php
				else :
					?>
					<p class="fs-blog__lead"><?php esc_html_e( 'Ideas and inspiration from real leaders dedicated to enhancing leadership skills and empowering teams.', 'focused-schools' ); ?></p>
					<?php
				endif;

				get_template_part( 'template-parts/components/blog-filters' );
				?>
			</div>
		</section>

		<?php
		get_template_part(
			'template-parts/components/post-list',
			null,
			array(
				'eyebrow' => __( 'All articles', 'focused-schools' ),
				'heading' => __( 'Browse the archive.', 'focused-schools' ),
				'feature' => true,
				'empty'   => __( 'No articles have been published yet.', 'focused-schools' ),
			)
		);
		?>
	</main>
	<?php
endif;

get_footer();
