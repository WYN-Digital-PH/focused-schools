<?php
/**
 * Component: Contact Info block.
 *
 * No args — pulls directly from Site Settings (focused_schools_get_setting()),
 * same no-args pattern as site-footer.php. Renders business name, formatted
 * address, and tel:/mailto: links inside a semantic <address> element.
 * Omits itself entirely if the plugin is inactive or all fields are empty
 * (graceful degradation, same as every other Site-Settings-dependent
 * component).
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'focused_schools_get_setting' ) ) {
	return;
}

$fs_business_name = focused_schools_get_setting( 'business_name' );
$fs_phone         = focused_schools_get_setting( 'phone' );
$fs_email         = focused_schools_get_setting( 'email' );
$fs_address       = focused_schools_get_setting( 'address' );

if ( ! $fs_business_name && ! $fs_phone && ! $fs_email && ! $fs_address ) {
	return;
}

// tel: links need digits (and a leading +) only — strip spaces, dashes,
// parens, dots that make the display string human-readable.
$fs_phone_href = $fs_phone ? preg_replace( '/[^0-9+]/', '', $fs_phone ) : '';
?>
<div class="fs-contact-info">
	<address class="fs-contact-info__address">
		<?php if ( $fs_business_name ) : ?>
			<strong class="fs-contact-info__name"><?php echo esc_html( $fs_business_name ); ?></strong><br />
		<?php endif; ?>
		<?php if ( $fs_address ) : ?>
			<?php echo wp_kses( nl2br( esc_html( $fs_address ) ), array( 'br' => array() ) ); ?>
		<?php endif; ?>
	</address>
	<?php if ( $fs_phone ) : ?>
		<p class="fs-contact-info__line">
			<a href="<?php echo esc_url( 'tel:' . $fs_phone_href ); ?>"><?php echo esc_html( $fs_phone ); ?></a>
		</p>
	<?php endif; ?>
	<?php if ( $fs_email ) : ?>
		<p class="fs-contact-info__line">
			<a href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
		</p>
	<?php endif; ?>
</div>
