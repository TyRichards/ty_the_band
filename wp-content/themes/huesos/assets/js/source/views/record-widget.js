var RecordWidget = Backbone.View.extend({
	events: {
		'click': 'click',
	},

	initialize: function( options ) {
		this.recordId = options.recordId || parseInt( this.$el.data( 'recordId' ), 10 );
		this.trackId = options.trackId || parseInt( this.$el.data( 'trackId' ), 10 );
		this.isTrack = this.trackId && ! _.isNaN( this.trackId );

		this.player = options.player;
		this.listenTo( this.player, 'change:track change:status', this.toggleState );
	},

	render: function() {
		this.toggleState();
		return this;
	},

	click: function( e ) {
		var model,
			$target = jQuery( e.target ),
			$forbidden = this.$el.find( 'a, .js-disable-playpause' );

		// Don't do anything if a link is clicked within the action element.
		if ( $target.is( $forbidden ) || !! $forbidden.find( $target ).length ) {
			return;
		}

		e.preventDefault();
		e.stopPropagation();

		if ( this.$el.hasClass( 'is-playing' ) ) {
			this.player.pause();
			return;
		}

		if ( this.isTrack ) {
			model = this.player.tracks.findWhere({ trackId: this.trackId });
		} else {
			model = this.player.tracks.findWhere({ recordId: this.recordId });
		}

		if ( model ) {
			this.player.setCurrentTrack( this.player.tracks.indexOf( model ) );
			this.player.play();
		} else {
			this.loadRecord();
		}
	},

	loadRecord: function() {
		var self = this,
			player = this.player;

		jQuery.ajax({
			url: _huesosSettings.ajaxUrl,
			type: 'GET',
			data: {
				action: 'huesos_get_record_data',
				record_id: this.recordId
			},
			dataType: 'json'
		}).done(function( response ) {
			var model;

			if ( ! response.success ) {
				return;
			}

			player.tracks.add( response.data.tracks );

			if ( self.isTrack ) {
				model = player.tracks.findWhere({ trackId: self.trackId });
			} else {
				model = player.tracks.get( response.data.tracks[0].id );
			}

			player.setCurrentTrack( player.tracks.indexOf( model ) );
			player.play();
		});
	},

	toggleState: function() {
		var isPlaying = 'playing' === this.player.get( 'status' ),
			currentRecordId = this.player.currentTrack.get( 'recordId' ),
			currentTrackId = this.player.currentTrack.get( 'trackId' );

		if ( this.isTrack ) {
			this.$el.toggleClass( 'is-playing', isPlaying && this.trackId === currentTrackId );
		} else {
			this.$el.toggleClass( 'is-playing', isPlaying && this.recordId === currentRecordId );
		}
	}
});

module.exports = RecordWidget;
