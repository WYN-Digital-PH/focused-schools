<?php
/**
 * Component: Post breadcrumb.
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Plain links to real URLs. Yoast, when active, prints its own breadcrumb
 * schema from its settings; this is presentational trail only and adds no
 * competing structured data, so the two cannot disagree about the canonical
 * hierarchy.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_blog = (int) get_option( 'page_for_posts' );
$fs_cats = get_the_category( $fs_post->ID );
?>
<nav class="fs-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'focused-schools' ); ?>">
	<p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'focused-schools' ); ?></a>
		<span aria-hidden="true">/</span>
		<?php if ( $fs_blog ) : ?>
			<a href="<?php echo esc_url( get_permalink( $fs_blog ) ); ?>"><?php echo esc_html( get_the_title( $fs_blog ) ); ?></a>
			<span aria-hidden="true">/</span>
		<?php endif; ?>
		<?php if ( $fs_cats ) : ?>
			<a href="<?php echo esc_url( get_category_link( $fs_cats[0]->term_id ) ); ?>"><?php echo esc_html( $fs_cats[0]->name ); ?></a>
			<span aria-hidden="true">/</span>
		<?php endif; ?>
		<span aria-current="page"><?php echo esc_html( get_the_title( $fs_post ) ); ?></span>
	</p>
</nav>
