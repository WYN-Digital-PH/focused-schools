<?php
/**
 * Component: Service Card (fs_service display integration).
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Meta key literals below intentionally duplicate (rather than reference)
 * FocusedSchoolsCore\Modules\Services\Meta so this template never fatals if
 * the plugin is deactivated — get_post_meta() simply returns '' instead.
 * Keep these in sync with that class's PREFIX/field keys.
 *
 * Always renders with id="{post_name}" (the service's own slug) so
 * /services/#{slug}-style deep links land on this card — see
 * docs/page-specs/services.md §4. scroll-margin-top in service-card.css
 * keeps the anchored card clear of the header on jump.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id = $fs_post->ID;
$fs_tagline = get_post_meta( $fs_post_id, '_fs_service_tagline', true );
$fs_accent  = get_post_meta( $fs_post_id, '_fs_service_accent_role', true );
$fs_accent  = in_array( $fs_accent, array( 'strategy', 'leadership', 'capacity' ), true ) ? $fs_accent : 'strategy';
?>
<div class="fs-card fs-service-card fs-card--accent-<?php echo esc_attr( $fs_accent ); ?>" id="<?php echo esc_attr( $fs_post->post_name ); ?>">
	<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
		<div class="fs-card__media">
			<?php echo get_the_post_thumbnail( $fs_post_id, 'medium', array( 'class' => 'fs-card__image' ) ); ?>
		</div>
	<?php endif; ?>
	<div class="fs-card__body">
		<span class="fs-service-card__bar" aria-hidden="true"></span>
		<?php if ( $fs_tagline ) : ?>
			<p class="fs-card__eyebrow"><?php echo esc_html( $fs_tagline ); ?></p>
		<?php endif; ?>
		<h3 class="fs-card__heading"><?php echo esc_html( get_the_title( $fs_post_id ) ); ?></h3>
		<div class="fs-card__excerpt"><?php echo esc_html( get_the_excerpt( $fs_post_id ) ); ?></div>
	</div>
</div>
