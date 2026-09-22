/**
 * Team grid "Load more": reveals team-card.php instances beyond the initial
 * visible count. Extra cards carry a real `hidden` attribute (see
 * page-about-our-mission-vision.php) — without JS they simply stay hidden,
 * same as the approved design's own click-to-reveal behavior.
 */
( function () {
	'use strict';

	var button = document.querySelector( '[data-fs-team-load-more]' );

	if ( ! button ) {
		return;
	}

	var grid = document.getElementById( button.getAttribute( 'aria-controls' ) );

	if ( ! grid ) {
		return;
	}

	button.addEventListener( 'click', function () {
		var hiddenCards = grid.querySelectorAll( '[hidden]' );

		hiddenCards.forEach( function ( card ) {
			card.hidden = false;
		} );

		button.hidden = true;
	} );
} )();
