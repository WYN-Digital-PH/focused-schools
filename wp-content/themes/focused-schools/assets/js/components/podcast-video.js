/**
 * Podcast video facade: defers loading the (heavy) YouTube iframe until a
 * visitor actually asks for it — by pressing the thumbnail's play button or
 * the card's "Watch episode" link — rather than on initial page load.
 *
 * The facade and the eventual iframe both fill the same aspect-ratio-locked
 * .fs-podcast-card__video box (see podcast-card.css), so swapping one for
 * the other never shifts layout. "Watch episode" is a real link to the
 * video, so without JS the card still works; with JS the click is upgraded
 * to the in-place player.
 */
( function () {
	'use strict';

	function loadVideo( container ) {
		var youtubeId = container.getAttribute( 'data-youtube-id' );

		if ( ! youtubeId || container.querySelector( 'iframe' ) ) {
			return;
		}

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( youtubeId ) + '?autoplay=1&rel=0';
		iframe.title = container.getAttribute( 'data-youtube-title' ) || 'YouTube video';
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.allowFullscreen = true;

		container.innerHTML = '';
		container.appendChild( iframe );
		iframe.focus();
	}

	document.addEventListener( 'click', function ( event ) {
		var facade = event.target.closest( '.fs-podcast-card__video-facade' );
		var watch = event.target.closest( '[data-fs-video-play]' );
		var container = null;

		if ( facade ) {
			container = facade.closest( '.fs-podcast-card__video' );
		} else if ( watch ) {
			var card = watch.closest( '.fs-podcast-card' );
			container = card ? card.querySelector( '.fs-podcast-card__video' ) : null;
		}

		if ( ! container ) {
			return;
		}

		event.preventDefault();
		loadVideo( container );
	} );
} )();
