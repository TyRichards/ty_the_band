var Player,
	l10n = huesos.l10n,
	mejsCreateErrorMessage = mejs.HtmlMediaElementShim.createErrorMessage,
	settings = huesos.player.settings,
	Tracks = require( './tracks' );

Player = Backbone.Model.extend({
	defaults: {
		currentTime: 0,
		currentTrackIndex: 0,
		duration: 0,
		loop: true,
		repeat: false,
		shuffle: false,
		status: '',
		volume: 0.8
	},

	initialize: function( attributes, options ) {
		var self = this;

		this.options = _.extend({
			id: 'huesos-player',
			persist: false,
			tracks: null
		}, options );

		this.currentTimeIntervalId = null;
		this.currentTrack = null;
		this.initialCurrentTime = 0;
		this.media = null;
		this.mediaCanPlay = false;
		this.playIntervalId = null;

		this.tracks = this.options.tracks || new Tracks;
		delete this.options.tracks;

		// Remember the player state between requests.
		if ( this.options.persist ) {
			this.fetchTracks();
			this.on( 'change', this.save, this );
			this.tracks.on( 'add remove reset', this.tracks.save, this.tracks );
		}

		_.bindAll(
			this,
			'bindAudio',
			'onMediaCanPlay',
			'onMediaEnded',
			'onMediaError',
			'onMediaLoadedMetadata',
			'onMediaPause',
			'onMediaPlaying',
			'onMediaTimeUpdate',
			'onMediaVolumeChange',
			'onUserGesture'
		);

		if ( mejs.MediaFeatures.isAndroid || mejs.MediaFeatures.isiOS ) {
			Backbone.$( window ).on( 'keydown.huesosPlayer mousedown.huesosPlayer pointerdown.huesosPlayer touchstart.huesosPlayer', this.onUserGesture );
		}

		// Proxy the createErrorMessage method to prevent issues since the media element isn't in the DOM.
		mejs.HtmlMediaElementShim.createErrorMessage = function( playback, options, poster ) {
			if ( 'huesos' in options ) {
				self.set( 'status', 'error' );
				self.currentTrack && self.currentTrack.set( 'status', 'error' );
				//self.nextTrack();
				return;
			}

			mejsCreateErrorMessage.apply( this, arguments );
		}
	},

	// http://blog.foolip.org/2014/02/10/media-playback-restrictions-in-blink/
	onUserGesture: function() {
		Backbone.$( window ).off( 'keydown.huesosPlayer mousedown.huesosPlayer pointerdown.huesosPlayer touchstart.huesosPlayer', this.onUserGesture );

		if ( ! this.media || !! 'load' in this.media ) {
			return;
		}

		this.media.load();
		this.setCurrentTime( this.initialCurrentTime );
	},

	getProgress: function() {
		var currentTime = this.get( 'currentTime' ),
			duration = this.get( 'duration' );

		return currentTime / duration;
	},

	loadTrack: function( track ) {
		if ( 'undefined' === typeof this.mediaNode ) {
			this.mediaNode = document.createElement( 'audio' );
		}

		// @todo Should this ever be set to 'loaded'?
		if ( this.currentTrack ) {
			this.currentTrack.set( 'status', 'error' === this.currentTrack.get( 'status') ? 'error' : 'paused' );
		}

		// Reload the audio object if the type has changed.
		if ( null !== this.media && track.get( 'type' ) !== this.currentTrack.get( 'type' ) ) {
			if ( this.media && !! 'pause' in this.media ) {
				this.media.pause();
				this.media.remove();
			}

			this.media = null;
		}

		// Update the current track.
		this.set( 'status', 'loading' );
		this.currentTrack = track;
		this.currentTrack.set( 'status', 'loading' );

		// Instantiate MediaElement.js.
		if ( null === this.media ) {
			this.mediaCanPlay = false;

			this.mediaNode.setAttribute( 'preload', 'auto' );
			this.mediaNode.setAttribute( 'src', track.get( 'src' ) );
			this.mediaNode.setAttribute( 'type', track.get( 'type' ) );

			try {
				this.media = new MediaElement( this.mediaNode, {
					huesos: true,
					pluginPath: settings.mejs.pluginPath || mejs.Utility.getScriptPath([ 'mediaelement.js','mediaelement.min.js','mediaelement-and-player.js','mediaelement-and-player.min.js' ]),
					startVolume: this.get( 'volume' ),
					success: this.bindAudio
				});
			} catch ( ex ) {
				this.media = null;
			}
		}

		// Load a new source using the existing audio object.
		else {
			this.media.pause();
			this.media.setSrc( track.get( 'src' ) );
			this.media.load();
			//this.media.setCurrentTime( 0 );
		}

		this.trigger( 'change:track', track );
	},

	nextTrack: function() {
		var nextIndex,
			currentIndex = this.get( 'currentTrackIndex' );

		if ( this.get( 'shuffle' ) ) {
			nextIndex = Math.floor( Math.random() * this.tracks.length );
		} else {
			nextIndex = currentIndex + 1 >= this.tracks.length ? 0 : currentIndex + 1;
		}

		this.setCurrentTrack( nextIndex );
	},

	pause: function() {
		clearInterval( this.playIntervalId );
		this.media.pause();
	},

	play: function() {
		var player = this;

		// Hack to work around issues with playing before the media is ready.
		clearInterval( this.playIntervalId );

		this.playIntervalId = setInterval(function() {
			if ( player.mediaCanPlay && null === player.currentTimeIntervalId ) {
				'play' in player.media && player.media.play();
				clearInterval( player.playIntervalId );
			}
		}, 50 );
	},

	previousTrack: function() {
		var currentIndex = this.get( 'currentTrackIndex' ),
			previousIndex = currentIndex - 1 < 0 ? this.tracks.length - 1 : currentIndex - 1;

		this.setCurrentTrack( previousIndex );
	},

	// @todo Set up some sort of queue instead?
	// @todo May need to account for buffered time on iOS
	// @todo https://github.com/audiotheme/jquery-cue/blob/19909bfe7eb70c382593347a9277aa8c685ba77f/src/feature-history.js#L82
	setCurrentTime: function( time ) {
		var player = this;

		clearInterval( this.currentTimeIntervalId );

		this.currentTimeIntervalId = setInterval(function() {
			if ( 4 === player.media.readyState ) { //  && ( ! mejs.MediaFeatures.isWebkit || time < player.media.buffered.end( 0 ) )
				player.media.setCurrentTime( time );
				clearInterval( player.currentTimeIntervalId );
				player.currentTimeIntervalId = null;
			}
		}, 50 );
	},

	setCurrentTrack: function( index ) {
		while ( index >= this.tracks.length ) {
			index--;
		}

		if ( index !== this.get( 'currentTrackIndex' ) || null === this.currentTrack ) {
			this.set( 'currentTrackIndex', index );
			this.loadTrack( this.tracks.at( index ) );
		}

		return this;
	},

	setVolume: function( volume ) {
		this.media.volume = volume;
	},

	stop: function() {
		this.media.pause();
	},

	bindAudio: function( audio ) {
		// @todo Do these get blown away when removing an audio node?
		audio.addEventListener( 'canplay',        this.onMediaCanPlay );
		audio.addEventListener( 'loadedmetadata', this.onMediaCanPlay );
		audio.addEventListener( 'ended',          this.onMediaEnded );
		audio.addEventListener( 'loadedmetadata', this.onMediaLoadedMetadata );
		audio.addEventListener( 'error',          this.onMediaError );
		// abort, emptied, stalled, suspend
		audio.addEventListener( 'pause',          this.onMediaPause );
		audio.addEventListener( 'playing',        this.onMediaPlaying );
		audio.addEventListener( 'loadedmetadata', this.onMediaTimeUpdate );
		audio.addEventListener( 'timeupdate',     this.onMediaTimeUpdate );
		audio.addEventListener( 'volumechange',   this.onMediaVolumeChange );
	},

	onMediaCanPlay: function() {
		this.mediaCanPlay = true;
	},

	onMediaTimeUpdate: function() {
		this.set( 'currentTime', this.media.currentTime );
	},

	onMediaEnded: function() {
		if ( this.get( 'repeat' ) ) {
			this.play();
		}

		else if ( this.get( 'currentTrackIndex' ) < this.tracks.length || this.get( 'loop' ) || this.get( 'shuffle' ) )  {
			this.nextTrack();
			this.play();
		}
	},

	onMediaError: function( e ) {
		this.set( 'status', 'error' );
		this.currentTrack && this.currentTrack.set( 'status', 'error' );
		//clearInterval( this.playIntervalId );
		//this.nextTrack();
	},

	onMediaLoadedMetadata: function() {
		this.set( 'duration', this.media.duration );
	},

	onMediaPause: function() {
		this.set( 'status', 'paused' );
		this.currentTrack.set( 'status', 'paused' );
	},

	onMediaPlaying: function() {
		this.set( 'status', 'playing' );
		this.currentTrack.set( 'status', 'playing' );
	},

	onMediaVolumeChange: function() {
		this.set( 'volume', this.media.volume );
	},

	fetch: function() {
		var attributes, tracks;

		if ( ! this.options.persist ) {
			this.setCurrentTrack( this.get( 'currentTrackIndex' ) );
			return;
		}

		attributes = JSON.parse( localStorage.getItem( this.options.id ) );

		if ( null === attributes ) {
			this.setCurrentTrack( this.get( 'currentTrackIndex' ) );
			return;
		}

		this.setCurrentTrack ( attributes.currentTrackIndex );
		this.setCurrentTime( attributes.currentTime );
		this.initialCurrentTime = attributes.currentTime;

		// Don't auto play on mobile devices.
		if ( 'playing' === attributes.status && ! mejs.MediaFeatures.isAndroid && ! mejs.MediaFeatures.isiOS ) {
			this.play();
		}
	},

	fetchTracks: function() {
		var cachedSignature = localStorage.getItem( this.options.id + '-signature' );

		// Don't fetch tracks from localStorage if the signature has changed.
		if ( 'signature' in this.options && cachedSignature !== this.options.signature ) {
			localStorage.removeItem( this.options.id );
			localStorage.removeItem( this.options.id + '-signature' );
			localStorage.removeItem( this.options.id + '-tracks' );
			return;
		}

		this.tracks.fetch();
	},

	save: function() {
		if ( ! this.options.persist ) {
			return;
		}

		localStorage.setItem( this.options.id, JSON.stringify( this.toJSON() ) );

		if ( 'signature' in this.options ) {
			localStorage.setItem( this.options.id + '-signature', this.options.signature );
		}
	}
});

module.exports = Player;
