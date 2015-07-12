var VolumePanel,
	l10n = huesos.l10n,
	VolumeSlider = require( './volume-slider' );

VolumePanel = Backbone.View.extend({
	className: 'volume-panel',
	tagName: 'div',

	events: {
		'click .volume-toggle': 'toggleMute'
	},

	initialize: function( options ) {
		this.parent = options.parent;
		this.player = options.player;
		this.listenTo( this.player, 'change:volume', this.updateClasses );
	},

	render: function() {
		this.$el.append( '<button class="volume-toggle">' + l10n.mute + '</button>' );
		this.$toggleButton = this.$el.find( '.volume-toggle' );

		this.$el.append(
			new VolumeSlider({
				parent: this,
				player: this.player,
				playerView: this.parent.parent
			}).render().el
		);
		return this;
	},

	toggleMute: function() {
		var volume = this.player.get( 'volume' ) < 0.05 ? 0.8 : 0;
		this.player.setVolume( volume );
	},

	updateClasses: function() {
		var isMuted = this.player.get( 'volume' ) < 0.05;
		this.parent.$el.toggleClass( 'is-muted', isMuted );
		this.$toggleButton.toggleClass( 'is-muted', isMuted ).text( isMuted ? l10n.unmute : l10n.mute );
	}
});

module.exports = VolumePanel;
