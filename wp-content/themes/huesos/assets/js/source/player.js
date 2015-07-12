/*global _:false, _huesosPlayerSettings, Backbone:false, MediaElement:false, mejs:false, wp:false */
/*jshint browserify:true */

'use strict';

var $ = require( 'jquery' );

window.huesos = window.huesos || {};

_.extend( huesos, { player: {}, players: {}, model: {}, view: {} } );

huesos.player.settings = _huesosPlayerSettings || {};

huesos.l10n = _.extend({
	'mute': 'Mute',
	'pause': 'Pause',
	'play': 'Play',
	'togglePlaylist': 'Toggle Playlist',
	'unmute': 'Unmute'
}, huesos.player.settings.l10n );

delete huesos.player.settings.l10n;

huesos.model.Track = require( './models/track' );
huesos.model.Tracks = require( './models/tracks' );
huesos.model.Player = require( './models/player' );
huesos.view.Player = require( './views/player' );
huesos.view.Playlist = require( './views/playlist' );
huesos.view.RecordWidget = require( './views/record-widget' );
huesos.view.Track = require( './views/track' );
huesos.view.TogglePlaylistButton = require( './views/toggle-playlist-button' );
huesos.view.VolumePanel = require( './views/volume-panel' );
huesos.view.VolumeSlider = require( './views/volume-slider' );

$( document ).ready(function( $ ) {
	var player, playerView, tracks,
		data = JSON.parse( $( '#huesos-player-settings' ).html() );

	tracks = new huesos.model.Tracks( data.tracks );

	player = huesos.players.huesos = new huesos.model.Player({}, {
		persist: true,
		signature: data.signature,
		tracks: tracks
	});

	player.fetch();

	// Restore the original playlist if all tracks are removed.
	player.listenTo( player.tracks, 'remove', function() {
		if ( this.tracks.length < 1 ) {
			this.tracks.add( data.tracks );
		}
	});

	playerView = new huesos.view.Player({
		player: player,
	}).render();

	$( '.js-play-record' ).off( 'click' ).each(function() {
		new huesos.view.RecordWidget({
			el: this,
			player: player,
			recordId: $( this ).data( 'record-id' ),
			trackId: $( this ).data( 'track-id' )
		});
	});
});
