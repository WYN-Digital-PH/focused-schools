/**
 * Copy-link control on an article's share rail.
 *
 * The button ships hidden and is revealed here, so a reader without
 * JavaScript sees the four share links — which are plain hrefs and work on
 * their own — rather than a button that does nothing. Everything else in the
 * rail needs no script at all.
 *
 * Two copy paths, because the modern one is not always available: the
 * Clipboard API requires a secure context, so it is absent over plain HTTP,
 * which includes most local development. The execCommand fallback has no
 * such requirement, so the control behaves the same in both.
 */
( function () {
	'use strict';

	var button = document.querySelector( '[data-fs-copy]' );

	if ( ! button ) {
		return;
	}

	var modern = !! ( navigator.clipboard && navigator.clipboard.writeText );
	var legacy = 'function' === typeof document.execCommand;

	if ( ! modern && ! legacy ) {
		return;
	}

	var toast = document.querySelector( '[data-fs-copy-toast]' );
	var timer = null;

	button.hidden = false;

	function say( message ) {
		if ( ! toast ) {
			return;
		}

		toast.textContent = message;
		window.clearTimeout( timer );
		timer = window.setTimeout( function () {
			toast.textContent = '';
		}, 2400 );
	}

	function copyLegacy( value ) {
		var field = document.createElement( 'textarea' );

		field.value = value;
		field.setAttribute( 'readonly', '' );

		// Off-screen rather than hidden: a field that is not rendered cannot
		// be selected, and selection is what execCommand copies.
		field.style.position = 'fixed';
		field.style.top = '-1000px';
		field.style.opacity = '0';

		document.body.appendChild( field );
		field.select();

		var copied = false;

		try {
			copied = document.execCommand( 'copy' );
		} catch ( error ) {
			copied = false;
		}

		document.body.removeChild( field );

		return copied;
	}

	button.addEventListener( 'click', function () {
		var url = button.getAttribute( 'data-fs-copy-url' ) || window.location.href;

		if ( modern ) {
			navigator.clipboard.writeText( url ).then(
				function () {
					say( 'Link copied' );
				},
				function () {
					say( copyLegacy( url ) ? 'Link copied' : 'Press Ctrl+C to copy' );
				}
			);

			return;
		}

		say( copyLegacy( url ) ? 'Link copied' : 'Press Ctrl+C to copy' );
	} );
} )();
