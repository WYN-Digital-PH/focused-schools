<?php
/**
 * Server render for focused-schools/contact-hero.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="fs-contact__hero">
	<div class="fs-container fs-container--shell fs-rail">
		<div class="fs-rail__label">
			<img src="<?php echo esc_url( FOCUSED_SCHOOLS_THEME_URI . '/assets/img/mark-1.svg' ); ?>" alt="" width="28" height="28" />
			<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
		</div>
		<div>
			<h1 class="fs-contact__title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h1>
			<p class="fs-contact__lead"><?php echo esc_html( isset( $attributes['body'] ) ? $attributes['body'] : '' ); ?></p>
		</div>
	</div>
</section>
