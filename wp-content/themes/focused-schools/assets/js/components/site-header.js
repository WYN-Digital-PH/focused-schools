/**
 * Header: the "Explore" overlay toggle, plus the scroll-progress bar.
 *
 * - Manages aria-expanded on the toggle button; shows/hides the overlay via
 *   the `hidden` attribute (same pattern as home-hero.js's video modal and
 *   team-bio-modal.js).
 * - Locks body scroll while open, traps focus (Tab/Shift+Tab wrap), closes
 *   on Escape or the close button, and returns focus to the toggle button.
 * - Scroll progress: a thin bar under the header bar, width tied to how far
 *   down the page the visitor has scrolled. Passive listener, rAF-throttled.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-fs-nav-toggle]' );
	var menu = toggle ? document.getElementById( toggle.getAttribute( 'aria-controls' ) ) : null;

	if ( toggle && menu ) {
		var closeButtons = menu.querySelectorAll( '[data-fs-nav-close]' );

		function focusableElements() {
			return Array.prototype.slice.call(
				menu.querySelectorAll( 'a[href], button:not([disabled])' )
			);
		}

		function closeMenu() {
			menu.hidden = true;
			toggle.setAttribute( 'aria-expanded', 'false' );
			document.body.classList.remove( 'fs-modal-open' );
			document.removeEventListener( 'keydown', trapFocus );
			toggle.focus();
		}

		function openMenu() {
			menu.hidden = false;
			toggle.setAttribute( 'aria-expanded', 'true' );
			document.body.classList.add( 'fs-modal-open' );
			document.addEventListener( 'keydown', trapFocus );
		}

		function trapFocus( event ) {
			if ( event.key === 'Escape' ) {
				closeMenu();
				return;
			}

			if ( event.key !== 'Tab' ) {
				return;
			}

			var focusable = focusableElements();

			if ( ! focusable.length ) {
				return;
			}

			var first = focusable[ 0 ];
			var last = focusable[ focusable.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';

			if ( isOpen ) {
				closeMenu();
			} else {
				openMenu();
			}
		} );

		closeButtons.forEach( function ( button ) {
			button.addEventListener( 'click', closeMenu );
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
