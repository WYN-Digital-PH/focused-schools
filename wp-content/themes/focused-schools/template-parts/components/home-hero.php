<?php
/**
 * Component: Home Hero (video-poster broadcast style).
 *
 * Contract ($args):
 * - heading      (string, required)
 * - subheading   (string)
 * - poster_url   (string) theme-relative static image URL for the video poster
 * - youtube_id   (string) bare YouTube video ID for the "watch with sound" modal
 * - cta_label    (string)
 * - cta_url      (string)
 *
 * Distinct from the shared hero.php component (used by every other page):
 * this is the Home page's own richer, video-led hero, so hero.php's markup
 * and every page that uses it are unaffected. Zero-overhead by design: only
 * a static poster `<img>` renders on load. The real YouTube iframe is
 * created by assets/js/components/home-hero.js only after the "Watch with
 * sound" button is clicked, opening it in a modal dialog — the same
 * click-to-load principle already used by podcast-card.php, just presented
 * as a modal here instead of an inline facade (matching the design
 * reference's "Watch with sound" interaction, since the video also plays
 * silently as a poster/background element with sound intentionally gated
 * behind an explicit user action).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$fs_poster_url = isset( $args['poster_url'] ) ? $args['poster_url'] : '';
$fs_youtube_id = isset( $args['youtube_id'] ) ? $args['youtube_id'] : '';
$fs_cta_label  = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url    = isset( $args['cta_url'] ) ? $args['cta_url'] : '';

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}

// Same strict allowlist as podcast-card.php: reject anything that isn't a
// bare 11-character YouTube video ID rather than build an embed URL from it.
if ( $fs_youtube_id && ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $fs_youtube_id ) ) {
	$fs_youtube_id = '';
}
?>
<section class="fs-home-hero" aria-labelledby="fs-home-hero-title">
	<div class="fs-container fs-container--shell fs-home-hero__shell">
		<div class="fs-home-hero__media" data-fs-hero-frame data-paused="false">
			<?php if ( $fs_poster_url ) : ?>
				<img class="fs-home-hero__poster" src="<?php echo esc_url( $fs_poster_url ); ?>" alt="" loading="eager" fetchpriority="high" />
			<?php endif; ?>

			<?php
			/*
			 * Slot for the ambient loop. The iframe is created by
			 * home-hero.js rather than printed here so it is never requested
			 * under prefers-reduced-motion, and so a no-JS visitor simply
			 * keeps the poster.
			 */
			?>
			<div class="fs-home-hero__video-slot" data-fs-hero-video></div>

			<?php if ( $fs_youtube_id ) : ?>
				<div class="fs-home-hero__console">
					<button
						type="button"
						class="fs-home-hero__toggle"
						aria-pressed="false"
						data-fs-hero-play
					>
						<span class="fs-home-hero__glyph" aria-hidden="true"><span></span><span></span></span>
						<span data-fs-hero-play-label><?php esc_html_e( 'Pause', 'focused-schools' ); ?></span>
					</button>
					<button
						type="button"
						class="fs-home-hero__watch"
						data-fs-video-open
						data-fs-video-id="<?php echo esc_attr( $fs_youtube_id ); ?>"
						data-fs-video-title="<?php echo esc_attr( $fs_heading ); ?>"
					>
						<?php esc_html_e( 'Watch with sound', 'focused-schools' ); ?>
						<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13M13 6l6 6-6 6" /></svg>
					</button>
				</div>
			<?php endif; ?>
		</div>

		<div class="fs-home-hero__slab">
			<h1 class="fs-home-hero__heading" id="fs-home-hero-title"><?php echo esc_html( $fs_heading ); ?></h1>
			<?php if ( $fs_subheading ) : ?>
				<p class="fs-home-hero__subheading"><?php echo esc_html( $fs_subheading ); ?></p>
			<?php endif; ?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta_label,
						'url'   => $fs_cta_url,
						'style' => 'primary',
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $fs_youtube_id ) : ?>
	<div class="fs-video-modal" data-fs-video-modal role="dialog" aria-modal="true" aria-labelledby="fs-video-modal-title" hidden>
		<button class="fs-video-modal__backdrop" type="button" data-fs-video-close aria-label="<?php esc_attr_e( 'Close video', 'focused-schools' ); ?>"></button>
		<div class="fs-video-modal__dialog">
			<div class="fs-video-modal__bar">
				<p class="fs-video-modal__title" id="fs-video-modal-title" data-fs-video-modal-title><?php esc_html_e( 'Watch with sound', 'focused-schools' ); ?></p>
				<button class="fs-video-modal__close" type="button" data-fs-video-close aria-label="<?php esc_attr_e( 'Close video', 'focused-schools' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="fs-video-modal__frame" data-fs-video-frame></div>
		</div>
	</div>
<?php endif; ?>
