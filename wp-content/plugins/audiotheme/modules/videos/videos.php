<?php
/**
 * Set up video-related functionality in the AudioTheme framework.
 *
 * @package AudioTheme_Framework
 * @subpackage Videos
 */

/**
 * Load the video template API.
 */
require( AUDIOTHEME_DIR . 'modules/videos/post-template.php' );

/**
 * Load the admin interface elements and functionality for videos.
 */
if ( is_admin() ) {
	require( AUDIOTHEME_DIR . 'modules/videos/admin/videos.php' );
}

/**
 * Register video post type and attach hooks to load related functionality.
 *
 * @since 1.0.0
 * @uses register_post_type()
 */
function audiotheme_videos_init() {
	register_taxonomy( 'audiotheme_video_category', 'audiotheme_video', array(
		'args'                           => array( 'orderby' => 'term_order' ),
		'hierarchical'                   => true,
		'labels'                         => array(
			'name'                       => _x( 'Categories', 'taxonomy general name', 'bandstand' ),
			'singular_name'              => _x( 'Category', 'taxonomy singular name', 'bandstand' ),
			'search_items'               => __( 'Search Categories', 'bandstand' ),
			'popular_items'              => __( 'Popular Categories', 'bandstand' ),
			'all_items'                  => __( 'All Categories', 'bandstand' ),
			'parent_item'                => __( 'Parent Category', 'bandstand' ),
			'parent_item_colon'          => __( 'Parent Category:', 'bandstand' ),
			'edit_item'                  => __( 'Edit Category', 'bandstand' ),
			'view_item'                  => __( 'View Category', 'bandstand' ),
			'update_item'                => __( 'Update Category', 'bandstand' ),
			'add_new_item'               => __( 'Add New Category', 'bandstand' ),
			'new_item_name'              => __( 'New Category Name', 'bandstand' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'bandstand' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'bandstand' ),
			'choose_from_most_used'      => __( 'Choose from most used categories', 'bandstand' ),
			'menu_name'                  => __( 'Categories', 'bandstand' ),
		),
		'meta_box_cb'                    => 'audiotheme_taxonomy_checkbox_list_meta_box',
		'public'                         => true,
		'query_var'                      => true,
		'rewrite'                        => array(
			'slug'                       => get_audiotheme_videos_rewrite_base() . '/category',
			'with_front'                 => false,
		),
		'show_ui'                        => true,
		'show_admin_column'              => true,
		'show_in_nav_menus'              => true,
		'show_tagcloud'                  => false,
	) );

	// Register the video custom post type.
	register_post_type( 'audiotheme_video', array(
		'has_archive'            => get_audiotheme_videos_rewrite_base(),
		'hierarchical'           => true,
		'labels'                 => array(
			'name'               => _x( 'Videos', 'post type general name', 'audiotheme' ),
			'singular_name'      => _x( 'Video', 'post type singular name', 'audiotheme' ),
			'add_new'            => _x( 'Add New', 'video', 'audiotheme' ),
			'add_new_item'       => __( 'Add New Video', 'audiotheme' ),
			'edit_item'          => __( 'Edit Video', 'audiotheme' ),
			'new_item'           => __( 'New Video', 'audiotheme' ),
			'view_item'          => __( 'View Video', 'audiotheme' ),
			'search_items'       => __( 'Search Videos', 'audiotheme' ),
			'not_found'          => __( 'No videos found', 'audiotheme' ),
			'not_found_in_trash' => __( 'No videos found in Trash', 'audiotheme' ),
			'all_items'          => __( 'All Videos', 'audiotheme' ),
			'menu_name'          => __( 'Videos', 'audiotheme' ),
			'name_admin_bar'     => _x( 'Video', 'add new on admin bar', 'audiotheme' ),
		),
		'menu_icon'              => audiotheme_encode_svg( 'admin/images/dashicons/videos.svg' ),
		'menu_position'          => 514,
		'public'                 => true,
		'publicly_queryable'     => true,
		'register_meta_box_cb'   => 'audiotheme_video_meta_boxes',
		'rewrite'                => array(
			'slug'       => get_audiotheme_videos_rewrite_base(),
			'with_front' => false
		),
		'show_ui'                => true,
		'show_in_menu'           => true,
		'show_in_nav_menus'      => false,
		'supports'               => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author' ),
	) );

	add_action( 'pre_get_posts', 'audiotheme_video_query_sort' );
	add_action( 'pre_get_posts', 'audiotheme_video_default_template_query' );

	add_action( 'template_include', 'audiotheme_video_template_include' );
	add_action( 'delete_attachment', 'audiotheme_video_delete_attachment' );
	add_filter( 'post_class', 'audiotheme_video_archive_post_class' );
}

/**
 * Get the videos rewrite base. Defaults to 'videos'.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_audiotheme_videos_rewrite_base() {
	$base = get_option( 'audiotheme_video_rewrite_base' );
	return ( empty( $base ) ) ? 'videos' : $base;
}

/**
 * Sort video archive requests.
 *
 * Defaults to sorting by publish date in descending order. A plugin can hook
 * into pre_get_posts at an earlier priority and manually set the order.
 *
 * @since 1.4.4
 *
 * @param object $query The main WP_Query object. Passed by reference.
 */
function audiotheme_video_query_sort( $query ) {
	if (
		is_admin() ||
		! $query->is_main_query() ||
		! ( is_post_type_archive( 'audiotheme_video' ) || is_tax( 'audiotheme_video_category' ) )
	) {
		return;
	}

	if ( ! $orderby = $query->get( 'orderby' ) ) {
		$orderby = get_audiotheme_archive_meta( 'orderby', true, 'post_date', 'audiotheme_video' );
		switch ( $orderby ) {
			// Use a plugin like Simple Page Ordering to change the menu order.
			case 'custom' :
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'asc' );
				break;

			case 'title' :
				$query->set( 'orderby', 'title' );
				$query->set( 'order', 'asc' );
				break;

			// Sort videos by publish date.
			default :
				$query->set( 'orderby', 'post_date' );
				$query->set( 'order', 'desc' );
		}
	}
}

/**
 * Set posts per page for video archives if the default templates are being
 * loaded.
 *
 * The default video archive template uses a 4-column grid. If it's loaded from
 * the plugin, set the posts per page arg to a multiple of 4.
 *
 * @since 1.3.0
 *
 * @param object $query The main WP_Query object. Passed by reference.
 */
function audiotheme_video_default_template_query( $query ) {
	global $wpdb;

	if (
		is_admin() ||
		! $query->is_main_query() ||
		! is_post_type_archive( 'audiotheme_video' ) ||
		! is_tax( 'audiotheme_video_category' )
	) {
		return;
	}

	// The default video archive template uses a 4-column grid.
	// If it's being loaded from the plugin, set the posts per page arg to a multiple of 4.
	if ( is_audiotheme_default_template( audiotheme_locate_template( 'archive-video.php' ) ) ) {
		if ( '' === $query->get( 'posts_per_archive_page' ) ) {
			$query->set( 'posts_per_archive_page', 12 );
		}
	}
}

/**
 * Load video templates.
 *
 * Templates should be included in an /audiotheme/ directory within the theme.
 *
 * @since 1.0.0
 *
 * @param string $template Template path.
 * @return string
 */
function audiotheme_video_template_include( $template ) {
	if ( is_post_type_archive( 'audiotheme_video' ) || is_tax( 'audiotheme_video_category' ) ) {
		if ( is_tax() ) {
			$term = get_queried_object();
			$taxonomy = str_replace( 'audiotheme_', '', $term->taxonomy );
			$templates[] = "taxonomy-$taxonomy-{$term->slug}.php";
			$templates[] = "taxonomy-$taxonomy.php";
		}

		$templates[] = 'archive-video.php';
		$template = audiotheme_locate_template( $templates );
		do_action( 'audiotheme_template_include', $template );
	} elseif ( is_singular( 'audiotheme_video' ) ) {
		$template = audiotheme_locate_template( 'single-video.php' );
		do_action( 'audiotheme_template_include', $template );
	}

	return $template;
}

/**
 * Delete oEmbed thumbnail post meta if the associated attachment is deleted.
 *
 * @since 1.0.0
 *
 * @param int $attachment_id The ID of the attachment being deleted.
 */
function audiotheme_video_delete_attachment( $attachment_id ) {
	global $wpdb;

	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_audiotheme_oembed_thumbnail_id' AND meta_value=%d", $attachment_id ) );
	if ( $post_id ) {
		delete_post_meta( $post_id, '_audiotheme_oembed_thumbnail_id' );
		delete_post_meta( $post_id, '_audiotheme_oembed_thumbnail_url' );
	}
}

/**
 * Add classes to video posts on the archive page.
 *
 * Classes serve as helpful hooks to aid in styling across various browsers.
 *
 * - Adds nth-child classes to video posts.
 *
 * @since 1.2.0
 *
 * @param array $classes Default post classes.
 * @return array
 */
function audiotheme_video_archive_post_class( $classes ) {
	global $wp_query;

	if ( $wp_query->is_main_query() && is_post_type_archive( 'audiotheme_video' ) ) {
		$nth_child_classes = audiotheme_nth_child_classes( array(
			'current' => $wp_query->current_post + 1,
			'max'     => get_audiotheme_archive_meta( 'columns', true, 4 ),
		) );

		$classes = array_merge( $classes, $nth_child_classes );
	}

	return $classes;
}
