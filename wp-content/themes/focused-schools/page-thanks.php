<?php
/**
 * Template Name: Thank You
 *
 * Page template for the Thank You confirmation page. WordPress's native
 * page-{slug}.php template hierarchy applies this automatically to any
 * page whose slug is "thanks" — no numeric Page ID was provided for this
 * task, but none is needed.
 *
 * This template has no form/redirect logic at all — the form that sends
 * visitors here configures its own "Actions After Submit" redirect
 * entirely within its own settings (e.g. Elementor), external to this
 * file. This page only provides the destination's presentation.
 *
 * See docs/page-specs/contact.md §5 for the full spec.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) :
			?>
			<main id="content">
				<?php the_content(); ?>
			</main>
			<?php
			continue;
		endif;
		?>

		<main id="content">
			<?php
			get_template_part(
				'template-parts/components/hero',
				null,
				array(
					'heading'    => get_the_title() ? get_the_title() : __( 'Thank You', 'focused-schools' ),
					'subheading' => get_the_excerpt() ? get_the_excerpt() : __( "Thanks for reaching out — we've received your message and will be in touch soon.", 'focused-schools' ),
					'alignment'  => 'center',
				)
			);

			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="fs-container fs-thanks__content">
					<?php the_content(); ?>
				</div>
				<?php
			endif;
			?>
		</main>
		<?php
	endwhile;
else :
	?>
	<main id="content" class="fs-container">
		<p><?php esc_html_e( 'Nothing found.', 'focused-schools' ); ?></p>
	</main>
	<?php
endif;

get_footer();
