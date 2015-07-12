var Tracks,
	Track = require( './track' );

Tracks = Backbone.Collection.extend({
	model: Track,

	initialize: function( models, options ) {
		this.options = _.extend({
			id: 'huesos-player-tracks'
		}, options );
	},

	fetch: function() {
		var tracks;

		tracks = JSON.parse( localStorage.getItem( this.options.id ) );
		if ( null !== tracks ) {
			this.reset( tracks );
		}
	},

	save: function() {
		localStorage.setItem( this.options.id, JSON.stringify( this.toJSON() ) );
	}
});

module.exports = Tracks;
