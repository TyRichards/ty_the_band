<?php
/**
 * Huesos backward compatability functionality.
 *
 * @package Huesos
 * @since 1.0.0
 */

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Display the archive title based on the queried object.
 *
 * @since 1.0.0
 *
 * @see get_the_archive_title()
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	$title = get_the_archive_title();

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'get_the_archive_title' ) ) :
/**
 * Retrieve the archive title based on the queried object.
 *
 * @since 1.0.0
 *
 * @return string Archive title.
 */
function get_the_archive_title() {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'huesos' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'huesos' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'huesos' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'huesos' ), get_the_date( _x( 'Y', 'yearly archives date format', 'huesos' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'huesos' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'huesos' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'huesos' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'huesos' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'huesos' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'huesos' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'huesos' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'huesos' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'huesos' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title Archive title to be displayed.
	 */
	return apply_filters( 'get_the_archive_title', $title );
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Display category, tag, or term description.
 *
 * @since 1.0.0
 *
 * @see get_the_archive_description()
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = get_the_archive_description();

	if ( ! empty( $description ) ) {
		echo $before . $description . $after;
	}
}
endif;

if ( ! function_exists( 'get_the_archive_description' ) ) :
/**
 * Retrieve category, tag, or term description.
 *
 * @since 1.0.0
 *
 * @return string Archive description.
 */
function get_the_archive_description() {

	/**
	 * Filter the archive description.
	 *
	 * @since 1.0.0
	 *
	 * @see term_description()
	 *
	 * @param string $description Archive description to be displayed.
	 */
	return apply_filters( 'get_the_archive_description', term_description() );
}
endif;

if ( ! function_exists( 'get_the_post_navigation' ) ) :
/**
 * Return navigation to next/previous post when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Default post navigation arguments. Default empty array.
 *
 *     @type string $prev_text          Anchor text to display in the previous post link. Default `%title`.
 *     @type string $next_text          Anchor text to display in the next post link. Default `%title`.
 *     @type string $screen_reader_text Screen reader text for nav element. Default 'Post navigation'.
 * }
 * @return string Markup for post links.
 */
function get_the_post_navigation( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'prev_text'          => '%title',
		'next_text'          => '%title',
		'screen_reader_text' => __( 'Post navigation', 'huesos' ),
	) );

	$navigation = '';
	$previous   = get_previous_post_link( '<div class="nav-previous">%link</div>', $args['prev_text'] );
	$next       = get_next_post_link( '<div class="nav-next">%link</div>', $args['next_text'] );

	// Only add markup if there's somewhere to navigate to.
	if ( $previous || $next ) {
		$navigation = _navigation_markup( $previous . $next, 'post-navigation', $args['screen_reader_text'] );
	}

	return $navigation;
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args Optional. See {@see get_the_post_navigation()} for available
 *                    arguments. Default empty array.
 */
function the_post_navigation( $args = array() ) {
	echo get_the_post_navigation( $args );
}
endif;

if ( ! function_exists( 'get_the_posts_navigation' ) ) :
/**
 * Return navigation to next/previous set of posts when applicable.
 *
 * @since 1.0.0
 *
 * @global WP_Query $wp_query WordPress Query object.
 *
 * @param array $args {
 *     Optional. Default posts navigation arguments. Default empty array.
 *
 *     @type string $prev_text          Anchor text to display in the previous posts link.
 *                                      Default 'Older posts'.
 *     @type string $next_text          Anchor text to display in the next posts link.
 *                                      Default 'Newer posts'.
 *     @type string $screen_reader_text Screen reader text for nav element.
 *                                      Default 'Posts navigation'.
 * }
 * @return string Markup for posts links.
 */
function get_the_posts_navigation( $args = array() ) {
	$navigation = '';

	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
		$args = wp_parse_args( $args, array(
			'prev_text'          => __( 'Older posts', 'huesos' ),
			'next_text'          => __( 'Newer posts', 'huesos' ),
			'screen_reader_text' => __( 'Posts navigation', 'huesos' ),
		) );

		$next_link = get_previous_posts_link( $args['next_text'] );
		$prev_link = get_next_posts_link( $args['prev_text'] );

		if ( $prev_link ) {
			$navigation .= '<div class="nav-previous">' . $prev_link . '</div>';
		}

		if ( $next_link ) {
			$navigation .= '<div class="nav-next">' . $next_link . '</div>';
		}

		$navigation = _navigation_markup( $navigation, 'posts-navigation', $args['screen_reader_text'] );
	}

	return $navigation;
}
endif;

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args Optional. See {@see get_the_posts_navigation()} for available
 *                    arguments. Default empty array.
 */
function the_posts_navigation( $args = array() ) {
	echo get_the_posts_navigation( $args );
}
endif;

if ( ! function_exists( 'get_the_posts_pagination' ) ) :
/**
 * Return a paginated navigation to next/previous set of posts,
 * when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Default pagination arguments, {@see paginate_links()}.
 *
 *     @type string $screen_reader_text Screen reader text for navigation element.
 *                                      Default 'Posts navigation'.
 * }
 * @return string Markup for pagination links.
 */
function get_the_posts_pagination( $args = array() ) {
	$navigation = '';

	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
		$args = wp_parse_args( $args, array(
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'huesos' ),
			'next_text'          => __( 'Next', 'huesos' ),
			'screen_reader_text' => __( 'Posts navigation', 'huesos' ),
		) );

		// Make sure we get a string back. Plain is the next best thing.
		if ( isset( $args['type'] ) && 'array' === $args['type'] ) {
			$args['type'] = 'plain';
		}

		// Set up paginated links.
		$links = paginate_links( $args );

		if ( $links ) {
			$navigation = _navigation_markup( $links, 'pagination', $args['screen_reader_text'] );
		}
	}

	return $navigation;
}
endif;

if ( ! function_exists( 'the_posts_pagination' ) ) :
/**
 * Display a paginated navigation to next/previous set of posts,
 * when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args Optional. See {@see get_the_posts_pagination()} for available arguments.
 *                    Default empty array.
 */
function the_posts_pagination( $args = array() ) {
	echo get_the_posts_pagination( $args );
}
endif;

if ( ! function_exists( '_navigation_markup' ) ) :
/**
 * Wraps passed links in navigational markup.
 *
 * @since 1.0.0
 * @access private
 *
 * @param string $links              Navigational links.
 * @param string $class              Optional. Custom class for nav element. Default: 'posts-navigation'.
 * @param string $screen_reader_text Optional. Screen reader text for nav element. Default: 'Posts navigation'.
 * @return string Navigation template tag.
 */
function _navigation_markup( $links, $class = 'posts-navigation', $screen_reader_text = '' ) {
	if ( empty( $screen_reader_text ) ) {
		$screen_reader_text = __( 'Posts navigation', 'huesos' );
	}

	$template = '
	<nav class="navigation %1$s" role="navigation">
		<h2 class="screen-reader-text">%2$s</h2>
		<div class="nav-links">%3$s</div>
	</nav>';

	return sprintf( $template, sanitize_html_class( $class ), esc_html( $screen_reader_text ), $links );
}
endif;
