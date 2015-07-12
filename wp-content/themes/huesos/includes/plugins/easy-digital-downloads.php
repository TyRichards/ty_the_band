<?php
/**
 * Easy Digital Downloads Compatibility File
 *
 * @package Huesos
 * @since 1.0.0
 * @link https://easydigitaldownloads.com/
 */

if ( ! defined( 'EDD_DISABLE_ARCHIVE' ) ) {
	/**
	 * Disable download post type archive
	 *
	 * @since 1.0.0
	 * @link https://easydigitaldownloads.com/docs/edd_disable_archive/
	 */
	define( 'EDD_DISABLE_ARCHIVE', true );
}

/**
 * Enqueue EDD assets.
 *
 * @since 1.0.0
 */
function huesos_edd_enqueue_assets() {
	wp_dequeue_style( 'edd-styles' );
	wp_enqueue_style( 'huesos-edd', get_template_directory_uri() . '/assets/css/easy-digital-downloads.css' );
	wp_style_add_data( 'huesos-edd', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'huesos_edd_enqueue_assets', 20 );


/*
 * EDD hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Disable EDD Styles Settings Tab. The theme uses its own styles.
 *
 * @since 1.0.0
 *
 * @param  array $tabs
 * @return array
 */
function huesos_edd_settings_styles( $tabs ) {
	if ( array_key_exists( 'styles', $tabs ) ) {
		unset( $tabs['styles'] );
	}

	return $tabs;
}
add_filter( 'edd_settings_tabs', 'huesos_edd_settings_styles', 20 );

/**
 * Add post classes to EDD downloads output via the [downloads] shortcode.
 *
 * @return [type] [description]
 */
function huesos_edd_download_class() {
	return implode( ' ', get_post_class() );
}
add_filter( 'edd_download_class', 'huesos_edd_download_class' );


/*
 * Theme hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Show cart link and quantity count in header if the cart has contents.
 *
 * The ".edd-cart-quantity" class is needed in order for the quantity to be
 * updated dynamically via AJAX.
 *
 * @todo  Add cart link to mobile navigation bar.
 *
 * @since 1.0.0
 */
function huesos_edd_show_header_cart_link() {
	if ( ! edd_get_cart_contents() ) {
		return;
	}

	printf(
		'<p class="cart-quantity"><a class="button" href="%1$s">%2$s (<span class="edd-cart-quantity">%3$s</span>)</a></p>',
		esc_url( edd_get_checkout_uri() ),
		esc_html__( 'Cart', 'huesos' ),
		esc_html( edd_get_cart_quantity() )
	);
}
add_filter( 'huesos_header_bottom', 'huesos_edd_show_header_cart_link' );
