<?php
/**
 * Component: Contact aside.
 *
 * Contract ($args):
 * - badge_url (string) credential badge image
 * - badge_alt (string)
 *
 * The rail beside the form: how to reach us directly, what happens after a
 * message is sent, and the credential badge. Contact routes come from Site
 * Settings, so they are set once for the whole site and cannot drift from
 * the footer or the homepage.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_has_settings = function_exists( 'focused_schools_get_setting' );
$fs_email        = $fs_has_settings ? focused_schools_get_setting( 'email' ) : '';
$fs_phone        = $fs_has_settings ? focused_schools_get_setting( 'phone' ) : '';
$fs_linkedin     = $fs_has_settings ? focused_schools_get_setting( 'linkedin_url' ) : '';
$fs_business     = $fs_has_settings ? focused_schools_get_setting( 'business_name' ) : '';
$fs_phone_href   = $fs_phone ? preg_replace( '/[^0-9+]/', '', $fs_phone ) : '';
$fs_badge_url    = isset( $args['badge_url'] ) ? $args['badge_url'] : '';
$fs_badge_alt    = isset( $args['badge_alt'] ) ? $args['badge_alt'] : '';

$fs_rows = array();

if ( $fs_email ) {
	$fs_rows[] = array( __( 'Email', 'focused-schools' ), $fs_email, 'mailto:' . $fs_email );
}

if ( $fs_phone ) {
	$fs_rows[] = array( __( 'Phone', 'focused-schools' ), $fs_phone, 'tel:' . $fs_phone_href );
}

if ( $fs_linkedin ) {
	$fs_rows[] = array( __( 'LinkedIn', 'focused-schools' ), $fs_business ? $fs_business : __( 'Focused Schools', 'focused-schools' ), $fs_linkedin );
}

$fs_steps = array(
	array( __( 'We read it', 'focused-schools' ), __( 'A person on our team, not a queue. Usually the same day.', 'focused-schools' ) ),
	array( __( 'We reply within two business days', 'focused-schools' ), __( 'With a real answer about whether and how we can help.', 'focused-schools' ) ),
	array( __( 'We talk it through', 'focused-schools' ), __( 'A conversation first. No proposal until we understand the work.', 'focused-schools' ) ),
);
?>
<aside class="fs-aside">
	<?php if ( $fs_rows ) : ?>
		<div class="fs-teal-card">
			<p class="fs-eyebrow fs-eyebrow--lime"><?php esc_html_e( 'Reach us directly', 'focused-schools' ); ?></p>
			<div class="fs-aside__rows">
				<?php foreach ( $fs_rows as $fs_row ) : ?>
					<a
						class="fs-aside__row"
						href="<?php echo esc_url( $fs_row[2] ); ?>"
						<?php echo 0 === strpos( $fs_row[2], 'http' ) ? 'target="_blank" rel="noopener"' : ''; ?>
					>
						<span><?php echo esc_html( $fs_row[0] ); ?></span>
						<strong><?php echo esc_html( $fs_row[1] ); ?></strong>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="fs-panel">
		<p class="fs-eyebrow"><?php esc_html_e( 'What happens next', 'focused-schools' ); ?></p>
		<ol class="fs-steps">
			<?php foreach ( $fs_steps as $fs_index => $fs_step ) : ?>
				<li>
					<span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fs_index + 1 ) ); ?></span>
					<div>
						<strong><?php echo esc_html( $fs_step[0] ); ?></strong>
						<span class="fs-mini"><?php echo esc_html( $fs_step[1] ); ?></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<?php if ( $fs_badge_url ) : ?>
		<div class="fs-panel">
			<p class="fs-eyebrow"><?php esc_html_e( 'Credentials', 'focused-schools' ); ?></p>
			<div class="fs-credential">
				<img src="<?php echo esc_url( $fs_badge_url ); ?>" alt="<?php echo esc_attr( $fs_badge_alt ); ?>" width="92" height="92" loading="lazy" />
				<p class="fs-mini">
					<?php esc_html_e( 'Massachusetts DESE', 'focused-schools' ); ?>
					<strong><?php esc_html_e( 'Approved Provider.', 'focused-schools' ); ?></strong>
					<?php esc_html_e( 'Partnering with districts nationwide since 2000.', 'focused-schools' ); ?>
				</p>
			</div>
		</div>
	<?php endif; ?>
</aside>
