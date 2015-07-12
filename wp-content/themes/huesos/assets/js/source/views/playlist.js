var Playlist,
	Track = require( './track' );

Playlist = Backbone.View.extend({
	className: 'playlist',
	tagName: 'div',

	initialize: function( options ) {
		this.parent = options.parent;
		this.player = options.player;
		this.$window = Backbone.$( window );
		this.$toolbar = Backbone.$( '#wpadminbar' );
		this.listenTo( this.player.tracks, 'add', this.addTrack );

		_.bindAll( this, 'updateMaxHeight' );
		this.$window.on( 'load resize orientationchange scroll', _.throttle( this.updateMaxHeight, 250 ));
	},

	render: function() {
		this.$el.empty().append( '<ol class="tracks-list"></ol>' );
		this.$tracksList = this.$el.find( '.tracks-list' );
		this.player.tracks.each( this.addTrack, this );
		return this;
	},

	addTrack: function( track ) {
		this.$tracksList.append(
			new Track({
				model: track,
				player: this.player
			}).render().el
		);
	},

	updateMaxHeight: function() {
		var windowHeight,
			viewportWidth = window.innerWidth || this.$window.width();

		if ( viewportWidth >= 1024 ) {
			windowHeight = window.innerHeight || this.$window.height();
			this.$el.css( 'maxHeight', windowHeight - this.$toolbar.height() - this.parent.$el.height() + 'px' );
		} else {
			this.$el.css( 'maxHeight', 'none' );
		}
	}
});

module.exports = Playlist;
