<?php
/**
 * Component: Podcast Card.
 *
 * Contract ($args):
 * - title          (string, required)
 * - description    (string)
 * - embed_html     (string) e.g. a Buzzsprout <iframe> embed
 * - episode_number (string|int)
 * - duration       (string)
 * - cta_label      (string)
 * - cta_url        (string)
 *
 * Generic/args-driven: no fs_podcast post type exists yet
 * (see docs/architecture.md §3.5). Ready to wire to real data once that
 * module is built.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_title       = isset( $args['title'] ) ? $args['title'] : '';
$fs_description = isset( $args['description'] ) ? $args['description'] : '';
$fs_embed       = isset( $args['embed_html'] ) ? $args['embed_html'] : '';
$fs_episode     = isset( $args['episode_number'] ) ? $args['episode_number'] : '';
$fs_duration    = isset( $args['duration'] ) ? $args['duration'] : '';
$fs_cta_label   = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url     = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_title ) ) {
	return;
}

// Allow common podcast-embed iframe attributes on top of the standard post
// allowlist, rather than trusting embed_html verbatim.
$fs_allowed_embed_html = array_merge(
	wp_kses_allowed_html( 'post' ),
	array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'scrolling'       => true,
			'seamless'        => true,
			'allow'           => true,
			'allowfullscreen' => true,
			'title'           => true,
			'loading'         => true,
		),
	)
);
?>
<article class="fs-card fs-podcast-card">
	<div class="fs-card__body">
		<?php if ( $fs_episode ) : ?>
			<p class="fs-podcast-card__episode">
				<?php
				/* translators: %s: episode number. */
				echo esc_html( sprintf( __( 'Episode %s', 'focused-schools' ), $fs_episode ) );
				?>
			</p>
		<?php endif; ?>
		<h3 class="fs-card__heading"><?php echo esc_html( $fs_title ); ?></h3>
		<?php if ( $fs_duration ) : ?>
			<p class="fs-podcast-card__duration"><?php echo esc_html( $fs_duration ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_description ) : ?>
			<div class="fs-card__excerpt"><?php echo esc_html( $fs_description ); ?></div>
		<?php endif; ?>
		<?php if ( $fs_embed ) : ?>
			<div class="fs-podcast-card__embed"><?php echo wp_kses( $fs_embed, $fs_allowed_embed_html ); ?></div>
		<?php endif; ?>
		<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
				)
			);
			?>
		<?php endif; ?>
	</div>
</article>
