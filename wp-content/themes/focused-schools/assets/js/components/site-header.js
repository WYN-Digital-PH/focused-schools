/**
 * Header navigation: accessible mobile toggle.
 *
 * - Manages aria-expanded on the toggle button.
 * - Shows/hides the nav via the .is-open class (see site-header.css).
 * - Locks body scroll while open (the panel can cover the whole viewport
 *   on narrow screens).
 * - Traps focus within the open panel (Tab/Shift+Tab wrap at its edges),
 *   closes on Escape, and returns focus to the toggle button on close.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-fs-nav-toggle]' );

	if ( ! toggle ) {
		return;
	}

	var menu = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

	if ( ! menu ) {
		return;
	}

	function focusableElements() {
		return Array.prototype.slice.call(
			menu.querySelectorAll( 'a[href], button:not([disabled])' )
		);
	}

	function closeMenu() {
		menu.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'fs-modal-open' );
		document.removeEventListener( 'keydown', trapFocus );
	}

	function openMenu() {
		menu.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.classList.add( 'fs-modal-open' );
		document.addEventListener( 'keydown', trapFocus );
	}

	function trapFocus( event ) {
		if ( event.key === 'Escape' ) {
			closeMenu();
			toggle.focus();
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
} )();
