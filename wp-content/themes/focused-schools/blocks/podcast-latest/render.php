<?php
/**
 * Server render for focused-schools/podcast-latest.
 *
 * The episode comes from Buzzsprout's RSS feed, read from cache, so the
 * panel keeps itself current after every publish. The Site Settings fields
 * still win where filled in, so an episode can be pinned without giving up
 * the automatic behaviour.
 *
 * Absent until something is cached, and the full player below still carries
 * every episode, so the page never depends on this section.
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

$fs_feed = function_exists( 'FocusedSchoolsCore\get_podcast_latest_episode' )
	? FocusedSchoolsCore\get_podcast_latest_episode()
	: array();

$fs_episode_id = $fs_get( 'podcast_latest_episode_id' );

if ( '' === trim( $fs_episode_id ) && ! empty( $fs_feed['episode_id'] ) ) {
	$fs_episode_id = $fs_feed['episode_id'];
}

if ( '' === trim( (string) $fs_podcast_id ) || '' === trim( (string) $fs_episode_id ) ) {
	return;
}

// Buzzsprout's own single-episode embed, captured so the panel can frame it.
ob_start();
get_template_part(
	'template-parts/components/podcast-player',
	null,
	array(
		'podcast_id' => $fs_podcast_id,
		'episode_id' => $fs_episode_id,
		'player'     => 'small',
	)
);
$fs_player = ob_get_clean();

$fs_title = $fs_get( 'podcast_latest_episode_title' );

if ( '' === trim( $fs_title ) ) {
	$fs_title = ! empty( $fs_feed['title'] ) ? $fs_feed['title'] : __( 'Start with the latest conversation.', 'focused-schools' );
}

$fs_body = $fs_get( 'podcast_latest_episode_summary' );

if ( '' === trim( $fs_body ) && ! empty( $fs_feed['summary'] ) ) {
	$fs_body = wp_trim_words( $fs_feed['summary'], 40 );
}
?>
<section class="fs-podcast__section fs-podcast__latest" aria-labelledby="fs-podcast-latest-title">
	<div class="fs-container fs-container--shell">
		<div class="fs-eyebrow-row">
			<span class="fs-rule fs-rule--rasp" aria-hidden="true"></span>
			<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
		</div>
		<?php
		get_template_part(
			'template-parts/components/episode-spotlight',
			null,
			array(
				'eyebrow'  => $fs_get( 'podcast_title' ),
				'title'    => $fs_title,
				'body'     => $fs_body,
				'mark_url' => FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg',
				'inner'    => $fs_player,
			)
		);
		?>
	</div>
</section>
