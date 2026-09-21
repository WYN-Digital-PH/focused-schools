/**
 * Header navigation: accessible mobile toggle.
 *
 * - Manages aria-expanded on the toggle button.
 * - Shows/hides the nav via the .is-open class (see site-header.css).
 * - Closes on Escape, returning focus to the toggle button.
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

	function closeMenu() {
		menu.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
	}

	function openMenu() {
		menu.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';

		if ( isOpen ) {
			closeMenu();
		} else {
			openMenu();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
			closeMenu();
			toggle.focus();
		}
	} );
} )();
