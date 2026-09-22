<?php
/**
 * Component: Team Card (fs_team_member display integration).
 *
 * Contract ($args):
 * - post       (WP_Post|int, required)
 * - show_bio   (bool, optional, default false) — renders a bio excerpt from
 *   the member's `editor` content. Opt-in so existing usages (e.g. the Home
 *   page teaser) render exactly as before; enabled from page-team.php.
 * - bio_modal  (bool, optional, default false) — added for the About page
 *   task. Renders a "Read bio" button that opens the full `editor` content
 *   in the shared dialog from team-bio-modal.php (assets/js/components/team-bio-modal.js),
 *   instead of (or alongside) show_bio's inline excerpt. Only rendered when
 *   the member actually has post_content — no button for an empty bio.
 * - placeholder_mark_url (string) theme-static mark image shown, faint, in
 *   the "Portrait pending" fallback tile when the member has no featured
 *   image — matches the approved .dc design's explicit fallback treatment.
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

$fs_post_id   = $fs_post->ID;
$fs_name      = get_the_title( $fs_post_id );
$fs_position  = get_post_meta( $fs_post_id, '_fs_team_position', true );
$fs_quote     = get_post_meta( $fs_post_id, '_fs_team_quote', true );
$fs_linkedin  = get_post_meta( $fs_post_id, '_fs_team_linkedin_url', true );
$fs_show_bio  = ! empty( $args['show_bio'] );
$fs_bio       = $fs_show_bio ? get_the_excerpt( $fs_post_id ) : '';
$fs_bio_modal = ! empty( $args['bio_modal'] );
$fs_full_bio  = $fs_bio_modal ? trim( (string) $fs_post->post_content ) : '';
$fs_mark_url  = isset( $args['placeholder_mark_url'] ) ? $args['placeholder_mark_url'] : '';
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
	<?php else : ?>
		<div class="fs-card__media fs-team-card__media fs-team-card__media--pending">
			<?php if ( $fs_mark_url ) : ?>
				<img src="<?php echo esc_url( $fs_mark_url ); ?>" alt="" />
			<?php endif; ?>
			<span class="fs-team-card__pending-chip"><?php esc_html_e( 'Portrait pending', 'focused-schools' ); ?></span>
		</div>
	<?php endif; ?>
	<div class="fs-card__body">
		<h3 class="fs-card__heading"><?php echo esc_html( $fs_name ); ?></h3>
		<?php if ( $fs_position ) : ?>
			<p class="fs-team-card__position"><?php echo esc_html( $fs_position ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_bio ) : ?>
			<p class="fs-team-card__bio"><?php echo esc_html( $fs_bio ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_quote ) : ?>
			<blockquote class="fs-team-card__quote">
				<p><?php echo esc_html( $fs_quote ); ?></p>
			</blockquote>
		<?php endif; ?>
		<?php if ( $fs_full_bio || $fs_linkedin ) : ?>
			<div class="fs-team-card__foot">
				<?php if ( $fs_full_bio ) : ?>
					<button type="button" class="fs-text-link" data-fs-bio-open aria-haspopup="dialog">
						<?php esc_html_e( 'Read bio', 'focused-schools' ); ?>
						<span aria-hidden="true">&rarr;</span>
					</button>
					<template data-fs-bio-content>
						<h3><?php echo esc_html( $fs_name ); ?></h3>
						<?php if ( $fs_position ) : ?>
							<p class="fs-bio-modal__position"><?php echo esc_html( $fs_position ); ?></p>
						<?php endif; ?>
						<?php echo wp_kses_post( wpautop( $fs_full_bio ) ); ?>
					</template>
				<?php endif; ?>
				<?php if ( $fs_linkedin ) : ?>
					<a
						class="fs-icon-tile"
						href="<?php echo esc_url( $fs_linkedin ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						<?php
						/* translators: %s: team member name. */
						echo 'aria-label="' . esc_attr( sprintf( __( 'LinkedIn profile for %s', 'focused-schools' ), $fs_name ) ) . '"';
						?>
					>
						<span aria-hidden="true"><?php esc_html_e( 'in', 'focused-schools' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
