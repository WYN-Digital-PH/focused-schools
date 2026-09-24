<?php
/**
 * Component: Share rail.
 *
 * Contract ($args):
 * - post (WP_Post|int, required)
 *
 * Plain share URLs, opened in a new tab. No third-party share widget, so no
 * tracking script runs on an article page and nothing is requested from a
 * social network until a reader actually chooses to share.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_url   = rawurlencode( get_permalink( $fs_post ) );
$fs_title = rawurlencode( get_the_title( $fs_post ) );
$fs_blog  = (int) get_option( 'page_for_posts' );

$fs_links = array(
	'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $fs_url,
	'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $fs_url,
	'X'        => 'https://twitter.com/intent/tweet?url=' . $fs_url . '&text=' . $fs_title,
	'Email'    => 'mailto:?subject=' . $fs_title . '&body=' . $fs_url,
);

$fs_glyphs = array(
	'LinkedIn' => 'in',
	'Facebook' => 'f',
	'X'        => 'X',
	'Email'    => '@',
);
?>
<aside class="fs-story-aside">
	<p class="fs-eyebrow fs-story-aside__label"><?php esc_html_e( 'Share this article', 'focused-schools' ); ?></p>

	<div class="fs-share">
		<div class="fs-share__row">
			<?php foreach ( $fs_links as $fs_name => $fs_href ) : ?>
				<a
					class="fs-share__btn"
					href="<?php echo esc_url( $fs_href ); ?>"
					<?php echo 'Email' === $fs_name ? '' : 'target="_blank" rel="noopener"'; ?>
				>
					<span aria-hidden="true"><?php echo esc_html( $fs_glyphs[ $fs_name ] ); ?></span>
					<span class="screen-reader-text">
						<?php
						printf(
							/* translators: %s: network name. */
							esc_html__( 'Share on %s', 'focused-schools' ),
							esc_html( $fs_name )
						);
						?>
					</span>
				</a>
			<?php endforeach; ?>

			<?php
			/*
			 * Copying needs the clipboard API, so this is the one control
			 * here that depends on JavaScript. It is printed with `hidden`
			 * and revealed by post-share.js, so a reader without scripts
			 * sees the four working share links rather than a dead button.
			 */
			?>
			<button
				class="fs-share__btn fs-share__btn--wide"
				type="button"
				data-fs-copy
				data-fs-copy-url="<?php echo esc_url( get_permalink( $fs_post ) ); ?>"
				hidden
			>
				<?php esc_html_e( 'Copy link', 'focused-schools' ); ?>
			</button>
		</div>

		<p class="fs-share__toast" role="status" aria-live="polite" data-fs-copy-toast></p>
	</div>

	<?php
	get_template_part(
		'template-parts/components/button',
		null,
		array(
			'label' => __( 'Back to All Posts', 'focused-schools' ),
			'url'   => $fs_blog ? get_permalink( $fs_blog ) : home_url( '/' ),
			'style' => 'secondary',
		)
	);
	?>
</aside>
