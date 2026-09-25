<?php
/**
 * Server render for focused-schools/podcast-listen.
 *
 * Buzzsprout's own script embed, framed by the design's shell. The player
 * markup is theirs and is never modified here — only the container around it
 * belongs to this design.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_get = function ( $key ) {
	return function_exists( 'focused_schools_get_setting' ) ? (string) focused_schools_get_setting( $key ) : '';
};

$fs_podcast_id = $fs_get( 'podcast_buzzsprout_id' );
$fs_show       = $fs_get( 'podcast_title' );
$fs_url        = $fs_get( 'podcast_buzzsprout_url' );
$fs_anchor     = isset( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'listen';

ob_start();
get_template_part(
	'template-parts/components/podcast-player',
	null,
	array(
		'podcast_id' => $fs_podcast_id,
		/* translators: %s: podcast show name. */
		'title'      => sprintf( __( '%s episodes', 'focused-schools' ), $fs_show ),
		'empty_text' => __( 'Episodes will appear here once the Buzzsprout podcast ID is set in Site Settings → Podcast.', 'focused-schools' ),
	)
);
$fs_player = ob_get_clean();
?>
<section class="fs-podcast__section fs-podcast__listen" id="<?php echo esc_attr( $fs_anchor ); ?>" aria-labelledby="fs-podcast-listen-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-podcast__head">
			<div>
				<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				<h2 id="fs-podcast-listen-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
			</div>
			<?php
			if ( $fs_url && ! empty( $attributes['ctaLabel'] ) ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'  => $attributes['ctaLabel'],
						'url'    => $fs_url,
						'style'  => 'text',
						'target' => '_blank',
					)
				);
			}
			?>
		</header>

		<?php
		if ( $fs_podcast_id ) {
			get_template_part(
				'template-parts/components/embed-shell',
				null,
				array(
					'title'    => $fs_show,
					'kicker'   => __( 'Hosted on Buzzsprout', 'focused-schools' ),
					'mark_url' => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg',
					'inner'    => $fs_player,
				)
			);
		} else {
			echo $fs_player; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the component's own escaped empty-state markup.
		}
		?>
	</div>
</section>
