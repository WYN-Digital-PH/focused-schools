/**
 * Home Hero video. Two separate things share this file:
 *
 * 1. The ambient loop — a muted, controls-free YouTube embed behind the hero
 *    poster, created here rather than printed in the markup so it is never
 *    requested under prefers-reduced-motion and a no-JS visitor simply keeps
 *    the poster. Play/pause is driven through the YouTube iframe API.
 * 2. The "Watch with sound" modal — the heavy, audible iframe, still deferred
 *    until a visitor asks for it. The iframe is destroyed on close so a closed
 *    modal never keeps audio playing, and the ambient loop is paused while the
 *    modal is open so two soundtracks never compete.
 */
( function () {
	'use strict';

	var NOCOOKIE = 'https://www.youtube-nocookie.com';
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ------------------------------------------------ Ambient hero loop --- */

	var heroFrame = document.querySelector( '[data-fs-hero-frame]' );
	var heroSlot = document.querySelector( '[data-fs-hero-video]' );
	var playButton = document.querySelector( '[data-fs-hero-play]' );
	var playLabel = document.querySelector( '[data-fs-hero-play-label]' );
	var openButton = document.querySelector( '[data-fs-video-open]' );
	var ambientId = openButton ? openButton.getAttribute( 'data-fs-video-id' ) : '';

	function setAmbientPaused( paused ) {
		if ( ! heroFrame ) {
			return;
		}

		heroFrame.setAttribute( 'data-paused', paused ? 'true' : 'false' );

		if ( playButton ) {
			playButton.setAttribute( 'aria-pressed', paused ? 'true' : 'false' );
		}

		if ( playLabel ) {
			playLabel.textContent = paused ? playLabel.getAttribute( 'data-play-text' ) || 'Play' : playLabel.getAttribute( 'data-pause-text' ) || 'Pause';
		}

		var iframe = heroSlot ? heroSlot.querySelector( 'iframe' ) : null;

		if ( iframe && iframe.contentWindow ) {
			iframe.contentWindow.postMessage(
				JSON.stringify( {
					event: 'command',
					func: paused ? 'pauseVideo' : 'playVideo',
					args: []
				} ),
				NOCOOKIE
			);
		}
	}

	if ( heroFrame && heroSlot && ambientId ) {
		if ( reduced ) {
			// Decorative motion: never even fetch it.
			setAmbientPaused( true );
		} else {
			var ambient = document.createElement( 'iframe' );

			ambient.className = 'fs-home-hero__video';
			ambient.title = 'Muted background video';
			ambient.tabIndex = -1;
			ambient.setAttribute( 'aria-hidden', 'true' );
			ambient.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			ambient.setAttribute( 'referrerpolicy', 'strict-origin-when-cross-origin' );
			ambient.src = NOCOOKIE + '/embed/' + encodeURIComponent( ambientId ) +
				'?autoplay=1&mute=1&controls=0&loop=1&playlist=' + encodeURIComponent( ambientId ) +
				'&playsinline=1&modestbranding=1&rel=0&disablekb=1&enablejsapi=1';

			heroSlot.appendChild( ambient );
		}

		if ( playButton ) {
			if ( playLabel ) {
				playLabel.setAttribute( 'data-pause-text', playLabel.textContent );
				playLabel.setAttribute( 'data-play-text', 'Play' );
			}

			playButton.addEventListener( 'click', function () {
				setAmbientPaused( 'true' !== heroFrame.getAttribute( 'data-paused' ) );
			} );
		}
	}

	/* --------------------------------------------- Watch-with-sound modal --- */

	var modal = document.querySelector( '[data-fs-video-modal]' );

	if ( ! modal ) {
		return;
	}

	var frame = modal.querySelector( '[data-fs-video-frame]' );
	var wasPausedBeforeModal = false;
	var titleEl = modal.querySelector( '[data-fs-video-modal-title]' );
	var openTrigger = null;

	function closeModal() {
		modal.hidden = true;
		document.body.classList.remove( 'fs-modal-open' );
		frame.innerHTML = '';

		// Bring the ambient loop back, unless the visitor had paused it.
		if ( heroFrame && ! reduced && ! wasPausedBeforeModal ) {
			setAmbientPaused( false );
		}

		if ( openTrigger ) {
			openTrigger.focus();
		}
	}

	function openModal( youtubeId, title, trigger ) {
		if ( ! youtubeId ) {
			return;
		}

		// Never let two soundtracks compete.
		wasPausedBeforeModal = heroFrame ? 'true' === heroFrame.getAttribute( 'data-paused' ) : true;
		setAmbientPaused( true );

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
