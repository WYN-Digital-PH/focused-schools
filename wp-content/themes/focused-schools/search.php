<?php
/**
 * Search results.
 *
 * Core's own search route, so the blog's search field reaches every post
 * rather than only the ones already on screen. Always native: a results
 * listing has no single post to key an Elementor check off, exactly as with
 * the other archives.
 *
 * The main query is untouched — this only presents what core searched.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fs_term = get_search_query();
?>

<main id="content" class="fs-blog">
	<section class="fs-blog__section fs-blog__intro">
		<div class="fs-container fs-container--shell">
			<div class="fs-eyebrow-row">
				<span class="fs-rule fs-rule--lime" aria-hidden="true"></span>
				<p class="fs-eyebrow"><?php esc_html_e( 'Search', 'focused-schools' ); ?></p>
			</div>

			<h1 class="fs-blog__title">
				<?php
				printf(
					/* translators: %s: the search term. */
					esc_html__( 'Results for %s', 'focused-schools' ),
					'<strong>' . esc_html( $fs_term ) . '</strong>'
				);
				?>
			</h1>

			<?php get_template_part( 'template-parts/components/blog-filters', null, array( 'search_term' => $fs_term ) ); ?>
		</div>
	</section>

	<?php
	get_template_part(
		'template-parts/components/post-list',
		null,
		array(
			'eyebrow' => __( 'Results', 'focused-schools' ),
			'heading' => __( 'What we found.', 'focused-schools' ),
			'feature' => false,
			'empty'   => __( 'No articles match that search yet.', 'focused-schools' ),
		)
	);
	?>
</main>

<?php
get_footer();
