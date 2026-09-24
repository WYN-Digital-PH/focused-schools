/**
 * Podcast video facade: plays the episode in the card rather than loading a
 * YouTube iframe on page load.
 *
 * The card ships a still thumbnail and a real link to YouTube, so it works
 * with no JavaScript at all and the page requests nothing from YouTube until
 * a reader asks. This upgrades that link: the first click swaps the
 * thumbnail for a player in the same aspect-locked shell, so nothing shifts.
 *
 * Modifier-clicks and middle-clicks are left alone, because someone doing
 * that is deliberately opening YouTube in a new tab.
 */
( function () {
	'use strict';

	function play( card, shell ) {
		var videoId = card.getAttribute( 'data-video' );

		if ( ! /^[A-Za-z0-9_-]{11}$/.test( videoId ) ) {
			return false;
		}

		var title = card.querySelector( '.fs-video__title' );

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( videoId ) + '?autoplay=1&rel=0&playsinline=1';
		iframe.title = title ? title.textContent.trim() : 'YouTube video';
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.setAttribute( 'allowfullscreen', '' );

		shell.innerHTML = '';
		shell.appendChild( iframe );

		return true;
	}

	document.addEventListener( 'click', function ( event ) {
		if ( event.metaKey || event.ctrlKey || event.shiftKey || 1 === event.button ) {
			return;
		}

		var trigger = event.target.closest( '[data-video-play]' );

		if ( ! trigger ) {
			return;
		}

		var card = trigger.closest( '[data-video]' );

		if ( ! card ) {
			return;
		}

		var shell = card.querySelector( '[data-video-shell]' );

		if ( ! shell ) {
			return;
		}

		// Only swallow the navigation if the player actually mounted.
		if ( play( card, shell ) ) {
			event.preventDefault();
		}
	} );
} )();
