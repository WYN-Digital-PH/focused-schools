<?php
/**
 * Not found.
 *
 * Without this file WordPress falls through to index.php, which renders a
 * bare "Nothing found." with none of the site's own navigation — a dead end
 * at exactly the point a reader needs a way onward.
 *
 * Reuses the contact page's reach cards, so the four routes offered here are
 * the same four offered there and cannot drift apart.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fs_blog = (int) get_option( 'page_for_posts' );
?>

<main id="content" class="fs-error">
	<section class="fs-error__intro">
		<div class="fs-container fs-container--shell">
			<div class="fs-eyebrow-row">
				<span class="fs-rule fs-rule--lime" aria-hidden="true"></span>
				<p class="fs-eyebrow"><?php esc_html_e( 'Page not found', 'focused-schools' ); ?></p>
			</div>

			<h1 class="fs-error__title"><?php esc_html_e( 'That page has moved on.', 'focused-schools' ); ?></h1>

			<p class="fs-error__lead">
				<?php esc_html_e( 'The link may be out of date, or the page may have been renamed during our redesign. Here is where most people are heading.', 'focused-schools' ); ?>
			</p>

			<div class="fs-error__actions">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => __( 'Back to home', 'focused-schools' ),
						'url'   => home_url( '/' ),
						'style' => 'primary',
					)
				);

				if ( $fs_blog ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => __( 'Read the blog', 'focused-schools' ),
							'url'   => get_permalink( $fs_blog ),
							'style' => 'secondary',
						)
					);
				}
				?>
			</div>

			<?php
			/*
			 * A search box, because a renamed page is usually still on the
			 * site — the reader just needs to find its new name.
			 */
			get_template_part( 'template-parts/components/blog-filters' );
			?>
		</div>
	</section>

	<section class="fs-error__reach" aria-labelledby="fs-error-reach-title">
		<div class="fs-container fs-container--shell">
			<header class="fs-blog__head">
				<div>
					<p class="fs-eyebrow"><?php esc_html_e( 'Try one of these', 'focused-schools' ); ?></p>
					<h2 class="fs-blog__heading" id="fs-error-reach-title"><?php esc_html_e( 'Where most people go next.', 'focused-schools' ); ?></h2>
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
							'title' => __( 'Talk to us', 'focused-schools' ),
							'body'  => __( 'Tell us what you were looking for and we will point you at it.', 'focused-schools' ),
							'label' => __( 'Contact us', 'focused-schools' ),
							'url'   => home_url( '/contact/' ),
							'rule'  => 'lime',
						),
					),
				)
			);
			?>
		</div>
	</section>
</main>

<?php
get_footer();
