<?php
/**
 * Component: Team Bio Modal (shared dialog shell).
 *
 * No args. Renders once per page, empty. assets/js/components/team-bio-modal.js
 * populates it from the clicked team-card's hidden `<template data-fs-bio-content>`
 * (see team-card.php's `bio_modal` prop) — this component has no knowledge of
 * any specific team member.
 *
 * Only include this once on a page that uses team-card.php with `bio_modal`
 * set (e.g. page-about-our-mission-vision.php) — including it more than once
 * would create duplicate `id="fs-bio-modal-title"` values.
 *
 * @package FocusedSchools
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="fs-bio-modal" data-fs-bio-modal role="dialog" aria-modal="true" aria-labelledby="fs-bio-modal-title" hidden>
	<button class="fs-bio-modal__backdrop" type="button" data-fs-bio-close aria-label="<?php esc_attr_e( 'Close', 'focused-schools' ); ?>"></button>
	<div class="fs-bio-modal__dialog">
		<button class="fs-bio-modal__close" type="button" data-fs-bio-close aria-label="<?php esc_attr_e( 'Close', 'focused-schools' ); ?>">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="fs-bio-modal__body" data-fs-bio-body></div>
	</div>
</div>
