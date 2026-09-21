<?php
/**
 * Single template for fs_impact_story.
 *
 * WordPress's native single-{post_type}.php template hierarchy — renders
 * individual Impact Story posts at /impact-stories/{slug}/ (rewrite slug
 * registered in the plugin, see docs/architecture.md §3.4).
 *
 * Unlike the page-{slug}.php templates, this CPT is new, Gutenberg-only
 * content per the project plan (never Elementor-authored), so there is no
 * Elementor-coexistence branch here — just the standard Loop.
 *
 * Yoast SEO compatibility: uses the standard Loop and relies on wp_head()
 * (already called in header.php) plus title-tag theme support (already
 * registered) rather than any custom <title>/meta output — that is what
 * Yoast's own hooks integrate with. Could not be verified live; Yoast is
 * not installed in this local environment.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			$fs_district   = get_post_meta( get_the_ID(), '_fs_impact_story_district_or_school', true );
			$fs_state      = get_post_meta( get_the_ID(), '_fs_impact_story_state', true );
			$fs_year       = get_post_meta( get_the_ID(), '_fs_impact_story_year', true );
			$fs_featured   = get_post_meta( get_the_ID(), '_fs_impact_story_featured', true );
			$fs_meta_parts = array_filter( array( $fs_district, $fs_state, $fs_year ) );
			?>
			<article <?php post_class( 'fs-impact-story-single' ); ?> id="post-<?php the_ID(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="fs-impact-story-single__media">
						<?php the_post_thumbnail( 'large', array( 'class' => 'fs-impact-story-single__image' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="fs-container fs-impact-story-single__content">
					<header class="fs-impact-story-single__header">
						<?php if ( $fs_featured ) : ?>
							<p class="fs-impact-story-single__badge"><?php esc_html_e( 'Featured', 'focused-schools' ); ?></p>
						<?php endif; ?>
						<?php the_title( '<h1 class="fs-impact-story-single__title">', '</h1>' ); ?>
						<?php if ( $fs_meta_parts ) : ?>
							<p class="fs-impact-story-single__meta"><?php echo esc_html( implode( ' &middot; ', $fs_meta_parts ) ); ?></p>
						<?php endif; ?>
					</header>

					<div class="fs-impact-story-single__body">
						<?php the_content(); ?>
					</div>

					<p class="fs-impact-story-single__back">
						<a href="<?php echo esc_url( home_url( '/impact-stories/' ) ); ?>">
							<?php esc_html_e( 'Back to Impact Stories', 'focused-schools' ); ?>
						</a>
					</p>
				</div>
			</article>
			<?php
		endwhile;
	else :
		?>
		<div class="fs-container">
			<p><?php esc_html_e( 'Nothing found.', 'focused-schools' ); ?></p>
		</div>
		<?php
	endif;
	?>
</main>

<?php
get_footer();
