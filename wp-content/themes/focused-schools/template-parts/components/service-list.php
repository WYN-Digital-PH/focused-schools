<?php
/**
 * Component: Service List (fs_service display integration — numbered row layout).
 *
 * Contract ($args):
 * - posts     (WP_Post[], required)
 * - variant   (string) 'index' draws the Services page's lane index: numeral,
 *             title, one-line promise, arrow. The default draws the Home
 *             page's wider row, which adds the description column.
 * - nav_label (string) when set, the list is wrapped in a <nav> with this as
 *             its accessible name. The Services page spec requires one
 *             ("Jump to a service"); the Home page's rows are part of the
 *             section's prose, so they stay a plain container.
 *
 * Distinct from service-card.php (grid layout): the Home and Services pages'
 * designs use a numbered list-row layout instead. Reuses the exact same
 * fs_service meta keys (duplicated rather than referenced, same reasoning as
 * service-card.php — never fatals if the plugin is deactivated).
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

$fs_is_index  = isset( $args['variant'] ) && 'index' === $args['variant'];
$fs_nav_label = isset( $args['nav_label'] ) ? trim( (string) $args['nav_label'] ) : '';
$fs_tag       = '' !== $fs_nav_label ? 'nav' : 'div';
$fs_classes   = 'fs-service-list' . ( $fs_is_index ? ' fs-service-list--index' : '' );

printf(
	'<%1$s class="%2$s"%3$s>',
	esc_attr( $fs_tag ),
	esc_attr( $fs_classes ),
	'' !== $fs_nav_label ? ' aria-label="' . esc_attr( $fs_nav_label ) . '"' : ''
);

$fs_index = 0;

foreach ( $fs_posts as $fs_post ) :
	$fs_post = get_post( $fs_post );

	if ( ! $fs_post instanceof WP_Post ) {
		continue;
	}

	++$fs_index;
	$fs_tagline = get_post_meta( $fs_post->ID, '_fs_service_tagline', true );

	/*
	 * fs_service has no public URL of its own (publicly_queryable => false,
	 * same as service-card.php) — services live as anchored lanes on the real
	 * /services/ page, matching service-lane.php's id="{post_name}". On the
	 * Services page itself the link is a bare fragment, so it scrolls rather
	 * than reloading the page it is already on.
	 */
	$fs_url = $fs_is_index
		? '#' . $fs_post->post_name
		: home_url( '/services/#' . $fs_post->post_name );
	?>
	<a class="fs-service-list__row" href="<?php echo esc_url( $fs_url ); ?>">
		<span class="fs-service-list__index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
		<?php if ( $fs_is_index ) : ?>
			<h3 class="fs-service-list__title"><?php echo esc_html( get_the_title( $fs_post ) ); ?></h3>
			<?php if ( $fs_tagline ) : ?>
				<p class="fs-service-list__promise"><?php echo esc_html( $fs_tagline ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<div class="fs-service-list__title-group">
				<h3><?php echo esc_html( get_the_title( $fs_post ) ); ?></h3>
				<?php if ( $fs_tagline ) : ?>
					<p class="fs-service-list__promise"><?php echo esc_html( $fs_tagline ); ?></p>
				<?php endif; ?>
			</div>
			<p class="fs-service-list__description"><?php echo esc_html( get_the_excerpt( $fs_post ) ); ?></p>
		<?php endif; ?>
		<span class="fs-service-list__arrow" aria-hidden="true">
			<svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6" /></svg>
		</span>
	</a>
	<?php
endforeach;

printf( '</%s>', esc_attr( $fs_tag ) );
