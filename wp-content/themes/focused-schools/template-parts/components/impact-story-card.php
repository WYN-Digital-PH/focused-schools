<?php
/**
 * Component: Impact Story Card.
 *
 * Contract ($args):
 * - post (WP_Post|int, required) — an fs_impact_story post, or an approved
 *   legacy Impact Story Page.
 *
 * One card presentation for both sources. The hard requirement from
 * docs/page-specs/impact-stories.md §4.4 is that a legacy Page missing
 * structured data reads as an editorial choice, not a gap: absent fields are
 * omitted outright rather than filled with a dash or "N/A", and the image
 * ratio box is fixed so rows never jag.
 *
 * Degradation, field by field:
 * - image   — legacy without one gets the teal mark tile, same height.
 * - year    — badge omitted entirely for legacy. No "Undated" placeholder.
 * - state   — omitted for legacy; the place line shortens.
 * - excerpt — legacy auto-excerpt trimmed to 180 chars on a word boundary.
 * - source  — every card carries a pill, and the place-line dot matches it.
 *
 * As a legacy Page is backfilled and converted to fs_impact_story it gains a
 * year badge and drops the Legacy pill with no change here: migration is
 * invisible by design.
 *
 * Meta key literals mirror FocusedSchoolsCore\Modules\Impact_Stories\Meta so
 * this degrades to empty values rather than fataling if the plugin is off.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id = $fs_post->ID;
$fs_is_new  = 'fs_impact_story' === $fs_post->post_type;

$fs_district = $fs_is_new ? (string) get_post_meta( $fs_post_id, '_fs_impact_story_district_or_school', true ) : '';
$fs_state    = $fs_is_new ? (string) get_post_meta( $fs_post_id, '_fs_impact_story_state', true ) : '';
$fs_year     = $fs_is_new ? (string) get_post_meta( $fs_post_id, '_fs_impact_story_year', true ) : '';

$fs_permalink = get_permalink( $fs_post_id );
$fs_title     = get_the_title( $fs_post_id );

// Legacy Pages rarely carry an authored excerpt, so trim the generated one to
// a word boundary instead of letting it run to the card's clamp.
$fs_excerpt = trim( (string) get_the_excerpt( $fs_post_id ) );

if ( ! $fs_is_new && mb_strlen( $fs_excerpt ) > 180 ) {
	$fs_excerpt = rtrim( mb_substr( $fs_excerpt, 0, mb_strrpos( mb_substr( $fs_excerpt, 0, 181 ), ' ' ) ), " \t\n\r\0\x0B.,;:" ) . '…';
}

$fs_place = array_filter( array( $fs_district, $fs_state ), 'strlen' );
$fs_mark  = isset( $args['placeholder_mark_url'] ) ? $args['placeholder_mark_url'] : FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg';
?>
<article class="fs-card fs-story-card<?php echo $fs_is_new ? '' : ' fs-story-card--legacy'; ?>">
	<div class="fs-story-card__media">
		<?php if ( has_post_thumbnail( $fs_post_id ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$fs_post_id,
				'large',
				array(
					'class'   => 'fs-story-card__image',
					'alt'     => esc_attr( $fs_title ),
					'loading' => 'lazy',
				)
			);
			?>
		<?php else : ?>
			<span class="fs-story-card__placeholder" aria-hidden="true">
				<img src="<?php echo esc_url( $fs_mark ); ?>" alt="" />
			</span>
		<?php endif; ?>

		<?php if ( '' !== $fs_year ) : ?>
			<span class="fs-story-card__year"><?php echo esc_html( $fs_year ); ?></span>
		<?php endif; ?>
	</div>

	<div class="fs-story-card__body">
		<p class="fs-story-card__place">
			<span class="fs-story-card__dot" aria-hidden="true"></span>
			<?php echo esc_html( implode( ' · ', $fs_place ) ); ?>
		</p>

		<h3 class="fs-story-card__title">
			<a href="<?php echo esc_url( $fs_permalink ); ?>"><?php echo esc_html( $fs_title ); ?></a>
		</h3>

		<?php if ( '' !== $fs_excerpt ) : ?>
			<p class="fs-story-card__excerpt"><?php echo esc_html( $fs_excerpt ); ?></p>
		<?php endif; ?>

		<div class="fs-story-card__foot">
			<a class="fs-story-card__link" href="<?php echo esc_url( $fs_permalink ); ?>">
				<?php esc_html_e( 'Read story', 'focused-schools' ); ?>
				<span aria-hidden="true">&rarr;</span>
				<span class="screen-reader-text"><?php echo esc_html( $fs_title ); ?></span>
			</a>
			<span class="fs-story-card__source">
				<?php echo $fs_is_new ? esc_html__( 'Story', 'focused-schools' ) : esc_html__( 'Legacy', 'focused-schools' ); ?>
			</span>
		</div>
	</div>
</article>
