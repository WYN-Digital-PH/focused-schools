<?php
/**
 * Component: Service List (fs_service display integration — numbered row layout).
 *
 * Contract ($args):
 * - posts (WP_Post[], required)
 *
 * Distinct from service-card.php (grid layout, used by the Services page):
 * the Home page's design uses a numbered list-row layout instead. Reuses
 * the exact same fs_service meta keys (duplicated rather than referenced,
 * same reasoning as service-card.php — never fatals if the plugin is
 * deactivated).
 *
 * Renders nothing if $posts is empty — the caller is responsible for
 * deciding what to show instead (see front-page.php's empty-state).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_posts = isset( $args['posts'] ) && is_array( $args['posts'] ) ? $args['posts'] : array();

if ( empty( $fs_posts ) ) {
	return;
}
?>
<div class="fs-service-list">
	<?php
	$fs_index = 0;
	foreach ( $fs_posts as $fs_post ) :
		$fs_post = get_post( $fs_post );

		if ( ! $fs_post instanceof WP_Post ) {
			continue;
		}

		++$fs_index;
		$fs_tagline = get_post_meta( $fs_post->ID, '_fs_service_tagline', true );

		// fs_service has no public URL of its own (publicly_queryable => false,
		// same as service-card.php) — services live as anchored cards on the
		// real /services/ page, matching that component's id="{post_name}".
		$fs_url = home_url( '/services/#' . $fs_post->post_name );
		?>
		<a class="fs-service-list__row" href="<?php echo esc_url( $fs_url ); ?>">
			<span class="fs-service-list__index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
			<div class="fs-service-list__title-group">
				<h3><?php echo esc_html( get_the_title( $fs_post ) ); ?></h3>
				<?php if ( $fs_tagline ) : ?>
					<p class="fs-service-list__promise"><?php echo esc_html( $fs_tagline ); ?></p>
				<?php endif; ?>
			</div>
			<p class="fs-service-list__description"><?php echo esc_html( get_the_excerpt( $fs_post ) ); ?></p>
			<span class="fs-service-list__arrow" aria-hidden="true">
				<svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6" /></svg>
			</span>
		</a>
	<?php endforeach; ?>
</div>
