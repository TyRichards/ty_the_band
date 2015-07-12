var Player,
	l10n = huesos.l10n,
	Playlist = require( './playlist' ),
	TogglePlaylistButton = require( './toggle-playlist-button' ),
	VolumePanel = require( './volume-panel' );

Player = Backbone.View.extend({
	className: 'huesos-player fade-in',
	tagName: 'div',
	template: wp.template( 'huesos-player' ),

	events: {
		'click .next':         'nextTrack',
		'click .play-pause':   'togglePlayback',
		'click .previous':     'previousTrack',
		'click .progress-bar': 'seek',
		'click .volume-bar':   'changeVolume'
	},

	initialize: function( options ) {
		this.player = options.player;

		this.listenTo( this.player,        'change:status',      this.toggleStatus );
		this.listenTo( this.player,        'change:currentTime', this.updateCurrentTime );
		this.listenTo( this.player,        'change:track',       this.updateDetails );
		this.listenTo( this.player,        'change:duration',    this.updateDuration );
		this.listenTo( this.player.tracks, 'add remove reset',   this.updateTracksCount );
	},

	render: function() {
		// @todo Add an is-loading class until the first track has loaded.

		this.$el.html( this.template( this.player.currentTrack.toJSON() ) )
		        .appendTo( '#page' );

		this.$artist =           this.$el.find( '.artist' );
		this.$controls =         this.$el.find( '.controls' );
		this.$currentTime =      this.$el.find( '.current-time' );
		this.$duration =         this.$el.find( '.duration' );
		this.$playBar =          this.$el.find( '.play-bar' );
		this.$playPause =        this.$el.find( '.play-pause' );
		this.$progressBar =      this.$el.find( '.progress-bar' );
		this.$title =            this.$el.find( '.title' );

		// Don't show volume control on Android or iOS.
		if ( ! mejs.MediaFeatures.isAndroid && ! mejs.MediaFeatures.isiOS ) {
			this.$controls.append(
				new VolumePanel({
					parent: this,
					player: this.player
				}).render().el
			);
		}

		this.$controls.append(
			new TogglePlaylistButton({
				parent: this
			}).render().el
		);

		this.$el.append(
			new Playlist({
				parent: this,
				player: this.player
			}).render().el
		);

		this.toggleStatus();
		this.updateCurrentTime();
		this.updateDetails( this.player.currentTrack );
		this.updateDuration();
		this.updateTracksCount();

		return this;
	},

	changeVolume: function( e ) {
		var position = e.pageX - this.$volumeBar.offset().left,
			percent = position / this.$volumeBar.outerWidth();

		this.player.setVolume( Number( ( percent ).toFixed( 2 ) ) );
	},

	nextTrack: function( e ) {
		this.player.nextTrack();
		this.player.play();
	},

	previousTrack: function( e ) {
		this.player.previousTrack();
		this.player.play();
	},

	seek: function( e ) {
		var duration = this.player.get( 'duration' ),
			position = e.pageX - this.$progressBar.offset().left,
			percent = position / this.$progressBar.outerWidth();

		percent = percent < 0.05 ? 0 : percent;
		this.player.setCurrentTime( percent * duration );
	},

	togglePlayback: function() {
		if ( 'playing' === this.player.get( 'status' ) ) {
			this.player.pause();
		} else {
			this.player.play();
		}
	},

	toggleStatus: function() {
		var isPlaying = 'playing' === this.player.get( 'status' );
		this.$el.toggleClass( 'is-playing', isPlaying )
		this.$playPause.text( isPlaying ? l10n.pause : l10n.play )
		               .toggleClass( 'play', ! isPlaying )
		               .toggleClass( 'pause', isPlaying );
	},

	updateCurrentTime: function() {
		var currentTime = this.player.get( 'currentTime' ),
			currentTimeCode = mejs.Utility.secondsToTimeCode( currentTime, false );

		this.$currentTime.text( currentTimeCode );
		this.$playBar.width( this.player.getProgress() * 100 + '%' );
	},

	updateDetails: function() {
		this.$artist.text( this.player.currentTrack.get( 'artist' ) );
		this.$title.text( this.player.currentTrack.get( 'title' ) );
	},

	updateDuration: function() {
		var durationTimeCode = mejs.Utility.secondsToTimeCode( this.player.get( 'duration' ), false );
		this.$duration.text( durationTimeCode );
	},

	updateTracksCount: function() {
		this.$el.removeClass(function( index, classes ) {
			return ( classes.match( /\s?tracks-count-\d+/g ) || [] ).join( ' ' );
		}).addClass( 'tracks-count-' + this.player.tracks.length )
	}
});

module.exports = Player;
