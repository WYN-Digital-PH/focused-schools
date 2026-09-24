<?php
/**
 * Category, tag, date and author archives.
 *
 * There is no single post or page to key an Elementor check off here, so
 * these routes are always native — unchanged from the original template, and
 * documented in docs/blog-coexistence.md.
 *
 * Presentation now matches the blog archive, but the main query is untouched:
 * the term, ordering, post count and every /page/N/ URL are exactly as
 * WordPress produced them.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fs_description = get_the_archive_description();
$fs_term        = is_category() ? (int) get_queried_object_id() : 0;
?>

<main id="content" class="fs-blog">
	<section class="fs-blog__section fs-blog__intro">
		<div class="fs-container fs-container--shell">
			<div class="fs-eyebrow-row">
				<span class="fs-rule fs-rule--lime" aria-hidden="true"></span>
				<p class="fs-eyebrow"><?php esc_html_e( 'Blog', 'focused-schools' ); ?></p>
			</div>

			<h1 class="fs-blog__title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h1>

			<?php if ( $fs_description ) : ?>
				<div class="fs-blog__lead"><?php echo wp_kses_post( $fs_description ); ?></div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/components/blog-filters', null, array( 'active_term' => $fs_term ) ); ?>
		</div>
	</section>

	<?php
	get_template_part(
		'template-parts/components/post-list',
		null,
		array(
			'eyebrow' => __( 'All articles', 'focused-schools' ),
			'heading' => __( 'Browse the archive.', 'focused-schools' ),
			'feature' => false,
			'empty'   => __( 'No articles found in this archive.', 'focused-schools' ),
		)
	);
	?>
</main>

<?php
get_footer();
