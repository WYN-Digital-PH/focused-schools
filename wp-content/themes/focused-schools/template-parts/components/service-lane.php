<?php
/**
 * Component: Service Lane (fs_service full-width detail section).
 *
 * Contract ($args):
 * - post      (WP_Post|int, required)
 * - index     (int, required) 1-based position, rendered as the 01/02/03 numeral
 * - flip      (bool) photo left, copy right — derived from query position, not
 *              authored. Source order stays copy-then-figure either way.
 * - cta_label (string) defaults to "Start This Conversation"
 * - cta_url   (string) defaults to /contact/
 * - image_url (string) fallback photo used only when the service has no
 *              featured image, e.g. a theme-bundled asset
 * - image_alt (string) alt text for image_url
 *
 * Structure and geometry come from the approved mockup
 * (`Focused Schools Mockup/site`), see docs/page-specs/services.md §5.
 *
 * The accent is a design-system token key stored on the record — never a
 * color — and it appears in exactly three places: the 26×3px rule beside the
 * numeral, the offering bullets, and the caption kicker. It never sets body
 * copy, headings, taglines, buttons or section grounds.
 *
 * Meta key literals mirror FocusedSchoolsCore\Modules\Services\Meta so this
 * template degrades gracefully (empty values) rather than fataling if the
 * plugin is deactivated — same reasoning as the two sibling components.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_post_id = $fs_post->ID;
$fs_index   = isset( $args['index'] ) ? (int) $args['index'] : 0;
$fs_flip    = ! empty( $args['flip'] );
$fs_title   = get_the_title( $fs_post_id );
$fs_tagline = get_post_meta( $fs_post_id, '_fs_service_tagline', true );
$fs_video   = focused_schools_youtube_id( get_post_meta( $fs_post_id, '_fs_service_video_url', true ) );
$fs_vtitle  = (string) get_post_meta( $fs_post_id, '_fs_service_video_title', true );
$fs_vtitle  = '' !== trim( $fs_vtitle ) ? $fs_vtitle : $fs_title;
$fs_proof   = get_post_meta( $fs_post_id, '_fs_service_proof', true );
$fs_raw     = (string) get_post_meta( $fs_post_id, '_fs_service_offerings', true );

/*
 * Accent token. Anything unrecognized — including a pre-token lane name from
 * an older record — resolves to teal, per the approved spec's explicit
 * "never a random or cycling color" rule.
 */
$fs_accent_legacy = array(
	'strategy'   => 'cerulean',
	'leadership' => 'coral',
	'capacity'   => 'lime',
);
$fs_accent        = (string) get_post_meta( $fs_post_id, '_fs_service_accent_role', true );
$fs_accent        = isset( $fs_accent_legacy[ $fs_accent ] ) ? $fs_accent_legacy[ $fs_accent ] : $fs_accent;
$fs_accent        = in_array( $fs_accent, array( 'cerulean', 'coral', 'lime', 'teal' ), true ) ? $fs_accent : 'teal';

$fs_accent_colors = array(
	'cerulean' => 'var( --wp--preset--color--accent, #0a96cb )',
	'coral'    => 'var( --wp--preset--color--primary, #dd6237 )',
	'lime'     => 'var( --wp--preset--color--lime, #a7cc14 )',
	'teal'     => 'var( --wp--preset--color--contrast, #005c6d )',
);

/*
 * Lime fails contrast for type at any size, so the caption kicker — the only
 * place the accent becomes text — falls back to cerulean in that lane. Bar
 * and bullets stay lime. Codified here, never left to the editor.
 */
$fs_kicker_color = 'lime' === $fs_accent ? $fs_accent_colors['cerulean'] : $fs_accent_colors[ $fs_accent ];

// Same split the plugin's Meta::offerings_list() performs — duplicated, not
// referenced, for the plugin-deactivated case noted above.
$fs_offerings = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $fs_raw ) ), 'strlen' ) );

$fs_cta_label = isset( $args['cta_label'] ) ? $args['cta_label'] : __( 'Start This Conversation', 'focused-schools' );
$fs_cta_url   = isset( $args['cta_url'] ) ? $args['cta_url'] : home_url( '/contact/' );
$fs_has_image = has_post_thumbnail( $fs_post_id ) || ! empty( $args['image_url'] );
?>
<div class="fs-lane<?php echo $fs_flip ? ' fs-lane--flip' : ''; ?><?php echo $fs_has_image ? '' : ' fs-lane--no-media'; ?>" id="<?php echo esc_attr( $fs_post->post_name ); ?>">
	<div class="fs-lane__copy">
		<?php if ( $fs_index ) : ?>
			<div class="fs-lane__accent">
				<span class="fs-rule fs-rule--<?php echo esc_attr( $fs_accent ); ?>" aria-hidden="true"></span>
				<span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index ) ); ?></span>
			</div>
		<?php endif; ?>

		<h2 id="fs-lane-<?php echo esc_attr( $fs_post->post_name ); ?>"><?php echo esc_html( $fs_title ); ?></h2>

		<?php if ( $fs_tagline ) : ?>
			<p class="fs-lane__tagline"><?php echo esc_html( $fs_tagline ); ?></p>
		<?php endif; ?>

		<?php
		/*
		 * The lane shows the service's full body, not its excerpt — the
		 * excerpt is the short card/index-row description, and on this page
		 * that text already appears as the tagline above.
		 */
		$fs_body = trim( (string) $fs_post->post_content );

		if ( '' !== $fs_body ) :
			?>
			<div class="fs-body fs-lane__desc"><?php echo wp_kses_post( apply_filters( 'the_content', $fs_body ) ); ?></div>
			<?php
		endif;
		?>

		<?php if ( ! empty( $fs_offerings ) ) : ?>
			<?php // Two columns from four items up; one below that, per spec §6. ?>
			<div class="fs-offerings<?php echo count( $fs_offerings ) < 4 ? ' fs-offerings--single' : ''; ?>">
				<p class="fs-eyebrow fs-offerings__label"><?php esc_html_e( 'Signature offerings', 'focused-schools' ); ?></p>
				<ul>
					<?php foreach ( $fs_offerings as $fs_offering ) : ?>
						<li>
							<span aria-hidden="true" style="background:<?php echo esc_attr( $fs_accent_colors[ $fs_accent ] ); ?>"></span>
							<?php echo esc_html( $fs_offering ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="fs-btn-row">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $fs_cta_label,
					'url'   => $fs_cta_url,
				)
			);

			if ( $fs_video ) :
				?>
				<button
					class="fs-btn fs-btn--secondary fs-btn--watch"
					type="button"
					data-fs-video="<?php echo esc_attr( $fs_video ); ?>"
					data-fs-video-title="<?php echo esc_attr( $fs_vtitle ); ?>"
				>
					<span class="fs-btn__play" aria-hidden="true">&#9654;</span>
					<?php esc_html_e( 'Watch Overview Video', 'focused-schools' ); ?>
				</button>
				<?php
			endif;
			?>
		</div>
	</div>

	<?php if ( $fs_has_image ) : ?>
		<figure class="fs-lane__media fs-figure<?php echo $fs_video ? ' fs-lane__media--video' : ''; ?>">
			<?php
			if ( has_post_thumbnail( $fs_post_id ) ) {
				echo get_the_post_thumbnail(
					$fs_post_id,
					'large',
					array(
						'class'   => 'fs-photo',
						/* translators: %s: service name. */
						'alt'     => esc_attr( sprintf( __( '%s partnership work in progress.', 'focused-schools' ), $fs_title ) ),
						'loading' => 'lazy',
					)
				);
			} else {
				printf(
					'<img class="fs-photo" src="%1$s" alt="%2$s" width="1000" height="1250" loading="lazy" />',
					esc_url( $args['image_url'] ),
					esc_attr( isset( $args['image_alt'] ) ? $args['image_alt'] : $fs_title )
				);
			}
			?>

			<?php if ( $fs_video ) : ?>
				<button
					class="fs-playover"
					type="button"
					data-fs-video="<?php echo esc_attr( $fs_video ); ?>"
					data-fs-video-title="<?php echo esc_attr( $fs_vtitle ); ?>"
				>
					<span class="fs-playover__disc" aria-hidden="true">&#9654;</span>
					<span class="fs-playover__label"><?php esc_html_e( 'Watch Overview Video', 'focused-schools' ); ?></span>
				</button>
			<?php endif; ?>

			<?php if ( $fs_proof ) : ?>
				<figcaption class="fs-figure__caption<?php echo $fs_flip ? ' fs-figure__caption--left' : ''; ?>">
					<span class="fs-figure__kicker" style="color:<?php echo esc_attr( $fs_kicker_color ); ?>"><?php echo esc_html( $fs_title ); ?></span>
					<strong><?php echo esc_html( $fs_proof ); ?></strong>
				</figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>
</div>
