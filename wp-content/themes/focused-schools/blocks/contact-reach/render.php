<?php
/**
 * Server render for focused-schools/contact-reach.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="fs-contact__reach" aria-labelledby="fs-contact-reach-title">
	<div class="fs-container fs-container--shell">
		<header class="fs-blog__head">
			<div>
				<p class="fs-eyebrow"><?php echo esc_html( isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '' ); ?></p>
				<h2 class="fs-blog__heading" id="fs-contact-reach-title"><?php echo esc_html( isset( $attributes['heading'] ) ? $attributes['heading'] : '' ); ?></h2>
			</div>
		</header>

		<?php
		get_template_part(
			'template-parts/components/reach-cards',
			null,
			array(
				'cards' => array(
					array(
						'title' => __( 'Impact Stories', 'focused-schools' ),
						'body'  => __( 'What changed in districts that partnered with us.', 'focused-schools' ),
						'label' => __( 'Read stories', 'focused-schools' ),
						'url'   => home_url( '/impact-stories/' ),
						'rule'  => 'cerulean',
					),
					array(
						'title' => __( 'Our Services', 'focused-schools' ),
						'body'  => __( 'Three lanes, one cycle of inquiry.', 'focused-schools' ),
						'label' => __( 'Explore services', 'focused-schools' ),
						'url'   => home_url( '/services/' ),
						'rule'  => '',
					),
					array(
						'title' => __( 'The Podcast', 'focused-schools' ),
						'body'  => __( 'Conversations with leaders doing this work now.', 'focused-schools' ),
						'label' => __( 'Listen', 'focused-schools' ),
						'url'   => home_url( '/podcast/' ),
						'rule'  => 'rasp',
					),
					array(
						'title' => __( 'Meet the Team', 'focused-schools' ),
						'body'  => __( 'Educators first — the people who would carry the work.', 'focused-schools' ),
						'label' => __( 'Meet the team', 'focused-schools' ),
						'url'   => home_url( '/team/' ),
						'rule'  => 'lime',
					),
				),
			)
		);
		?>
	</div>
</section>
