<?php
/**
 * Footer template.
 *
 * Foundation only — no visual page design. Provides the Footer navigation
 * menu and closes the document.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;
?>

	<footer class="fs-site-footer">
		<div class="fs-container">
			<nav class="fs-nav fs-nav--footer" aria-label="<?php esc_attr_e( 'Footer', 'focused-schools' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
