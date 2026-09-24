<?php
/**
 * Server render for focused-schools/service-lanes.
 *
 * One block definition rendered once per fs_service record, per the page
 * spec: there are not three service layouts, there is one layout rendered
 * three times. Adding a fourth service needs no template or page edit.
 *
 * Everything that varies down the page is derived from loop position, never
 * authored: the lane numeral, the alternating ground, the media side, and
 * the anchor the index links to.
 *
 * @package FocusedSchools
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$fs_services = focused_schools_service_query();

if ( ! $fs_services ) {
	return;
}

$fs_img = FOCUSED_SCHOOLS_THEME_URI . '/assets/img/';

/*
 * Theme-bundled fallbacks, used only until each service has its own featured
 * image set in wp-admin.
 */
$fs_photos = array( 'retreat-1.jpg', 'retreat-3.jpg', 'retreat-2.jpg' );

$fs_lane_index = 0;
$fs_has_video  = false;

foreach ( $fs_services as $fs_service_post ) :
	++$fs_lane_index;

	if ( focused_schools_youtube_id( get_post_meta( $fs_service_post->ID, '_fs_service_video_url', true ) ) ) {
		$fs_has_video = true;
	}

	$fs_alt   = ( 0 === $fs_lane_index % 2 );
	$fs_photo = $fs_photos[ ( $fs_lane_index - 1 ) % count( $fs_photos ) ];
	?>
	<section class="fs-services__lane<?php echo $fs_alt ? ' fs-services__lane--paper' : ''; ?>" aria-labelledby="fs-lane-<?php echo esc_attr( $fs_service_post->post_name ); ?>">
		<div class="fs-container fs-container--shell">
			<?php
			get_template_part(
				'template-parts/components/service-lane',
				null,
				array(
					'post'      => $fs_service_post,
					'index'     => $fs_lane_index,
					'flip'      => $fs_alt,
					'image_url' => $fs_img . $fs_photo,
					'image_alt' => sprintf(
						/* translators: %s: service name. */
						__( 'Focused Schools partners at work: %s.', 'focused-schools' ),
						$fs_service_post->post_title
					),
				)
			);
			?>
		</div>
	</section>
	<?php
endforeach;

// Only ship the dialog when a lane can actually open it.
if ( $fs_has_video ) {
	get_template_part( 'template-parts/components/video-modal' );
}
