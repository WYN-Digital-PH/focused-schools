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
		<?php get_template_part( 'template-parts/components/site-footer' ); ?>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
