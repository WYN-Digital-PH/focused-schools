/**
 * Statistics counter: animates the visual count up to the real value
 * already present in the markup (data-fs-count-to / .fs-stats__number).
 *
 * The real final number is always in the HTML source, so this script only
 * enhances the visual presentation — it never introduces the value itself.
 * Entirely skipped under prefers-reduced-motion.
 */
( function () {
	'use strict';

	var prefersReducedMotion =
		window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	var items = document.querySelectorAll( '[data-fs-count-to]' );

	if ( ! items.length || prefersReducedMotion ) {
		return;
	}

	var DURATION = 1200;

	function animateItem( item ) {
		var target = parseFloat( item.getAttribute( 'data-fs-count-to' ) );
		var numberEl = item.querySelector( '.fs-stats__number' );

		if ( ! numberEl || isNaN( target ) ) {
			return;
		}

		var start = null;

		function step( timestamp ) {
			if ( start === null ) {
				start = timestamp;
			}

			var progress = Math.min( ( timestamp - start ) / DURATION, 1 );
			var current = Math.round( target * progress );

			numberEl.textContent = current;

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			} else {
				numberEl.textContent = target;
			}
		}

		window.requestAnimationFrame( step );
	}

	if ( ! window.IntersectionObserver ) {
		items.forEach( animateItem );
		return;
	}

	var observer = new IntersectionObserver(
		function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					animateItem( entry.target );
					obs.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.4 }
	);

	items.forEach( function ( item ) {
		observer.observe( item );
	} );
} )();
