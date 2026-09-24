<?php
/**
 * Component: Rail Text Block.
 *
 * Contract ($args):
 * - eyebrow    (string, required)
 * - icon_url   (string) small decorative icon URL, theme-static path
 * - heading    (string, required)
 * - body       (string|string[]) one paragraph, or an array of paragraphs
 * - emphasis   (string) optional opening phrase of the first paragraph, given
 *               a coral underline that the Home page's script grows on scroll
 * - cta_label  (string)
 * - cta_url    (string)
 * - heading_max_ch (int) heading's max-width in `ch` units — the Home,
 *   About, and Team `.dc` sources each specify a different value (13ch /
 *   15ch / 18ch) for this shared component's heading, default 13 (Home's
 *   value, the original caller).
 * - padding_bottom (int) section's bottom padding in px — Home/About use
 *   120px (the default); Team's own `.dc` source specifies 112px.
 * - heading_weight (int) heading's base font-weight, default 700
 *   (Home/About render the whole heading bold). Team's `.dc` source uses a
 *   400 base weight with `<strong>` at 700 for the emphasized clause.
 *
 * `heading` allows basic HTML (run through wp_kses_post()) so a caller can
 * mix regular/bold weight within it, e.g. Team's "We have sat in the seat
 * <strong>you are sitting in.</strong>" — plain-text headings render
 * identically to before.
 *
 * A narrow "rail" label (small icon + eyebrow) beside a content column —
 * heading, one or more paragraphs, optional CTA. No image/photo (that's
 * content-image-split.php's job). This exact pattern appears on both the
 * Home page's Beliefs section and the About page's "Who We Are" section —
 * added for the About page task, generic/args-driven, no CPT.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow        = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_icon_url       = isset( $args['icon_url'] ) ? $args['icon_url'] : '';
$fs_heading        = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_body           = isset( $args['body'] ) ? $args['body'] : '';
$fs_body           = is_array( $fs_body ) ? $fs_body : array( $fs_body );
$fs_cta_label      = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url        = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
$fs_heading_max_ch = isset( $args['heading_max_ch'] ) ? absint( $args['heading_max_ch'] ) : 13;
$fs_padding_bottom = isset( $args['padding_bottom'] ) ? absint( $args['padding_bottom'] ) : 120;
$fs_heading_weight = isset( $args['heading_weight'] ) ? absint( $args['heading_weight'] ) : 700;
$fs_emphasis       = isset( $args['emphasis'] ) ? trim( (string) $args['emphasis'] ) : '';

if ( '' === trim( (string) $fs_heading ) ) {
	return;
}
?>
<section class="fs-rail-text" style="--fs-rail-padding-bottom: <?php echo esc_attr( $fs_padding_bottom ); ?>px; --fs-rail-heading-weight: <?php echo esc_attr( $fs_heading_weight ); ?>;">
	<div class="fs-container fs-container--shell fs-rail-text__inner">
		<div class="fs-rail-text__label">
			<?php if ( $fs_icon_url ) : ?>
				<img src="<?php echo esc_url( $fs_icon_url ); ?>" alt="" width="28" height="28" />
			<?php endif; ?>
			<?php if ( $fs_eyebrow ) : ?>
				<p class="fs-eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
			<?php endif; ?>
		</div>
		<div class="fs-rail-text__content" style="--fs-rail-heading-max: <?php echo esc_attr( $fs_heading_max_ch ); ?>ch;">
			<h2><?php echo wp_kses_post( $fs_heading ); ?></h2>
			<?php
			$fs_first = true;

			foreach ( $fs_body as $fs_paragraph ) :
				$fs_paragraph = (string) $fs_paragraph;

				if ( '' === trim( $fs_paragraph ) ) {
					continue;
				}

				/*
				 * Optional opening emphasis: the phrase keeps a coral rule
				 * beneath it that the page script grows as the section is
				 * scrolled. Only the first paragraph, and only when it really
				 * starts with that phrase — otherwise the copy renders plain,
				 * so a content edit can never produce mismatched markup.
				 */
				$fs_use_emphasis = $fs_first && '' !== $fs_emphasis && 0 === strpos( $fs_paragraph, $fs_emphasis );
				$fs_first        = false;
				?>
				<p>
					<?php if ( $fs_use_emphasis ) : ?>
						<em class="fs-emph">
							<?php echo esc_html( $fs_emphasis ); ?>
							<span class="fs-emph__rule" aria-hidden="true" data-fs-emph-rule></span>
						</em>
						<?php echo esc_html( substr( $fs_paragraph, strlen( $fs_emphasis ) ) ); ?>
					<?php else : ?>
						<?php echo esc_html( $fs_paragraph ); ?>
					<?php endif; ?>
				</p>
				<?php
			endforeach;
			?>
			<?php if ( $fs_cta_label && $fs_cta_url ) : ?>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta_label,
						'url'   => $fs_cta_url,
						'style' => 'secondary',
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>
