<?php
/**
 * Component: Video Modal.
 *
 * No args. Rendered once per page by any template that prints
 * [data-fs-video] triggers; video-modal.js finds it and fills the frame with
 * a YouTube iframe on demand.
 *
 * The iframe is created on open and removed on close — clearing the src is
 * what actually stops playback — so no third-party frame, and no request to
 * YouTube at all, exists until a reader asks for one.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="fs-video-modal" data-fs-video-modal hidden>
	<button
		class="fs-video-modal__backdrop"
		type="button"
		data-fs-video-close
		aria-label="<?php esc_attr_e( 'Close video', 'focused-schools' ); ?>"
	></button>
	<div class="fs-video-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="fs-video-modal-title">
		<div class="fs-video-modal__bar">
			<p class="fs-video-modal__title" id="fs-video-modal-title" tabindex="-1"></p>
			<button
				class="fs-video-modal__close"
				type="button"
				data-fs-video-close
				aria-label="<?php esc_attr_e( 'Close video', 'focused-schools' ); ?>"
			>
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="fs-video-modal__frame" data-fs-video-frame></div>
	</div>
</div>
