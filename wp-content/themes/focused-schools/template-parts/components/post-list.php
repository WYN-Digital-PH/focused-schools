<?php
/**
 * Component: Post List (the article grid, count and pagination).
 *
 * Contract ($args):
 * - heading  (string) section heading, e.g. "Browse the archive."
 * - eyebrow  (string) label above it
 * - feature  (bool) present the first result as the lead article
 * - empty    (string) message when the query returned nothing
 *
 * Consumes the **main query** — it never runs one of its own, and never
 * alters one. That is the whole point: the blog's post count, ordering,
 * pagination and URLs stay exactly as WordPress produced them, and this
 * only decides how the results are presented. `the_posts_pagination()` is
 * core's, so page URLs cannot drift from what the query expects.
 *
 * The lead article is simply the first result of that same query, shown
 * differently, so no post is skipped, duplicated or queried twice.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_empty   = isset( $args['empty'] ) ? $args['empty'] : __( 'No articles found.', 'focused-schools' );

// Only lead on the first page: page two of an archive has no "latest".
$fs_feature = ! empty( $args['feature'] ) && ! is_paged();

if ( ! have_posts() ) :
	?>
	<section class="fs-blog__section fs-blog__section--paper">
		<div class="fs-container fs-container--shell">
			<div class="fs-blog__empty">
				<p><?php echo esc_html( $fs_empty ); ?></p>
				<p class="fs-small"><?php esc_html_e( 'Try a broader keyword, or browse everything we have published.', 'focused-schools' ); ?></p>
				<?php
				$fs_blog = (int) get_option( 'page_for_posts' );
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => __( 'See all articles', 'focused-schools' ),
						'url'   => $fs_blog ? get_permalink( $fs_blog ) : home_url( '/' ),
						'style' => 'secondary',
					)
				);
				?>
			</div>
		</div>
	</section>
	<?php
	return;
endif;

global $wp_query;
$fs_total = (int) $wp_query->found_posts;

if ( $fs_feature ) :
	the_post();
	?>
	<section class="fs-blog__section fs-blog__feature-section">
		<div class="fs-container fs-container--shell">
			<div class="fs-eyebrow-row">
				<span class="fs-rule fs-rule--gold" aria-hidden="true"></span>
				<p class="fs-eyebrow"><?php esc_html_e( 'Latest article', 'focused-schools' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/components/post-feature', null, array( 'post' => get_post() ) ); ?>
		</div>
	</section>
	<?php
endif;
?>
<section class="fs-blog__section fs-blog__section--paper" id="articles">
	<div class="fs-container fs-container--shell">
		<header class="fs-blog__head">
			<div>
				<?php if ( $fs_eyebrow ) : ?>
					<p class="fs-eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( $fs_heading ) : ?>
					<h2 class="fs-blog__heading"><?php echo esc_html( $fs_heading ); ?></h2>
				<?php endif; ?>
			</div>
			<p class="fs-count">
				<?php
				printf(
					/* translators: %d: total number of articles. */
					esc_html( _n( '%d article', '%d articles', $fs_total, 'focused-schools' ) ),
					(int) $fs_total
				);
				?>
			</p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="fs-blog__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/components/post-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>
		<?php endif; ?>

		<?php
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => __( 'Previous', 'focused-schools' ),
				'next_text'          => __( 'Next', 'focused-schools' ),
				'screen_reader_text' => __( 'Article pagination', 'focused-schools' ),
			)
		);
		?>
	</div>
</section>
