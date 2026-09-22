/**
 * Cycle of Excellence stepper: toggles which phase is visually "active".
 * Every phase's full label + description is always in the markup (see
 * cycle-of-excellence.php) — this only changes which one is emphasized, so
 * nothing is hidden from no-JS or screen-reader users.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-fs-cycle]' ).forEach( function ( group ) {
		var steps = Array.prototype.slice.call( group.querySelectorAll( '[data-fs-cycle-step]' ) );

		if ( steps.length < 2 ) {
			return;
		}

		steps.forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function () {
				steps.forEach( function ( otherTrigger ) {
					var step = otherTrigger.closest( '.fs-cycle__step' );
					var isActive = otherTrigger === trigger;

					if ( step ) {
						step.classList.toggle( 'is-active', isActive );
					}

					if ( isActive ) {
						otherTrigger.setAttribute( 'aria-current', 'step' );
					} else {
						otherTrigger.removeAttribute( 'aria-current' );
					}
				} );
			} );
		} );
	} );
} )();
