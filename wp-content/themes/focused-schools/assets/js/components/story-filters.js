/**
 * Impact Stories grid: Load more, and a live result count.
 *
 * Filtering itself is server-rendered — every chip is a real link carrying the
 * filter in the URL, so a filtered view is shareable, back-button safe and
 * works with JavaScript off. This file only handles the progressive parts:
 * revealing the next page of cards without a round trip, and keeping the
 * count honest as that happens.
 *
 * Cards past the first page carry a real `hidden` attribute, so with no JS
 * they simply stay hidden behind a link-free button rather than appearing
 * broken.
 */
( function () {
	'use strict';

	var button = document.querySelector( '[data-fs-story-load-more]' );

	if ( ! button ) {
		return;
	}

	var grid = document.getElementById( button.getAttribute( 'aria-controls' ) );
	var count = document.querySelector( '[data-fs-story-count]' );

	if ( ! grid ) {
		return;
	}

	var PER_PAGE = 9;

	button.addEventListener( 'click', function () {
		var hidden = grid.querySelectorAll( '[hidden]' );
		var revealed = 0;

		Array.prototype.some.call( hidden, function ( card ) {
			if ( revealed >= PER_PAGE ) {
				return true;
			}

			card.hidden = false;
			revealed++;

			return false;
		} );

		var remaining = grid.querySelectorAll( '[hidden]' ).length;
		var total = parseInt( button.getAttribute( 'data-fs-total' ), 10 ) || grid.children.length;
		var shown = total - remaining;

		if ( count ) {
			// aria-live on the element announces this to screen readers.
			count.textContent = count.textContent.replace( /\d+/, shown );
		}

		if ( ! remaining ) {
			button.hidden = true;
		}

		// Focus stays on the button while it is still there, so the reader
		// does not lose their place in the grid.
		if ( button.hidden && count ) {
			count.setAttribute( 'tabindex', '-1' );
			count.focus();
		}
	} );
} )();
