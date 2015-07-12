<?php
/**
 * Huesos functions and definitions.
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/**
 * Adjust the content width.
 *
 * @since 1.0.0
 */
function huesos_content_width() {
	global $content_width;

	if ( in_array( 'layout-full', get_body_class() ) ) {
		$content_width = 910;
	}
}
add_action( 'template_redirect', 'huesos_content_width' );

/**
 * Load helper functions and libraries.
 */
require( get_template_directory() . '/includes/class-huesos-player.php' );
require( get_template_directory() . '/includes/customizer.php' );
require( get_template_directory() . '/includes/hooks.php' );
require( get_template_directory() . '/includes/plugins.php' );
require( get_template_directory() . '/includes/template-helpers.php' );
require( get_template_directory() . '/includes/template-tags.php' );
include( get_template_directory() . '/includes/vendor/cedaro-theme/autoload.php' );
huesos_theme()->load();

/**
 * Set up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 */
function huesos_setup() {
	// Add support for translating strings in this theme.
	// @link http://codex.wordpress.org/Function_Reference/load_theme_textdomain
	load_theme_textdomain( 'huesos', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array(
		is_rtl() ? 'assets/css/editor-style-rtl.css' : 'assets/css/editor-style.css',
		huesos_fonts_url(),
	) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for the title tag.
	// @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	add_theme_support( 'title-tag' );

	// Add support for a logo.
	add_theme_support( 'site-logo', array(
		'size' => 'full',
	) );

	// Add support for post thumbnails.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 640, 640, false );

	// Add HTML5 markup for the comment forms, search forms and comment lists.
	add_theme_support( 'html5', array(
		'caption', 'comment-form', 'comment-list', 'gallery', 'search-form',
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array(
		'link', 'quote',
	) );

	// Register default nav menus.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'huesos' ),
		'social'  => __( 'Social Links Menu', 'huesos' ),
		'footer'  => __( 'Footer Menu', 'huesos' ),
	) );

	// Register front page templates.
	huesos_theme()->front_page->add_support()->add_templates( array(
		'templates/full-width.php',
		'templates/page-no-sidebar.php',
	) );
}
add_action( 'after_setup_theme', 'huesos_setup' );

/**
 * Register widget areas.
 *
 * @since 1.0.0
 */
function huesos_register_widget_areas() {
	register_sidebar( array(
		'id'            => 'sidebar-1',
		'name'          => __( 'Main Sidebar', 'huesos' ),
		'description'   => __( 'The right sidebar on posts and pages.', 'huesos' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'id'            => 'home-widgets',
		'name'          => __( 'Home', 'huesos' ),
		'description'   => __( 'Appears in the main content area on the homepage when using a static front page.', 'huesos' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	// register_sidebar( array(
	// 	'id'            => 'sidebar-footer',
	// 	'name'          => __( 'Footer', 'huesos' ),
	// 	'description'   => __( 'Footer sidebar', 'huesos' ),
	// 	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 	'after_widget'  => '</aside>',
	// 	'before_title'  => '<h1 class="widget-title">',
	// 	'after_title'   => '</h1>',
	// ) );	
}
add_action( 'widgets_init', 'huesos_register_widget_areas' );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 1.0.0
 */
function huesos_enqueue_assets() {
	$background_image = get_theme_mod( 'huesos_background_image' );
	$text_scheme      = get_theme_mod( 'huesos_text_scheme', 'dark-text-scheme' );

	// Add Themicons font, used in the main stylesheet.
	wp_enqueue_style( 'themicons', huesos_fonts_icon_url(), array(), '1.0.0' );

	// Load webfonts.
	wp_enqueue_style( 'huesos-fonts', huesos_fonts_url(), array(), null );

	// Load main style sheet.
	wp_enqueue_style( 'huesos-style', get_stylesheet_uri(), array( 'mediaelement' ) );
	wp_style_add_data( 'huesos-style', 'rtl', 'replace' );

	if ( 'light-text-scheme' === $text_scheme || ! empty( $background_image ) || is_customize_preview() ) {
		wp_enqueue_style( 'huesos-color-schemes', get_template_directory_uri() . '/assets/css/color-schemes.css', array( 'huesos-style' ) );
	}

	// Load theme scripts.
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/assets/js/vendor/jquery.fitvids.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'huesos-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'jquery-fitvids', 'underscore' ), '20141221', true );

	wp_localize_script( 'huesos-script', '_huesosSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'l10n'    => array(
			'expand'   => '<span class="screen-reader-text">' . __( 'Expand', 'huesos' ) . '</span>',
			'collapse' => '<span class="screen-reader-text">' . __( 'Collapse', 'huesos' ) . '</span>',
		),
	) );

	// Load script to support comment threading when it's enabled.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 'huesos-player', get_template_directory_uri() . '/assets/js/player.min.js', array( 'backbone', 'jquery', 'mediaelement', 'underscore', 'wp-util' ), '20150204', true );

	wp_localize_script( 'huesos-player', '_huesosPlayerSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'l10n'    => array(
			'mute'           => __( 'Mute', 'huesos' ),
			'pause'          => __( 'Pause', 'huesos' ),
			'play'           => __( 'Play', 'huesos' ),
			'togglePlaylist' => __( 'Toggle Playlist', 'huesos' ),
			'unmute'         => __( 'Unmute', 'huesos' ),
		),
		'mejs'    => array(
			'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'huesos_enqueue_assets' );

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since 1.1.0
 */
function huesos_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'huesos_javascript_detection', 0 );

/**
 * Add an HTML class to MediaElement.js container elements to aid styling.
 *
 * Extends the core _wpmejsSettings object to add a new feature via the
 * MediaElement.js plugin API.
 *
 * @since 1.0.0
 */
function huesos_mejs_add_container_class() {
	if ( ! wp_script_is( 'mediaelement', 'done' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var settings = window._wpmejsSettings || {};
		settings.features = settings.features || mejs.MepDefaults.features;
		settings.features.push( 'huesosclass' );

		MediaElementPlayer.prototype.buildhuesosclass = function( player ) {
			player.container.addClass( 'huesos-mejs-container' );
		};
	})();
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'huesos_mejs_add_container_class' );

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The default Google font usage is localized. For languages that use characters
 * not supported by the font, the font can be disabled.
 *
 * @since 1.0.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function huesos_fonts_url() {
	$fonts   = array();
	$subsets = 'latin';

	/* translators: If there are characters in your language that are not supported by Pathway Gothic One, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Pathway Gothic One: on or off', 'huesos' ) ) {
		$fonts[] = 'Pathway Gothic One';
	}

	/* translators: To add an additional character subset specific to your language, translate this to 'latin-ext'. Do not translate into your own language. */
	$subset = _x( 'no-subset', 'Add new subset (latin-ext)', 'huesos' );

	if ( 'latin-ext' === $subset ) {
		$subsets .= ',latin-ext';
	}

	return add_query_arg( array(
		'family' => rawurlencode( implode( '|', $fonts ) ),
		'subset' => rawurlencode( $subsets ),
	), '//fonts.googleapis.com/css' );
}

/**
 * Retrieve the Themeicons icon font stylesheet URL.
 *
 * @since 1.0.0
 *
 * @return string Font stylesheet.
 */
function huesos_fonts_icon_url() {
	return get_template_directory_uri() . '/assets/css/themicons.css';
}

/**
 * Retrieve the site-wide player instance.
 *
 * @since 1.0.0
 *
 * @return Huesos_Player
 */
function huesos_player() {
	static $instance;

	if ( null === $instance ) {
		$instance = new Huesos_Player();
	}

	return $instance;
}
add_action( 'init', array( huesos_player(), 'load' ) );

/**
 * Wrapper for accessing the Cedaro_Theme instance.
 *
 * @since 1.0.0
 *
 * @return Cedaro_Theme
 */
function huesos_theme() {
	static $instance;

	if ( null === $instance ) {
		Cedaro_Theme_Autoloader::register();
		$instance = new Cedaro_Theme( array( 'prefix' => 'huesos' ) );
	}

	return $instance;
}
