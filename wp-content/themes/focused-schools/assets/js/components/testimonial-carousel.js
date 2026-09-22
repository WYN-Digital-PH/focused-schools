/**
 * Testimonial carousel: shows one slide at a time via arrows/dots. All
 * slides are always in the DOM (see testimonial-carousel.php) — this only
 * toggles visibility/aria state, so nothing breaks without JS. No
 * auto-advance timer, by design (a moving carousel a visitor didn't ask to
 * move is a common motion-sensitivity/attention complaint).
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-fs-carousel]' ).forEach( function ( carousel ) {
		var slides = Array.prototype.slice.call( carousel.querySelectorAll( '[data-fs-carousel-slide]' ) );
		var dots = Array.prototype.slice.call( carousel.querySelectorAll( '[data-fs-carousel-dot]' ) );
		var prevButton = carousel.querySelector( '[data-fs-carousel-prev]' );
		var nextButton = carousel.querySelector( '[data-fs-carousel-next]' );
		var status = carousel.querySelector( '[data-fs-carousel-status]' );
		var current = 0;

		if ( slides.length < 2 ) {
			return;
		}

		function show( index ) {
			current = ( index + slides.length ) % slides.length;

			slides.forEach( function ( slide, i ) {
				var isActive = i === current;
				slide.classList.toggle( 'is-active', isActive );
				if ( isActive ) {
					slide.removeAttribute( 'aria-hidden' );
				} else {
					slide.setAttribute( 'aria-hidden', 'true' );
				}
			} );

			dots.forEach( function ( dot, i ) {
				var isActive = i === current;
				dot.classList.toggle( 'is-active', isActive );
				dot.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
			} );

			if ( status ) {
				status.textContent = ( current + 1 ) + ' / ' + slides.length;
			}
		}

		if ( prevButton ) {
			prevButton.addEventListener( 'click', function () {
				show( current - 1 );
			} );
		}

		if ( nextButton ) {
			nextButton.addEventListener( 'click', function () {
				show( current + 1 );
			} );
		}

		dots.forEach( function ( dot, i ) {
			dot.addEventListener( 'click', function () {
				show( i );
			} );
		} );
	} );
} )();
