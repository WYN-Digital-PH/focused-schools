/**
 * Partner map.
 *
 * Reads the state data the component printed, drops one pin per state, and
 * opens a popup listing that state's current and previous districts. A
 * ?state= parameter on the URL opens that state on load, so a filtered view
 * is shareable.
 *
 * Everything here is progressive enhancement. The district list underneath is
 * the real content and is always rendered server-side; if Leaflet fails to
 * load, the tile service is unreachable, or JavaScript is off, the map
 * container stays empty and nothing is lost.
 */
( function () {
	'use strict';

	var canvas = document.querySelector( '[data-fs-partner-map]' );
	var payload = document.querySelector( '[data-fs-partner-map-data]' );

	if ( ! canvas || ! payload || 'undefined' === typeof window.L ) {
		return;
	}

	var states;

	try {
		states = JSON.parse( payload.textContent );
	} catch ( error ) {
		return;
	}

	if ( ! states || ! states.length ) {
		return;
	}

	// Must match the legend in partner-map.css, or the key lies about the pins.
	var CURRENT = '#dd6237';
	var PREVIOUS = '#005c6d';

	// An initial view up front, so invalidateSize() below is always safe to
	// call; fitBounds replaces it as soon as the pins are on.
	var map = window.L.map( canvas, {
		center: [ 39.8, -98.5 ],
		zoom: 4,
		scrollWheelZoom: false,
		zoomControl: true,
		attributionControl: true
	} );

	window.L.tileLayer(
		'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}',
		{
			minZoom: 2,
			maxZoom: 12,
			attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ'
		}
	).addTo( map );

	function escapeHtml( value ) {
		return String( value ).replace( /[&<>"']/g, function ( character ) {
			return {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#39;'
			}[ character ];
		} );
	}

	function listMarkup( label, districts ) {
		if ( ! districts || ! districts.length ) {
			return '';
		}

		return '<p class="fs-partner-map__popup-label">' + escapeHtml( label ) + '</p><ul>' +
			districts.map( function ( district ) {
				return '<li>' + escapeHtml( district ) + '</li>';
			} ).join( '' ) + '</ul>';
	}

	var bounds = [];
	var active = canvas.getAttribute( 'data-fs-active' );
	var openMarker = null;

	states.forEach( function ( state ) {
		var hasCurrent = state.current && state.current.length;
		var position = [ parseFloat( state.lat ), parseFloat( state.lng ) ];

		var marker = window.L.circleMarker( position, {
			radius: 9,
			weight: 2,
			color: '#ffffff',
			fillColor: hasCurrent ? CURRENT : PREVIOUS,
			fillOpacity: 1
		} ).addTo( map );

		var total = ( state.current ? state.current.length : 0 ) + ( state.previous ? state.previous.length : 0 );

		marker.bindPopup(
			'<div class="fs-partner-map__popup">' +
				'<p class="fs-partner-map__popup-state">' + escapeHtml( state.name ) + '</p>' +
				'<p class="fs-partner-map__popup-count">' + total + ( 1 === total ? ' district' : ' districts' ) + '</p>' +
				listMarkup( 'Current', state.current ) +
				listMarkup( 'Previous', state.previous ) +
			'</div>'
		);

		bounds.push( position );

		if ( active && active === state.name ) {
			openMarker = marker;
		}
	} );

	/*
	 * The container can still measure zero on first paint — reveal animations,
	 * web fonts, a section not yet laid out — and Leaflet would then clamp the
	 * fit to maxZoom and show the middle of the ocean. Size it now, then fit
	 * again on the first non-zero measurement.
	 */
	function fit() {
		map.invalidateSize( { animate: false } );

		if ( bounds.length ) {
			map.fitBounds( bounds, { padding: [ 52, 52 ], maxZoom: 6, animate: false } );
		}
	}

	fit();

	if ( window.ResizeObserver ) {
		var observer = new window.ResizeObserver( function () {
			if ( ! canvas.clientWidth || ! canvas.clientHeight ) {
				return;
			}

			fit();
			observer.disconnect();
		} );

		observer.observe( canvas );
	} else {
		window.setTimeout( fit, 240 );
	}

	if ( openMarker ) {
		openMarker.openPopup();
	}

	// Dragging a map inside a scrolling page is a trap on touch; require a
	// deliberate interaction before the map takes the gesture.
	map.once( 'focus', function () {
		map.scrollWheelZoom.enable();
	} );
} )();
