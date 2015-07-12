/*global _:false, wp:false */

/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 */

(function( $, wp ) {
	'use strict';

	var api = wp.customize,
		$body = $( 'body' ),
		$background = $( '.huesos-background-overlay' ),
		colorSettings = [ 'huesos_accent_color', 'huesos_background_color' ],
		stylesTemplate = wp.template( 'huesos-customizer-styles' ),
		$styles = $( '#huesos-custom-css' );

	if ( ! $styles.length ) {
		$styles = $( 'head' ).append( '<style type="text/css" id="huesos-custom-css"></style>' )
		                     .find( '#huesos-custom-css' );
	}

	// Site title.
	api( 'blogname', function( value ) {
		value.bind(function( to ) {
			$( '.site-title a' ).text( to );
		});
	});

	// Site description
	api( 'blogdescription', function( value ) {
		value.bind(function( to ) {
			$( '.site-description' ).text( to );
		});
	});

	// Text color scheme.
	api( 'huesos_text_scheme', function( value ) {
		value.bind(function( to ) {
			$body.removeClass( 'dark-text-scheme light-text-scheme' ).addClass( to );
		});
	});

	// Player scheme.
	api( 'huesos_player_scheme', function( value ) {
		value.bind(function( to ) {
			$body.removeClass( 'dark-player-scheme light-player-scheme' ).addClass( to );
		});
	});

	// Background image.
	api( 'huesos_background_image', function( value ) {
		value.bind(function( to ) {
			$body.toggleClass( 'has-background-image', '' !== to ).toggleClass( 'no-background-image', '' === to );
			$background.css( 'background-image', 'url(\'' + to + '\')' );
		});
	});

	// Update CSS when colors are changed.
	_.each( colorSettings, function( settingKey ) {
		api( settingKey, function( setting ) {
			setting.bind(function( value ) {
				updateCSS();
			});
		});
	});

	function updateCSS() {
		var css = stylesTemplate({
			accentColor: api( 'huesos_accent_color' )(),
			backgroundColor: api( 'huesos_background_color' )()
		});

		$styles.html( css );
	}

})( jQuery, wp );
