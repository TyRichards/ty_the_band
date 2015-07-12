/*global _:false, _huesosSettings:false, mejs:true */

window.huesos = window.huesos || {};

(function( window, $, undefined ) {
	'use strict';

	var $window = $( window ),
		$body = $( 'body' ),
		huesos = window.huesos,
		l10n = _huesosSettings.l10n;

	$.extend( huesos, {
		config: {
			videoPreview: {
				params: {
					autoplay: 1,
					rel: 0
				}
			}
		},

		/**
		 * Initialize the theme.
		 */
		init: function() {
			var $player = $( '.huesos-player' );

			$body.addClass( 'ontouchstart' in window || 'onmsgesturechange' in window ? 'touch' : 'no-touch' );

			// Open external links in a new window.
			$( '.js-maybe-external' ).each(function() {
				if ( this.hostname && this.hostname !== window.location.hostname ) {
					$( this ).attr( 'target', '_blank' );
				}
			});

			/**
			 * Makes "skip to content" link work correctly in IE9, Chrome, and Opera
			 * for better accessibility.
			 *
			 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
			 */
			if ( /webkit|opera|msie/i.test( navigator.userAgent ) && window.addEventListener ) {
				$window.on( 'hashchange', function() {
					skipToElement( location.hash.substring( 1 ) );
				});

				$( 'a.screen-reader-text' ).on ( 'click', function() {
					skipToElement( $( this ).attr( 'href' ).substring( 1 ) );
				});
			}

			$( '.site-header' ).find( '.player-toggle' ).on( 'click.huesos', function() {
				$body.toggleClass( 'player-is-open' );
				$player.trigger( 'resize' );
			} );

			// Insert play buttons in elements with a '.js-playable' class.
			$( '.js-playable' ).each(function() {
				var $this = $( this ),
					postId = $this.data( 'post-id' ),
					type = $this.data( 'type' );

				$this.after(
					$( '<button />', {
						'class': 'button-play js-play-' + type,
						'html': '<span class="screen-reader-text">' + l10n.play + '</span>'
					}).attr( 'data-' + type + '-id', postId )
				);
			});

			_.bindAll( this, 'onResize' );
			$window.on( 'load orientationchange resize', _.throttle( this.onResize, 100 ) );
		},

		/**
		 * Pause all MediaElement.js players.
		 */
		pausePlayers: function() {
			if ( 'undefined' !== typeof mejs ) {
				_.each( mejs.players, function( player, index ) {
					player.pause();
				});
			}

			if ( 'undefined' !== typeof huesos.players ) {
				_.each( huesos.players, function( player, index ) {
					player.pause();
				});
			}
		},

		/**
		 * Set up navigation.
		 */
		setupNavigation: function() {
			var $navigation = $( '.site-navigation' ),
				$secondary = $( '#secondary' ),
				$secondaryToggle = $( '.site-header' ).find( '.secondary-toggle' );

			// Add dropdown toggle that display child menu items.
			$navigation.find( '.menu-item-has-children > a' ).append( '<button class="dropdown-toggle" aria-expanded="false">' + l10n.expand + '</button>' );
			$navigation.find( '.current-menu-item, .current-menu-ancestor' ).find( '> .sub-menu, > a > .dropdown-toggle' ).addClass( 'is-open' );

			$( '.dropdown-toggle' ).on( 'click.huesos', function( e ) {
				var $this = $( this );
				e.preventDefault();
				$this.toggleClass( 'is-open' ).parent().next( '.sub-menu' ).toggleClass( 'is-open' );
				$this.attr( 'aria-expanded', 'false' === $this.attr( 'aria-expanded' ) ? 'true' : 'false' );
				$this.html( $this.html() === l10n.expand ? l10n.collapse : l10n.expand );
			} );

			$secondaryToggle.on( 'click.huesos', function() {
				$body.toggleClass( 'secondary-is-open' );
				$secondary.trigger( 'resize' );
			} );
		},

		/**
		 * Set up videos.
		 *
		 * - Makes videos responsive.
		 */
		setupVideos: function() {
			$( '.hentry, .responsive-video' ).fitVids();
		},

		/**
		 * Video Previews
		 */
		setupVideoPreview: function() {
			var $preview, featuredId, previewOffsetTop,
				$videos = $( '.archive-video .audiotheme_video' ),
				params = this.config.videoPreview.params;

			// Return early if there are not any videos on the archive page.
			if ( ! $videos.length ) {
				return;
			}

			// Add video preview container.
			if ( ! $( '#huesos-preview' ).length ) {
				$( '<div id="huesos-preview" class="huesos-preview fade-in" />' ).insertBefore( '.archive-video .block-grid' );
			}

			$preview = $( '#huesos-preview' );

			featuredId = $preview.data( 'featured-video-id' );
			previewOffsetTop = $preview.offset().top;

			if ( featuredId ) {
				$( '#block-grid-item-' + featuredId ).addClass( 'current' );
			}

			$( '.js-play-video' ).on( 'click', function( e ) {
				var $this = $( this ),
					videoId = $this.data( 'video-id' ),
					$video = $this.closest( '.audiotheme_video' );

				e.preventDefault();

				$.ajax({
					url: _huesosSettings.ajaxUrl,
					type: 'GET',
					data: {
						action: 'huesos_get_video_data',
						'post_id': videoId
					},
					dataType: 'json'
				}).done(function( response ) {
					var $html = $( response.videoHtml ),
						$iframe = $html.find( 'iframe' ),
						querySep;

					if ( $iframe.length ) {
						querySep = $iframe.attr( 'src' ).match( /\?.+/ ) ? '&' : '?';
						$html = $iframe.attr( 'src', $iframe.attr( 'src' ) + querySep + $.param( params ) );
					}

					huesos.pausePlayers();
					$videos.removeClass( 'current' );
					$video.addClass( 'current' );
					$preview.html( $html ).fitVids().addClass( 'has-preview' );

					// 50 == playbar height.
					$( 'html, body' ).animate({ scrollTop: previewOffsetTop - 50 }, 400 );
				}).fail(function() {

				});
			});
		},

		onResize: function() {
			if ( 1024 <= this.viewportWidth() ) {
				$body.removeClass( 'secondary-is-open player-is-open' );
			}

			if ( 1024 <= this.viewportWidth() && $body.hasClass( 'dark-player-scheme' ) ) {
				$body.removeClass( 'light-player-scheme' );
			} else if ( $body.hasClass( 'has-background-image' ) ) {
				$body.addClass( 'light-player-scheme' );
			}
		},

		viewportWidth: function() {
			return window.innerWidth || $window.width();
		}
	});

	function skipToElement( elementId ) {
		var element = document.getElementById( elementId );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();
		}
	}

	// Document ready.
	jQuery(function() {
		huesos.init();
		huesos.setupNavigation();
		huesos.setupVideos();
		huesos.setupVideoPreview();
	});

})( this, jQuery );
