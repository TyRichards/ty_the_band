<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Add custom classes to the array of body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function huesos_body_classes( $classes ) {
	if ( ! is_page_template( 'templates/page-no-sidebar.php' ) && ! huesos_has_sidebar() ) {
		$classes[] = 'layout-full';
	}

	if ( is_page_template( 'templates/page-no-sidebar.php' ) ) {
		$classes[] = 'layout-no-sidebar';
	}

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_theme_mod( 'huesos_background_image', '' ) ) {
		$classes[] = 'has-background-image';
	} else {
		$classes[] = 'no-background-image';
	}

	$classes[] = get_theme_mod( 'huesos_player_scheme', 'dark-player-scheme' );
	$classes[] = get_theme_mod( 'huesos_text_scheme', 'dark-text-scheme' );

	return array_unique( $classes );
}
add_filter( 'body_class', 'huesos_body_classes' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a Continue reading link.
 *
 * @since 1.0.0
 */
function huesos_excerpt_more( $more ) {
	$link_text = sprintf(
		/* translators: %s: Name of current post */
		esc_html__( 'Continue reading %s', 'huesos' ),
		'<span class="screen-reader-text">' . get_the_title() . '</span>'
	);

	$link = sprintf(
		'<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink() ),
		$link_text
	);

	return '&hellip; ' . $link;
}
add_filter( 'excerpt_more', 'huesos_excerpt_more' );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since 1.0.0
 *
 * @param string $html Search form HTML
 * @return string Modified search form HTML
 */
function huesos_search_form_modify( $html ) {
	return str_replace( 'class="search-submit', 'class="search-submit screen-reader-text', $html );
}
add_filter( 'get_search_form', 'huesos_search_form_modify' );

/**
 * Remove brackets around link counts in widgets.
 *
 * @since 1.0.0
 *
 * @param string $html HTML
 * @return string
 */
function huesos_widget_link_count( $html ) {
	if ( false === strpos( $html, '<option' ) ) {
		$html = preg_replace( '/(&nbsp;)?\((\d+)\)/', '<span class="sep">/</span>$2', $html );
	}
	return $html;
}
add_filter( 'get_archives_link', 'huesos_widget_link_count' );
add_filter( 'wp_list_categories', 'huesos_widget_link_count' );

/**
 * Filter the sidebar status on no sidebar page templates.
 *
 * @since 1.0.0
 *
 * @param bool $is_active_sidebar Whether the sidebar is active.
 * @param string $index The sidebar id.
 * @return bool
 */
function huesos_sidebar_status( $is_active_sidebar, $index ) {
	if ( 'sidebar-1' !== $index || ! $is_active_sidebar ) {
		return $is_active_sidebar;
	}

	if ( is_page_template( 'templates/page-no-sidebar.php' ) ) {
		$is_active_sidebar = false;
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'huesos_sidebar_status', 10, 2 );
