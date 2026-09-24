/**
 * Video modal.
 *
 * Any element carrying data-fs-video="<id>" opens the dialog rendered by
 * template-parts/components/video-modal.php and plays that YouTube video.
 *
 * The iframe is built on open and removed on close: clearing it is what
 * actually stops playback, and it means no request reaches YouTube until a
 * reader asks for the video. Without JavaScript nothing here runs and no
 * trigger appears, so the page loses a convenience, not content — the same
 * progressive-enhancement rule the partner map follows.
 */
( function () {
	'use strict';

	var modal = document.querySelector( '[data-fs-video-modal]' );

	if ( ! modal ) {
		return;
	}

	var frame = modal.querySelector( '[data-fs-video-frame]' );
	var title = modal.querySelector( '.fs-video-modal__title' );
	var opener = null;

	function focusable() {
		return Array.prototype.slice.call(
			modal.querySelectorAll( 'button, iframe' )
		).filter( function ( node ) {
			return null !== node.offsetParent;
		} );
	}

	function open( trigger ) {
		var id = trigger.getAttribute( 'data-fs-video' );

		if ( ! /^[A-Za-z0-9_-]{11}$/.test( id ) ) {
			return;
		}

		opener = trigger;
		title.textContent = trigger.getAttribute( 'data-fs-video-title' ) || '';

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( id ) +
			'?autoplay=1&rel=0&playsinline=1';
		iframe.title = title.textContent;
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.setAttribute( 'allowfullscreen', '' );

		frame.appendChild( iframe );
		modal.hidden = false;
		document.body.classList.add( 'fs-has-modal' );
		title.focus();
	}

	function close() {
		if ( modal.hidden ) {
			return;
		}

		modal.hidden = true;
		frame.innerHTML = '';
		document.body.classList.remove( 'fs-has-modal' );

		if ( opener ) {
			opener.focus();
			opener = null;
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest( '[data-fs-video]' );

		if ( trigger ) {
			event.preventDefault();
			open( trigger );
			return;
		}

		if ( event.target.closest( '[data-fs-video-close]' ) ) {
			close();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( modal.hidden ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			close();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		// Keep focus inside the dialog while it is open.
		var nodes = focusable();

		if ( ! nodes.length ) {
			return;
		}

		var first = nodes[ 0 ];
		var last = nodes[ nodes.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	} );
} )();
