<?php
/**
 * Component: Blog search and category filters.
 *
 * Contract ($args):
 * - search_term (string) current search term, to keep the field filled
 * - active_term (int) term_id of the category being viewed, or 0
 *
 * Both controls are plain links and a plain GET form, so they use
 * WordPress's own routing rather than filtering client-side:
 *
 * - the search posts `s` to the site root, which is core's search route;
 * - each chip is a real category archive URL.
 *
 * That matters here. The blog has far more posts than one page, so
 * filtering the rendered page in JavaScript would only ever search the ten
 * posts already on screen. Going through core's own queries means every
 * result is reachable, pagination keeps working, and the URLs stay
 * shareable — and nothing about the blog's own query is modified.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_search = isset( $args['search_term'] ) ? (string) $args['search_term'] : '';
$fs_active = isset( $args['active_term'] ) ? (int) $args['active_term'] : 0;
$fs_blog   = (int) get_option( 'page_for_posts' );
$fs_all    = $fs_blog ? get_permalink( $fs_blog ) : home_url( '/' );

$fs_cats = get_categories(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 8,
		'hide_empty' => true,
	)
);

/*
 * Filters are only worth showing when they can actually divide the archive.
 * A site whose posts all sit in one category — the default "Uncategorized"
 * included — would otherwise get a row of chips that filters nothing, which
 * reads as broken rather than as a feature. Categorise the posts and the row
 * appears by itself.
 */
if ( count( $fs_cats ) < 2 ) {
	$fs_cats = array();
}
?>
<div class="fs-blogbar">
	<form class="fs-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="fs-blog-search"><?php esc_html_e( 'Search articles', 'focused-schools' ); ?></label>
		<input
			class="fs-input"
			id="fs-blog-search"
			type="search"
			name="s"
			value="<?php echo esc_attr( $fs_search ); ?>"
			placeholder="<?php esc_attr_e( 'Search articles, topics, or keywords', 'focused-schools' ); ?>"
		/>
		<?php // Keep results to articles; the site's other content has its own pages. ?>
		<input type="hidden" name="post_type" value="post" />
		<button class="fs-btn fs-btn--primary" type="submit">
			<?php esc_html_e( 'Search', 'focused-schools' ); ?>
			<span aria-hidden="true">&rarr;</span>
		</button>
	</form>

	<?php if ( $fs_cats ) : ?>
		<nav class="fs-filters__chips" aria-label="<?php esc_attr_e( 'Filter articles by category', 'focused-schools' ); ?>">
			<a class="fs-chip" href="<?php echo esc_url( $fs_all ); ?>"<?php echo 0 === $fs_active ? ' aria-current="page"' : ''; ?>>
				<?php esc_html_e( 'All', 'focused-schools' ); ?>
			</a>
			<?php foreach ( $fs_cats as $fs_cat ) : ?>
				<a
					class="fs-chip"
					href="<?php echo esc_url( get_category_link( $fs_cat->term_id ) ); ?>"
					<?php echo $fs_active === (int) $fs_cat->term_id ? ' aria-current="page"' : ''; ?>
				><?php echo esc_html( $fs_cat->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>
</div>
