<?php
/**
 * Component: Pull Quote.
 *
 * Contract ($args):
 * - post       (WP_Post|int, required) an fs_testimonial record
 * - link_label (string) optional link after the quote, e.g. Google Reviews
 * - link_url   (string) required when link_label is set
 *
 * One approved quote, shown statically. The Services page spec is explicit
 * that this is not the carousel: a single quote and a reviews link, nothing
 * to advance. It reads the same fs_testimonial records the carousel does, so
 * the quote is still editorial content rather than template copy.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $fs_post instanceof WP_Post ) {
	return;
}

$fs_text = trim( wp_strip_all_tags( get_the_content( null, false, $fs_post ) ) );

if ( '' === $fs_text ) {
	return;
}

$fs_cite       = get_the_title( $fs_post );
$fs_link_label = isset( $args['link_label'] ) ? trim( (string) $args['link_label'] ) : '';
$fs_link_url   = isset( $args['link_url'] ) ? trim( (string) $args['link_url'] ) : '';
?>
<blockquote class="fs-pull-quote">
	<span class="fs-pull-quote__marks" aria-hidden="true"><span></span><span></span></span>
	<div>
		<p class="fs-pull-quote__text"><?php echo esc_html( $fs_text ); ?></p>
		<?php if ( $fs_cite ) : ?>
			<p class="fs-pull-quote__cite"><span aria-hidden="true"></span><?php echo esc_html( $fs_cite ); ?></p>
		<?php endif; ?>
	</div>
</blockquote>

<?php if ( '' !== $fs_link_label && '' !== $fs_link_url ) : ?>
	<div class="fs-pull-quote__action">
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'label'  => $fs_link_label,
				'url'    => $fs_link_url,
				'style'  => 'secondary',
				'target' => '_blank',
			)
		);
		?>
	</div>
<?php endif; ?>
