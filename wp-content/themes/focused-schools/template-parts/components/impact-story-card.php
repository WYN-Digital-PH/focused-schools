<?php
/**
 * Component: Impact Story Card (fs_impact_story display integration).
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Unlike Team/Service cards, fs_impact_story is a public post type with real
 * URLs, so this card links to get_permalink(). Meta key literals mirror
 * FocusedSchoolsCore\Modules\Impact_Stories\Meta so this template degrades
 * gracefully if the plugin is deactivated.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id   = $fs_post->ID;
$fs_district  = get_post_meta( $fs_post_id, '_fs_impact_story_district_or_school', true );
$fs_state     = get_post_meta( $fs_post_id, '_fs_impact_story_state', true );
$fs_year      = get_post_meta( $fs_post_id, '_fs_impact_story_year', true );
$fs_featured  = get_post_meta( $fs_post_id, '_fs_impact_story_featured', true );
$fs_permalink = get_permalink( $fs_post_id );
?>
<article class="fs-card fs-impact-story-card<?php echo $fs_featured ? ' fs-impact-story-card--featured' : ''; ?>">
	<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
		<a href="<?php echo esc_url( $fs_permalink ); ?>" class="fs-card__media">
			<?php echo get_the_post_thumbnail( $fs_post_id, 'medium', array( 'class' => 'fs-card__image' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="fs-card__body">
		<?php if ( $fs_featured ) : ?>
			<p class="fs-impact-story-card__badge"><?php esc_html_e( 'Featured', 'focused-schools' ); ?></p>
		<?php endif; ?>
		<h3 class="fs-card__heading">
			<a href="<?php echo esc_url( $fs_permalink ); ?>"><?php echo esc_html( get_the_title( $fs_post_id ) ); ?></a>
		</h3>
		<?php
		$fs_meta_parts = array_filter( array( $fs_district, $fs_state, $fs_year ) );
		if ( $fs_meta_parts ) :
			?>
			<p class="fs-impact-story-card__meta"><?php echo esc_html( implode( ' &middot; ', $fs_meta_parts ) ); ?></p>
		<?php endif; ?>
		<div class="fs-card__excerpt"><?php echo esc_html( get_the_excerpt( $fs_post_id ) ); ?></div>
	</div>
</article>
