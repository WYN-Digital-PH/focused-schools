/**
 * Home beliefs section — scroll-held statement.
 *
 * The section is taller than the viewport and its inner block sticks, so the
 * belief statement holds on screen while the reader scrolls past it. The coral
 * rule under the opening phrase grows with that travel, reaching full width at
 * roughly the section's midpoint (progress x 2.2, clamped) so it is not still
 * creeping as the reader leaves.
 *
 * Under prefers-reduced-motion the rule is left alone: the stylesheet shows it
 * already complete, so the emphasis still reads without any motion.
 */
( function () {
	'use strict';

	var hold = document.querySelector( '[data-fs-home-hold]' );
	var rule = document.querySelector( '[data-fs-emph-rule]' );

	if ( ! hold || ! rule ) {
		return;
	}

	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	var frame = null;

	function scrub() {
		frame = null;

		var rect = hold.getBoundingClientRect();
		var span = Math.max( 1, rect.height - window.innerHeight );
		var progress = Math.min( 1, Math.max( 0, -rect.top / span ) );

		rule.style.width = ( Math.min( 1, progress * 2.2 ) * 100 ).toFixed( 1 ) + '%';
	}

	function onScroll() {
		if ( frame ) {
			return;
		}

		frame = window.requestAnimationFrame( scrub );
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll, { passive: true } );
	scrub();
} )();
