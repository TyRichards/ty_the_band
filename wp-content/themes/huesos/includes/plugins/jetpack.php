<?php
/**
 * Jetpack Compatibility File
 *
 * @package Huesos
 * @since 1.0.0
 * @link http://jetpack.me/
 */

/**
 * Set up Jetpack theme support.
 *
 * Adds support for Infinite Scroll.
 *
 * @since 1.0.0
 */
function huesos_jetpack_setup() {
	// Add support for Infinite Scroll
	add_theme_support( 'infinite-scroll', apply_filters( 'huesos_infinite_scroll_args', array(
		'container' => 'primary',
		'render'    => 'huesos_jetpack_infinite_scroll_render',
		'wrapper'   => false,
	) ) );
}
add_action( 'after_setup_theme', 'huesos_jetpack_setup' );

if ( ! function_exists( 'huesos_jetpack_infinite_scroll_render' ) ) :
/**
 * Callback for the Infinite Scroll module in Jetpack to render additional posts.
 *
 * @since 1.0.0
 */
function huesos_jetpack_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'templates/parts/content', get_post_format() );
	}
}
endif;
