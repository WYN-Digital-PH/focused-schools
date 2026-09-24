<?php
/**
 * Component: Contact aside (Contact and Thank You pages).
 *
 * Three stacked cards from the approved Contact `.dc`: "Reach us directly"
 * (teal; email, phone and LinkedIn from Site Settings, so no business
 * details are hardcoded here), "What happens next" (three numbered steps)
 * and "Credentials" (membership badge). No args.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_steps = array(
	array(
		'title' => __( 'We read it', 'focused-schools' ),
		'note'  => __( 'A person on our team, not a queue. Usually the same day.', 'focused-schools' ),
	),
	array(
		'title' => __( 'We reply within two business days', 'focused-schools' ),
		'note'  => __( 'With a real answer about whether and how we can help.', 'focused-schools' ),
	),
	array(
		'title' => __( 'We talk it through', 'focused-schools' ),
		'note'  => __( 'A conversation first. No proposal until we understand the work.', 'focused-schools' ),
	),
);

ob_start();
get_template_part( 'template-parts/components/contact-info', null, array( 'layout' => 'direct' ) );
$fs_direct = trim( (string) ob_get_clean() );
?>
<aside class="fs-contact-aside" aria-label="<?php esc_attr_e( 'Other ways to reach us', 'focused-schools' ); ?>">
	<?php if ( '' !== $fs_direct ) : ?>
		<div class="fs-contact-aside__card fs-contact-aside__card--direct">
			<img class="fs-contact-aside__mark" src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-white.svg' ); ?>" alt="" aria-hidden="true" />
			<p class="fs-contact-aside__label fs-contact-aside__label--lime"><?php esc_html_e( 'Reach us directly', 'focused-schools' ); ?></p>
			<?php echo $fs_direct; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of the contact-info component, which escapes its own values. ?>
		</div>
	<?php endif; ?>

	<div class="fs-contact-aside__card">
		<p class="fs-contact-aside__label"><?php esc_html_e( 'What happens next', 'focused-schools' ); ?></p>
		<ol class="fs-contact-aside__steps">
			<?php foreach ( $fs_steps as $fs_index => $fs_step ) : ?>
				<li>
					<span class="fs-contact-aside__num"><?php echo esc_html( sprintf( '%02d', $fs_index + 1 ) ); ?></span>
					<div>
						<strong><?php echo esc_html( $fs_step['title'] ); ?></strong>
						<span><?php echo esc_html( $fs_step['note'] ); ?></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<div class="fs-contact-aside__card">
		<p class="fs-contact-aside__label"><?php esc_html_e( 'Credentials', 'focused-schools' ); ?></p>
		<div class="fs-contact-aside__creds">
			<img src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/chamber-badge.jpg' ); ?>" alt="<?php esc_attr_e( '2026 Proud Member — Manatee Chamber of Commerce', 'focused-schools' ); ?>" width="92" loading="lazy" />
			<p>
				<?php esc_html_e( 'Massachusetts DESE', 'focused-schools' ); ?>
				<strong><?php esc_html_e( 'Approved Provider.', 'focused-schools' ); ?></strong>
				<?php esc_html_e( 'Partnering with districts nationwide since 2000.', 'focused-schools' ); ?>
			</p>
		</div>
	</div>
</aside>
