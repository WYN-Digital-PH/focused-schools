<?php
/**
 * Component: Team Card (fs_team_member display integration).
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Meta key literals mirror FocusedSchoolsCore\Modules\Team\Meta so this
 * template degrades gracefully (empty values) rather than fataling if the
 * plugin is deactivated.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id  = $fs_post->ID;
$fs_name     = get_the_title( $fs_post_id );
$fs_position = get_post_meta( $fs_post_id, '_fs_team_position', true );
$fs_quote    = get_post_meta( $fs_post_id, '_fs_team_quote', true );
$fs_linkedin = get_post_meta( $fs_post_id, '_fs_team_linkedin_url', true );
?>
<div class="fs-card fs-team-card">
	<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
		<div class="fs-card__media fs-team-card__media">
			<?php
			echo get_the_post_thumbnail(
				$fs_post_id,
				'medium',
				array(
					'class' => 'fs-card__image',
					'alt'   => esc_attr( $fs_name ),
				)
			);
			?>
		</div>
	<?php endif; ?>
	<div class="fs-card__body">
		<h3 class="fs-card__heading"><?php echo esc_html( $fs_name ); ?></h3>
		<?php if ( $fs_position ) : ?>
			<p class="fs-team-card__position"><?php echo esc_html( $fs_position ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_quote ) : ?>
			<blockquote class="fs-team-card__quote">
				<p><?php echo esc_html( $fs_quote ); ?></p>
			</blockquote>
		<?php endif; ?>
		<?php if ( $fs_linkedin ) : ?>
			<p class="fs-team-card__linkedin">
				<a
					href="<?php echo esc_url( $fs_linkedin ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					<?php
					/* translators: %s: team member name. */
					echo 'aria-label="' . esc_attr( sprintf( __( 'LinkedIn profile for %s', 'focused-schools' ), $fs_name ) ) . '"';
					?>
				>
					<?php esc_html_e( 'LinkedIn', 'focused-schools' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</div>
