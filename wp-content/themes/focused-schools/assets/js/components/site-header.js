/**
 * Header: the nav dropdown panel toggle, plus the scroll-progress bar.
 *
 * Dropdown panel — same `hidden`-attribute + focus-trap pattern used by the
 * video/team-bio modals elsewhere in this theme, minus body-scroll-lock
 * (a compact dropdown doesn't cover the viewport). Closes on Escape, on
 * clicking outside the panel/toggle, or on navigating via a panel link;
 * focus returns to the toggle except when closed by an outside click,
 * where returning focus would fight whatever the visitor just clicked.
 *
 * Scroll progress: a thin bar under the header bar, width tied to how far down
 * the page the visitor has scrolled. Passive listener, rAF-throttled.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-fs-nav-toggle]' );
	var menu = toggle ? document.getElementById( toggle.getAttribute( 'aria-controls' ) ) : null;

	if ( toggle && menu ) {

		function focusableElements() {
			return Array.prototype.slice.call(
				menu.querySelectorAll( 'a[href], button:not([disabled])' )
			);
		}

		function closeMenu( returnFocus ) {
			menu.hidden = true;
			toggle.setAttribute( 'aria-expanded', 'false' );
			document.removeEventListener( 'keydown', trapFocus );
			document.removeEventListener( 'click', onOutsideClick, true );

			if ( false !== returnFocus ) {
				toggle.focus();
			}
		}

		function openMenu() {
			menu.hidden = false;
			toggle.setAttribute( 'aria-expanded', 'true' );
			document.addEventListener( 'keydown', trapFocus );

			// Deferred so the click that opened the menu isn't also seen as
			// the "outside" click that immediately closes it again.
			setTimeout( function () {
				document.addEventListener( 'click', onOutsideClick, true );
			}, 0 );
		}

		function onOutsideClick( event ) {
			if ( ! menu.contains( event.target ) && ! toggle.contains( event.target ) ) {
				closeMenu( false );
			}
		}

		function trapFocus( event ) {
			if ( 'Escape' === event.key ) {
				closeMenu();
				return;
			}

			if ( 'Tab' !== event.key ) {
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
			if ( 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
				closeMenu();
			} else {
				openMenu();
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
