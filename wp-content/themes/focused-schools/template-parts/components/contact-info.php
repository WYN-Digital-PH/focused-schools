<?php
/**
 * Component: Contact Info block.
 *
 * Pulls directly from Site Settings (focused_schools_get_setting()), same
 * pattern as site-footer.php. Renders business name, formatted address, and
 * tel:/mailto: links inside a semantic <address> element. Omits itself
 * entirely if the plugin is inactive or all fields are empty (graceful
 * degradation, same as every other Site-Settings-dependent component).
 *
 * Contract ($args):
 * - layout (string) 'stacked' draws the approved homepage variant: email,
 *   phone and address as three links in one <address>, the address linking to
 *   the Map Link setting when one is set. 'direct' draws the Contact page's
 *   "Reach us directly" rows (email, phone, LinkedIn), each a label over a
 *   large value on a teal ground. Anything else keeps the default.
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
$fs_layout     = isset( $args['layout'] ) ? (string) $args['layout'] : '';
$fs_map_url    = focused_schools_get_setting( 'map_url' );

if ( 'direct' === $fs_layout ) :
	$fs_linkedin = focused_schools_get_setting( 'linkedin_url' );
	$fs_rows     = array();

	if ( $fs_email ) {
		$fs_rows[] = array(
			'label' => __( 'Email', 'focused-schools' ),
			'value' => $fs_email,
			'href'  => 'mailto:' . $fs_email,
		);
	}

	if ( $fs_phone ) {
		$fs_rows[] = array(
			'label' => __( 'Phone', 'focused-schools' ),
			'value' => $fs_phone,
			'href'  => 'tel:' . $fs_phone_href,
		);
	}

	if ( $fs_linkedin ) {
		$fs_rows[] = array(
			'label' => __( 'LinkedIn', 'focused-schools' ),
			'value' => $fs_business_name ? $fs_business_name : __( 'Follow us', 'focused-schools' ),
			'href'  => $fs_linkedin,
		);
	}

	if ( empty( $fs_rows ) ) {
		return;
	}
	?>
	<address class="fs-contact-info fs-contact-info--direct">
		<?php foreach ( $fs_rows as $fs_row ) : ?>
			<a class="fs-contact-info__row" href="<?php echo esc_url( $fs_row['href'] ); ?>"<?php echo 0 === strpos( $fs_row['href'], 'http' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
				<span class="fs-contact-info__k"><?php echo esc_html( $fs_row['label'] ); ?></span>
				<span class="fs-contact-info__v"><?php echo esc_html( $fs_row['value'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</address>
	<?php
	return;
endif;

if ( 'stacked' === $fs_layout ) :
	// This variant shows only the three contact routes, so with none of them
	// set there is nothing to draw — an empty <address> would still take space.
	if ( ! $fs_email && ! $fs_phone && ! $fs_address ) {
		return;
	}
	?>
	<address class="fs-contact-info fs-contact-info--stacked">
		<?php if ( $fs_email ) : ?>
			<a href="<?php echo esc_url( 'mailto:' . $fs_email ); ?>"><?php echo esc_html( $fs_email ); ?></a>
		<?php endif; ?>
		<?php if ( $fs_phone ) : ?>
			<a href="<?php echo esc_url( 'tel:' . $fs_phone_href ); ?>"><?php echo esc_html( $fs_phone ); ?></a>
		<?php endif; ?>
		<?php
		if ( $fs_address ) :
			$fs_lines = wp_kses( nl2br( esc_html( $fs_address ) ), array( 'br' => array() ) );

			if ( $fs_map_url ) :
				?>
				<a href="<?php echo esc_url( $fs_map_url ); ?>" target="_blank" rel="noopener"><?php echo $fs_lines; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above, then <br> restored via wp_kses. ?></a>
			<?php else : ?>
				<span><?php echo $fs_lines; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- see above. ?></span>
				<?php
			endif;
		endif;
		?>
	</address>
	<?php
	return;
endif;
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
