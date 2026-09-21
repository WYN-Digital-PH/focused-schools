/**
 * Podcast video facade: defers loading the (heavy) YouTube iframe until a
 * visitor actually clicks to play it, rather than on initial page load.
 *
 * The facade button and the eventual iframe both fill the same
 * aspect-ratio-locked .fs-podcast-card__video container (see
 * podcast-card.css), so swapping one for the other never shifts layout.
 */
( function () {
	'use strict';

	function loadVideo( container ) {
		var youtubeId = container.getAttribute( 'data-youtube-id' );

		if ( ! youtubeId ) {
			return;
		}

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( youtubeId ) + '?autoplay=1';
		iframe.title = container.getAttribute( 'data-youtube-title' ) || 'YouTube video';
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.allowFullscreen = true;
		iframe.setAttribute( 'loading', 'lazy' );

		container.innerHTML = '';
		container.appendChild( iframe );
	}

	document.addEventListener( 'click', function ( event ) {
		var facade = event.target.closest( '.fs-podcast-card__video-facade' );

		if ( ! facade ) {
			return;
		}

		var container = facade.closest( '.fs-podcast-card__video' );

		if ( container ) {
			loadVideo( container );
		}
	} );
} )();
