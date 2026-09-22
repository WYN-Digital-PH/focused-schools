/**
 * Home Hero video modal: defers loading the (heavy) YouTube iframe until a
 * visitor clicks "Watch with sound" — no network request to YouTube happens
 * before that, same zero-overhead principle as podcast-video.js. The iframe
 * is destroyed on close, so a closed modal never keeps audio/video playing
 * in the background.
 */
( function () {
	'use strict';

	var modal = document.querySelector( '[data-fs-video-modal]' );

	if ( ! modal ) {
		return;
	}

	var frame = modal.querySelector( '[data-fs-video-frame]' );
	var titleEl = modal.querySelector( '[data-fs-video-modal-title]' );
	var openTrigger = null;

	function closeModal() {
		modal.hidden = true;
		document.body.classList.remove( 'fs-modal-open' );
		frame.innerHTML = '';

		if ( openTrigger ) {
			openTrigger.focus();
		}
	}

	function openModal( youtubeId, title, trigger ) {
		if ( ! youtubeId ) {
			return;
		}

		openTrigger = trigger;

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( youtubeId ) + '?autoplay=1';
		iframe.title = title || 'YouTube video';
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.allowFullscreen = true;

		frame.innerHTML = '';
		frame.appendChild( iframe );

		if ( titleEl && title ) {
			titleEl.textContent = title;
		}

		modal.hidden = false;
		document.body.classList.add( 'fs-modal-open' );

		var closeButton = modal.querySelector( '[data-fs-video-close]' );
		if ( closeButton ) {
			closeButton.focus();
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var opener = event.target.closest( '[data-fs-video-open]' );

		if ( opener ) {
			openModal(
				opener.getAttribute( 'data-fs-video-id' ),
				opener.getAttribute( 'data-fs-video-title' ),
				opener
			);
			return;
		}

		if ( event.target.closest( '[data-fs-video-close]' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && ! modal.hidden ) {
			closeModal();
		}
	} );
} )();
