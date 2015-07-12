<?php
/**
 * Functionality specific to self-hosted installations of WordPress, including
 * any plugin support.
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Huesos only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	include( get_template_directory() . '/includes/back-compat.php' );
}


/*
 * Plugin support.
 * -----------------------------------------------------------------------------
 */

/**
 * Load AudioTheme support or display a notice that it's needed.
 */
if ( function_exists( 'audiotheme_load' ) ) {
	include( get_template_directory() . '/includes/plugins/audiotheme.php' );
} else {
	include( get_template_directory() . '/includes/vendor/class-audiotheme-themenotice.php' );
	new Audiotheme_ThemeNotice();
}

/**
 * Load Cue support.
 */
if ( class_exists( 'Cue' ) ) {
	include( get_template_directory() . '/includes/plugins/cue.php' );
}

/**
 * Load Easy Digital Downloads support.
 */
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	include( get_template_directory() . '/includes/plugins/easy-digital-downloads.php' );
}

/**
 * Load Jetpack support.
 */
if ( class_exists( 'Jetpack' ) ) {
	include( get_template_directory() . '/includes/plugins/jetpack.php' );
}

/**
 * Load WooCommerce support.
 */
if ( class_exists( 'WooCommerce' ) ) {
	include( get_template_directory() . '/includes/plugins/woocommerce.php' );
}
