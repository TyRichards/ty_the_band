var Track = Backbone.View.extend({
	className: 'track',
	tagName: 'li',
	template: wp.template( 'huesos-player-track' ),

	events: {
		'click': 'play',
		'click .js-remove': 'destroy'
	},

	initialize: function( options ) {
		this.player = options.player;
		this.listenTo( this.model, 'change:status', this.updateStatus );
		this.listenTo( this.model, 'destroy', this.remove );
		this.listenTo( this.player, 'change:track', this.updateCurrent );
		this.listenTo( this.player, 'change:track change:status', this.updateStatus );
	},

	render: function() {
		this.$el.html( this.template( this.model.toJSON() ) );
		this.updateCurrent();
		this.updateStatus();
		return this;
	},

	play: function( e ) {
		var $target = jQuery( e.target ),
			$forbidden = this.$el.find( 'a, .js-remove' ),
			index = this.player.tracks.indexOf( this.model );

		// Don't do anything if a link is clicked within the action element.
		if ( $target.is( $forbidden ) || !! $forbidden.find( $target ).length ) {
			return;
		}

		this.player.setCurrentTrack( index ).play();
	},

	destroy: function( e ) {
		e.preventDefault();
		//player.tracks.remove( this.model );
		this.model.trigger( 'destroy', this.model );
	},

	remove: function() {
		this.$el.remove();
	},

	updateCurrent: function() {
		this.$el.toggleClass( 'is-current', this.player.currentTrack.get( 'id' ) === this.model.get( 'id' ) );
	},

	updateStatus: function() {
		var isPlaying = 'playing' === this.player.get( 'status' );
		this.$el.toggleClass( 'is-playing', isPlaying && this.player.currentTrack === this.model );
		this.$el.toggleClass( 'is-error', 'error' === this.model.get( 'status' ) );
	}
});

module.exports = Track;
