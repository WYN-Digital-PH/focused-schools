/**
 * Team bio modal: populates the shared dialog (team-bio-modal.php) from the
 * clicked team-card's hidden `<template data-fs-bio-content>` — see
 * team-card.php's `bio_modal` prop. Same open/close/focus/scroll-lock
 * pattern as home-hero.js's video modal.
 */
( function () {
	'use strict';

	var modal = document.querySelector( '[data-fs-bio-modal]' );

	if ( ! modal ) {
		return;
	}

	var body = modal.querySelector( '[data-fs-bio-body]' );
	var openTrigger = null;

	function closeModal() {
		modal.hidden = true;
		document.body.classList.remove( 'fs-modal-open' );
		body.innerHTML = '';

		if ( openTrigger ) {
			openTrigger.focus();
		}
	}

	function openModal( template, trigger ) {
		openTrigger = trigger;

		body.innerHTML = '';
		body.appendChild( template.content.cloneNode( true ) );

		var heading = body.querySelector( 'h3' );
		if ( heading ) {
			heading.id = 'fs-bio-modal-title';
		}

		modal.hidden = false;
		document.body.classList.add( 'fs-modal-open' );

		var closeButton = modal.querySelector( '[data-fs-bio-close]:not(.fs-bio-modal__backdrop)' );
		if ( closeButton ) {
			closeButton.focus();
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var opener = event.target.closest( '[data-fs-bio-open]' );

		if ( opener ) {
			var card = opener.closest( '.fs-team-card' );
			var template = card ? card.querySelector( '[data-fs-bio-content]' ) : null;

			if ( template ) {
				openModal( template, opener );
			}
			return;
		}

		if ( event.target.closest( '[data-fs-bio-close]' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && ! modal.hidden ) {
			closeModal();
		}
	} );
} )();
