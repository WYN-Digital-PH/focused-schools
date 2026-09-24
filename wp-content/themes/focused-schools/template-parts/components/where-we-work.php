<?php
/**
 * Component: Where We Work.
 *
 * Contract ($args):
 * - eyebrow      (string)
 * - heading      (string)
 * - map_states   (array) passed straight to partner-map.php
 * - map_active   (string) state name to open on load
 * - list_eyebrow (string) label above the district list
 * - states       (array) each item: { name (string), districts (string[]) }
 * - cta_line     (string)
 * - cta_label    (string)
 * - cta_url      (string)
 *
 * The homepage's district list is a different shape from the About page's:
 * plain state columns rather than the numbered rows and chips of
 * partner-districts.php. They are separate components because the approved
 * design draws them differently, not because the data differs — both read the
 * same Partners records.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;

$fs_eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$fs_heading      = isset( $args['heading'] ) ? $args['heading'] : '';
$fs_map_states   = isset( $args['map_states'] ) && is_array( $args['map_states'] ) ? $args['map_states'] : array();
$fs_map_active   = isset( $args['map_active'] ) ? (string) $args['map_active'] : '';
$fs_list_eyebrow = isset( $args['list_eyebrow'] ) ? $args['list_eyebrow'] : '';
$fs_states       = isset( $args['states'] ) && is_array( $args['states'] ) ? $args['states'] : array();
$fs_cta_line     = isset( $args['cta_line'] ) ? $args['cta_line'] : '';
$fs_cta_label    = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$fs_cta_url      = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
?>
<div class="fs-where-we-work">
	<header class="fs-where-we-work__head">
		<?php if ( $fs_eyebrow ) : ?>
			<p class="fs-eyebrow"><?php echo esc_html( $fs_eyebrow ); ?></p>
		<?php endif; ?>
		<?php if ( $fs_heading ) : ?>
			<h3 class="fs-where-we-work__title"><?php echo esc_html( $fs_heading ); ?></h3>
		<?php endif; ?>
	</header>

	<?php
	get_template_part(
		'template-parts/components/partner-map',
		null,
		array(
			'states' => $fs_map_states,
			'active' => $fs_map_active,
		)
	);
	?>

	<?php if ( $fs_states ) : ?>
		<?php if ( $fs_list_eyebrow ) : ?>
			<p class="fs-eyebrow fs-where-we-work__list-label"><?php echo esc_html( $fs_list_eyebrow ); ?></p>
		<?php endif; ?>

		<div class="fs-where-we-work__list">
			<?php foreach ( $fs_states as $fs_state ) : ?>
				<?php
				$fs_name      = isset( $fs_state['name'] ) ? $fs_state['name'] : '';
				$fs_districts = isset( $fs_state['districts'] ) && is_array( $fs_state['districts'] ) ? $fs_state['districts'] : array();

				if ( '' === trim( (string) $fs_name ) || empty( $fs_districts ) ) {
					continue;
				}
				?>
				<section>
					<h4><?php echo esc_html( $fs_name ); ?></h4>
					<ul>
						<?php foreach ( $fs_districts as $fs_district ) : ?>
							<li><?php echo esc_html( $fs_district ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $fs_cta_line || ( $fs_cta_label && $fs_cta_url ) ) : ?>
		<div class="fs-where-we-work__cta">
			<?php if ( $fs_cta_line ) : ?>
				<p class="fs-where-we-work__cta-line"><?php echo esc_html( $fs_cta_line ); ?></p>
			<?php endif; ?>
			<?php
			if ( $fs_cta_label && $fs_cta_url ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $fs_cta_label,
						'url'   => $fs_cta_url,
						'style' => 'primary',
					)
				);
			}
			?>
		</div>
	<?php endif; ?>
</div>
