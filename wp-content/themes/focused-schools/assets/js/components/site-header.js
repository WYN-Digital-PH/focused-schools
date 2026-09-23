/**
 * Header: the menu panel toggle, plus the scroll-progress bar.
 *
 * The panel is a dropdown anchored under the bar, matching the mockup — not a
 * full-screen dialog — so it deliberately does not trap focus or lock body
 * scrolling. It toggles data-open alongside aria-expanded, swaps the button's
 * aria-label between Open/Close menu, and closes on Escape or a click outside
 * the header.
 *
 * Scroll progress: a thin bar under the header bar, width tied to how far down
 * the page the visitor has scrolled. Passive listener, rAF-throttled.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-fs-nav-toggle]' );
	var menu = toggle ? document.getElementById( toggle.getAttribute( 'aria-controls' ) ) : null;
	var header = toggle ? toggle.closest( '.fs-site-header' ) : null;

	if ( toggle && menu ) {
		var setOpen = function ( open ) {
			menu.setAttribute( 'data-open', open ? 'true' : 'false' );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			toggle.setAttribute(
				'aria-label',
				open
					? toggle.getAttribute( 'data-label-close' ) || 'Close menu'
					: toggle.getAttribute( 'data-label-open' ) || 'Open menu'
			);
		};

		toggle.addEventListener( 'click', function () {
			setOpen( 'true' !== menu.getAttribute( 'data-open' ) );
		} );

		// Anywhere outside the header closes it — the panel is a dropdown,
		// not a modal, so it never traps focus or locks scrolling.
		document.addEventListener( 'click', function ( event ) {
			if ( 'true' !== menu.getAttribute( 'data-open' ) ) {
				return;
			}

			if ( header && header.contains( event.target ) ) {
				return;
			}

			setOpen( false );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && 'true' === menu.getAttribute( 'data-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );
	}

	var progress = document.querySelector( '[data-fs-nav-progress]' );

	if ( progress && ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		var raf = null;

		function updateProgress() {
			raf = null;
			var max = document.documentElement.scrollHeight - window.innerHeight;
			var pct = max > 0 ? Math.min( 1, window.scrollY / max ) * 100 : 0;
			progress.style.setProperty( '--fs-nav-progress', pct.toFixed( 2 ) + '%' );
		}

		function onScroll() {
			if ( raf ) {
				return;
			}
			raf = requestAnimationFrame( updateProgress );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		updateProgress();
	}
} )();
