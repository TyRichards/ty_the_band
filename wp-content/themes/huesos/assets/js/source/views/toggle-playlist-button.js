var TogglePlaylistButton,
	l10n = huesos.l10n;

TogglePlaylistButton = Backbone.View.extend({
	className: 'toggle-playlist',
	tagName: 'button',

	events: {
		'click': 'togglePlaylist'
	},

	initialize: function( options ) {
		this.parent = options.parent;
	},

	render: function() {
		this.$el.text( l10n.togglePlaylist );
		return this;
	},

	togglePlaylist: function() {
		var isOpen = this.parent.$el.hasClass( 'is-playlist-open' );
		this.parent.$el.toggleClass( 'is-playlist-open', ! isOpen );
		this.$el.toggleClass( 'is-open', ! isOpen );
	}
});

module.exports = TogglePlaylistButton;
