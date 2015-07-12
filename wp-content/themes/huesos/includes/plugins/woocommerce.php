<?php
/**
 * WooCommerce Compatibility File
 *
 * @package Huesos
 * @since 1.0.0
 * @link http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 */

/**
 * Set up WooCommerce theme support.
 *
 * @since 1.0.0
 */
function huesos_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'huesos_woocommerce_setup', 11 );

/**
 * Enqueue WooCommerce assets.
 *
 * @since 1.0.0
 */
function huesos_woocommerce_enqueue_assets() {
	wp_enqueue_style( 'huesos-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css' );
}
add_action( 'wp_enqueue_scripts', 'huesos_woocommerce_enqueue_assets' );


/*
 * WooCommerce hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Remove the default WooCommerce content wrappers.
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

/**
 * Print the default theme content open tag.
 *
 * Wraps WooCommerce content with the same elements used throughout the theme.
 *
 * @since 1.0.0
 */
function huesos_woocommerce_before_main_content() {
	echo '<main id="primary" class="content-area" role="main" itemprop="mainContentOfPage">';
}
add_action( 'woocommerce_before_main_content', 'huesos_woocommerce_before_main_content' );

/**
 * Print the default theme content wrapper close tag.
 *
 * @since 1.0.0
 */
function huesos_woocommerce_after_main_content() {
	echo '</main>';
}
add_action( 'woocommerce_after_main_content', 'huesos_woocommerce_after_main_content' );
