/*global _:false, wp:false */

(function( $, _, wp, undefined ) {
	'use strict';

	var api = wp.customize;

	// http://24ways.org/2010/calculating-color-contrast/
	function getContrastScheme( hexcolor ) {
		var r, g, b, yiq;

		hexcolor = 0 === hexcolor.indexOf( '#' ) ? hexcolor.substr( 1 ) : hexcolor;
		r = parseInt( hexcolor.substr( 0, 2 ), 16 );
		g = parseInt( hexcolor.substr( 2, 2 ), 16 );
		b = parseInt( hexcolor.substr( 4, 2 ), 16 );
		yiq = ( ( r * 299 ) + ( g * 587 ) + ( b * 114 ) ) / 1000;

		return ( 128 <= yiq ) ? 'dark' : 'light';
	}

	function setSchemes() {
		var backgroundColor = api( 'huesos_background_color' )(),
			backgroundImage = api( 'huesos_background_image' )(),
			contrastScheme = getContrastScheme( backgroundColor ),
			textScheme = 'light-text-scheme';

		if ( '' === backgroundImage ) {
			textScheme = contrastScheme + '-text-scheme';
		}

		api( 'huesos_player_scheme' ).set( contrastScheme + '-player-scheme' );
		api( 'huesos_text_scheme' ).set( textScheme );
	}

	_.each( [ 'huesos_background_color', 'huesos_background_image' ], function( settingKey ) {
		api( settingKey, function( setting ) {
			setting.bind( setSchemes );
		});
	});

	$( document ).ready(function() {
		api.control( 'huesos_background_color' )
			.container
			.find( '.color-picker-hex' ).iris( 'option', 'palettes', [
				'#ffffff',
				'#79d1ff',
				'#85cc66',
				'#f0d177',
				'#bf3228',
				'#981281',
				'#2c519f',
				'#303642'
			]);
	});

})( jQuery, _, wp );
