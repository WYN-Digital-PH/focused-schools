<?php
/**
 * Component: Service Index (fs_service display integration — lane-jump
 * row layout, used only by the Services page's "How we help" index).
 *
 * Contract ($args):
 * - posts (WP_Post[], required)
 *
 * Distinct from service-list.php (the Home page's own row layout — two
 * text fields, right-arrow, `data-srow` in the .dc sources) and
 * service-card.php (grid): this page's own index (`data-index-row`) has a
 * single lead line and a down-arrow "go" tile, and jumps to each service's
 * own lane further down this same page (`#{slug}`), not an external URL.
 * Reuses the same `_fs_service_tagline` meta key as its siblings — same
 * "duplicated, not referenced" reasoning, so this degrades gracefully
 * rather than fataling if the plugin is deactivated.
 *
 * Renders nothing if $posts is empty — the caller decides what to show
 * instead (see page-services.php's empty-state).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_posts = isset( $args['posts'] ) && is_array( $args['posts'] ) ? $args['posts'] : array();

if ( empty( $fs_posts ) ) {
	return;
}
?>
<nav class="fs-service-index" aria-label="<?php esc_attr_e( 'Jump to a service', 'focused-schools' ); ?>">
	<?php
	$fs_index = 0;
	foreach ( $fs_posts as $fs_post ) :
		$fs_post = get_post( $fs_post );

		if ( ! $fs_post instanceof WP_Post ) {
			continue;
		}

		++$fs_index;
		$fs_tagline = get_post_meta( $fs_post->ID, '_fs_service_tagline', true );
		?>
		<a class="fs-service-index__row" href="<?php echo esc_url( '#' . $fs_post->post_name ); ?>">
			<span class="fs-service-index__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
			<h3 class="fs-service-index__title"><?php echo esc_html( get_the_title( $fs_post ) ); ?></h3>
			<?php if ( $fs_tagline ) : ?>
				<p class="fs-service-index__lead"><?php echo esc_html( $fs_tagline ); ?></p>
			<?php endif; ?>
			<span class="fs-service-index__go" aria-hidden="true">&darr;</span>
		</a>
	<?php endforeach; ?>
</nav>
