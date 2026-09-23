/**
 * Cycle of Excellence — scroll-scrubbed diagram.
 *
 * The section is taller than the viewport and its stage is sticky, so the
 * diagram holds still on screen while the page scrolls past it. Scroll
 * progress through that extra height drives three things in step:
 *
 *   - the mark rotates through exactly 360° across the section;
 *   - an orbiting dot tracks the same angle at the mark's radius, which is
 *     read from the --fs-cycle-orbit-r custom property so it follows the mark
 *     size at every breakpoint instead of being pinned to the desktop value;
 *   - the phase rows advance, one per equal slice of the scroll.
 *
 * Hovering, focusing or clicking a row takes over from the scroll position,
 * so the diagram is usable without scrolling at all.
 *
 * Under prefers-reduced-motion nothing rotates: the rows still step, so the
 * content is never gated behind motion.
 */
( function () {
	'use strict';

	var section = document.querySelector( '[data-fs-cycle-section]' );
	var group = document.querySelector( '[data-fs-cycle]' );

	if ( ! section || ! group ) {
		return;
	}

	var rotor = section.querySelector( '[data-fs-cycle-rotor]' );
	var orbit = section.querySelector( '[data-fs-cycle-orbit]' );
	var mark = section.querySelector( '.fs-cycle__mark' );
	var steps = [].slice.call( group.querySelectorAll( '[data-fs-cycle-step]' ) );
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var frame = null;
	var current = -1;

	if ( ! steps.length ) {
		return;
	}

	function setStep( index ) {
		if ( index === current ) {
			return;
		}

		current = index;

		steps.forEach( function ( step, i ) {
			var isActive = i === index;
			var parent = step.parentElement;

			if ( isActive ) {
				step.setAttribute( 'aria-current', 'step' );
			} else {
				step.removeAttribute( 'aria-current' );
			}

			if ( parent ) {
				parent.classList.toggle( 'is-active', isActive );
			}
		} );
	}

	function scrub() {
		frame = null;

		var rect = section.getBoundingClientRect();
		var span = Math.max( 1, rect.height - window.innerHeight );
		var progress = Math.min( 1, Math.max( 0, -rect.top / span ) );

		if ( ! reduced ) {
			var degrees = progress * 360;

			if ( rotor ) {
				rotor.style.transform = 'rotate(' + degrees.toFixed( 2 ) + 'deg)';
			}

			if ( orbit ) {
				var radius = mark
					? ( window.getComputedStyle( mark ).getPropertyValue( '--fs-cycle-orbit-r' ) || '183px' ).trim()
					: '183px';

				orbit.style.transform = 'rotate(' + degrees.toFixed( 2 ) + 'deg) translateX(' + radius + ')';
			}
		}

		setStep( Math.min( steps.length - 1, Math.floor( progress * steps.length ) ) );
	}

	function onScroll() {
		if ( frame ) {
			return;
		}

		frame = window.requestAnimationFrame( scrub );
	}

	steps.forEach( function ( step, i ) {
		[ 'click', 'mouseenter', 'focus' ].forEach( function ( event ) {
			step.addEventListener( event, function () {
				setStep( i );
			} );
		} );
	} );

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll, { passive: true } );
	scrub();
} )();
