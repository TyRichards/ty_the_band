<?php
/**
 * AudioTheme Compatibility File
 *
 * @package Huesos
 * @since 1.0.0
 * @link https://audiotheme.com/
 */

/**
 * Set up theme defaults and register support for various AudioTheme features.
 *
 * @since 1.0.0
 */
function huesos_audiotheme_setup() {
	// Add AudioTheme automatic updates support
	add_theme_support( 'audiotheme-automatic-updates' );

	// Add support for AudioTheme widgets.
	add_theme_support( 'audiotheme-widgets', array(
		'record', 'track', 'upcoming-gigs', 'video',
	) );
}
add_action( 'after_setup_theme', 'huesos_audiotheme_setup', 11 );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 1.0.0
 */
function huesos_audiotheme_enqueue_assets() {
	if ( in_array( get_post_type(), array( 'audiotheme_record', 'audiotheme_track' ) ) ) {
		wp_enqueue_script( 'jquery-cue' );
	}
}
add_action( 'wp_enqueue_scripts', 'huesos_audiotheme_enqueue_assets', 20 );

/**
 * Add additional HTML classes to posts.
 *
 * @since 1.0.0
 *
 * @param array $classes List of HTML classes.
 * @return array
 */
function huesos_audiotheme_post_class( $classes ) {
	if ( '' === get_the_title() ) {
		$classes[] = 'no-title';
	}

	if ( is_singular( 'audiotheme_gig' ) && audiotheme_gig_has_venue() ) {
		$classes[] = 'has-venue';
	}

	if ( 'audiotheme_video' === get_post_type() && get_audiotheme_video_url() ) {
		$classes[] = 'has-video';
	}

	return array_unique( $classes );
}
add_filter( 'post_class', 'huesos_audiotheme_post_class', 10 );

/**
 * Filter the sidebar status on AudioTheme archives and singular posts.
 *
 * @since 1.0.0
 *
 * @param bool $is_active_sidebar Whether the sidebar is active.
 * @param string $index The sidebar id.
 * @return bool
 */
function huesos_audiotheme_sidebar_status( $is_active_sidebar, $index ) {
	if ( 'sidebar-1' !== $index || ! $is_active_sidebar ) {
		return $is_active_sidebar;
	}

	$post_type = get_post_type();

	if ( is_audiotheme_post_type_archive() ) {
		$archive_post_type = get_audiotheme_post_type_archive();
	} elseif ( is_singular() && 0 === strpos( $post_type, 'audiotheme_' ) ) {
		$archive_post_type = ( 'audiotheme_track' === $post_type ) ? 'audiotheme_record' : $post_type;
	} elseif ( is_tax( 'audiotheme_record_type' ) ) {
		$archive_post_type = 'audiotheme_record';
	} elseif ( is_tax( 'audiotheme_video_category' ) ) {
		$archive_post_type = 'audiotheme_video';
	}

	if ( ! empty( $archive_post_type ) ) {
		$is_active_sidebar = ( 'enabled' === get_audiotheme_archive_meta( 'huesos_sidebar', true, 'enabled', $archive_post_type ) );
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'huesos_audiotheme_sidebar_status', 10, 2 );


/*
 * AudioTheme hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Display the artist name in the record archive loop.
 *
 * @since 1.1.0
 *
 * @param int $post_id Post ID.
 */
function huesos_audiotheme_record_archive_grid_meta( $post_id ) {
	global $wp_query;

	if ( ! is_post_type_archive( 'audiotheme_record' ) || ! $wp_query->is_main_query() || ! in_the_loop() ) {
		return;
	}

	printf(
		'<p class="block-grid-item-meta">%s</p>',
		esc_html( get_audiotheme_record_artist( $post_id ) )
	);
}
add_action( 'huesos_block_grid_item_bottom', 'huesos_audiotheme_record_archive_grid_meta' );

/**
 * Enable the filter to add attributes to post thumbnails.
 *
 * @since 1.1.0
 *
 * @see huesos_audiotheme_thumbnail_attributes()
 */
function huesos_audiotheme_enable_post_thumbnail_attributes_filter( $post_id ) {
	global $huesos_post_id;
	$huesos_post_id = $post_id;
	add_filter( 'wp_get_attachment_image_attributes', 'huesos_audiotheme_thumbnail_attributes', 10, 2 );
}
add_action( 'begin_fetch_post_thumbnail_html', 'huesos_audiotheme_enable_post_thumbnail_attributes_filter' );

/**
 * Disable the filter to add attributes to post thumbnails.
 *
 * @since 1.1.0
 *
 * @see huesos_audiotheme_thumbnail_attributes()
 */
function huesos_audiotheme_disable_post_thumbnail_attributes_filter() {
	remove_filter( 'wp_get_attachment_image_attributes', 'huesos_audiotheme_thumbnail_attributes', 10, 2 );
}
add_action( 'end_fetch_post_thumbnail_html', 'huesos_audiotheme_disable_post_thumbnail_attributes_filter' );

/**
 * Add attributes to post thumbnails to wire up play buttons in JavaScript.
 *
 * @since 1.1.0
 *
 * @todo This may need to be updated to retrieve the $post_id from
 *       huesos_audiotheme_enable_post_thumbnail_attributes_filter() if
 *       it gets called outside of a loop.
 *
 * @param array $attributes Post thumbnail image attributes.
 * @param WP_Post $attachment Attachment post object.
 * @return array
 */
function huesos_audiotheme_thumbnail_attributes( $attributes, $attachment ) {
	global $huesos_post_id, $wp_query;

	// Records and tracks.
	if (
		in_array( get_post_type( $huesos_post_id ), array( 'audiotheme_record', 'audiotheme_track' ) ) &&
		huesos_player()->is_record_playable( $huesos_post_id )
	) {
		$attributes['class']        .= ' js-playable';
		$attributes['data-post-id']  = $huesos_post_id;
		$attributes['data-type']     = 'record';
	}

	// Videos.
	elseif (
		in_the_loop() && $wp_query->is_main_query() &&
		is_post_type_archive( 'audiotheme_video' ) &&
		'audiotheme_video' === get_post_type( $huesos_post_id )
	) {
		$attributes['class']        .= ' js-playable';
		$attributes['data-post-id']  = $huesos_post_id;
		$attributes['data-type']     = 'video';
	}

	return $attributes;
}

/**
 * Adjust AudioTheme widget image sizes.
 *
 * @since 1.0.0
 *
 * @param string|array Image size.
 * @return array
 */
function huesos_audiotheme_widget_image_size( $size ) {
	$size = array( 440, 440 ); // sidebar width x 2

	if ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
		$size = array( 1280, 1280 ); // content width x 2
	}

	return $size;
}
add_filter( 'audiotheme_widget_record_image_size', 'huesos_audiotheme_widget_image_size' );
add_filter( 'audiotheme_widget_track_image_size', 'huesos_audiotheme_widget_image_size' );
add_filter( 'audiotheme_widget_video_image_size', 'huesos_audiotheme_widget_image_size' );

/**
 * Remove widget titles for AudioTheme Record, Track, and Video widgets if the
 * widget title is not set.
 *
 * @since 1.1.0
 *
 * @param  string $title    Widget title.
 * @param  array  $instance Widget values.
 * @param  array  $args     Registered widget area args.
 * @param  int    $id       Widget ID.
 * @return string           Widget title or empty string.
 */
function huesos_audiotheme_widget_title( $title, $instance, $args, $id ) {
	/**
	 * 1. Is home widget area.
	 * 2. Is a Record, Track, or Video widget.
	 * 3. Is actual widget title empty.
	 */
	if (
		'home-widgets' === $args['id']
		&& in_array( $id, array( 'audiotheme-record', 'audiotheme-track', 'audiotheme-video' ) )
		&& empty( $instance['title_raw'] )
	) {
		$title = '';
	}

	return $title;
}
add_filter( 'audiotheme_widget_title', 'huesos_audiotheme_widget_title', 10, 4 );

/**
 * Add styles for static Google Map images.
 *
 * @since 1.0.0
 * @link https://developers.google.com/maps/documentation/staticmaps/?csw=1#StyledMaps
 *
 * @param array $styles Style key-value pairs.
 * @return array
 */
function huesos_google_static_map_styles( $styles ) {
	// Global styles.
	$styles[] = array(
		'element' => 'geometry.fill',
		'color'   => '0xf7f7f7',
	);

	$styles[] = array(
		'element' => 'geometry.stroke',
		'color'   => '0xffffff',
	);

	$styles[] = array(
		'element' => 'labels.text.fill',
		'color'   => '0x000000',
	);

	// Road styles.
	$styles[] = array(
		'feature' => 'road',
		'element' => 'geometry.fill',
		'color'   => '0x787878',
	);

	return $styles;
}
add_filter( 'audiotheme_google_static_map_styles', 'huesos_google_static_map_styles' );


/*
 * Admin hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Activate default archive setting fields.
 *
 * @since 1.0.0
 *
 * @param array $fields List of default fields to activate.
 * @param string $post_type Post type archive.
 * @return array
 */
function huesos_audiotheme_archive_settings_fields( $fields, $post_type ) {
	if ( in_array( $post_type, array( 'audiotheme_record', 'audiotheme_video' ) ) ) {
		$fields['posts_per_archive_page'] = true;
	}

	return $fields;
}
add_filter( 'audiotheme_archive_settings_fields', 'huesos_audiotheme_archive_settings_fields', 10, 2 );

/**
 * Save AudioTheme archive settings.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 */
function huesos_audiotheme_admin_save_archive( $post_id, $post, $post_type ) {
	$value = isset( $_POST['huesos_sidebar'] ) ? 'disabled' : 'enabled';
	update_post_meta( $post_id, 'huesos_sidebar', $value );

	$value = isset( $_POST['huesos_sidebar_singular'] ) ? 'disabled' : 'enabled';
	update_post_meta( $post_id, 'huesos_sidebar_singular', $value );

	$value = isset( $_POST['huesos_featured_video'] ) ? absint( $_POST['huesos_featured_video'] ) : '';
	update_post_meta( $post_id, 'huesos_featured_video', $value );
}
add_action( 'save_audiotheme_archive_settings', 'huesos_audiotheme_admin_save_archive', 10, 3 );

/**
 * Activate the archive settings meta box for the Gigs archive.
 */
add_filter( 'add_audiotheme_archive_settings_meta_box_audiotheme_gig', '__return_true' );

/**
 * Display AudioTheme archive setting fields.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Archive post.
 */
function huesos_audiotheme_admin_archive_meta_box( $post ) {
	$post_type = is_audiotheme_post_type_archive_id( $post->ID );

	if ( ! $post_type ) {
		return;
	}

	$sidebar_status = get_audiotheme_archive_meta( 'huesos_sidebar', true, 'enabled', $post_type );
	$sidebar_singular_status = get_audiotheme_archive_meta( 'huesos_sidebar_singular', true, 'enabled', $post_type );
	?>
	<p>
		<label for="huesos-sidebar-status">
			<input type="checkbox" name="huesos_sidebar" id="huesos-sidebar-status" value="1" <?php checked( $sidebar_status, 'disabled' ); ?>>
			<?php esc_html_e( 'Disable the sidebar on this archive?', 'huesos' ); ?>
		</label>
	</p>
	<p>
		<label for="huesos-sidebar-singular-status">
			<input type="checkbox" name="huesos_sidebar_singular" id="huesos-sidebar-singular-status" value="1" <?php checked( $sidebar_singular_status, 'disabled' ); ?>>
			<?php esc_html_e( 'Disable the sidebar for posts in this archive?', 'huesos' ); ?>
		</label>
	</p>
	<?php
}
add_action( 'audiotheme_archive_settings_meta_box', 'huesos_audiotheme_admin_archive_meta_box', 15 );

/**
 * Display AudioTheme video archive setting fields.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Archive post.
 */
function huesos_audiotheme_admin_video_archive_meta_box( $post ) {
	$post_type = is_audiotheme_post_type_archive_id( $post->ID );

	if ( 'audiotheme_video' !== $post_type ) {
		return;
	}

	$videos = get_posts( array(
		'post_type'      => 'audiotheme_video',
		'post_status'    => 'publish',
		'posts_per_page' => 999,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	if ( ! $videos ) {
		return;
	}

	$options = wp_list_pluck( $videos, 'post_title', 'ID' );
	$featured_video = get_audiotheme_archive_meta( 'huesos_featured_video', true, '', $post_type );
	?>
	<p>
		<label for="huesos-featured-video"><?php esc_html_e( 'Featured Video:', 'huesos' ); ?></label>
		<select name="huesos_featured_video" id="huesos-featured-video">
			<option value=""></option>
			<?php
			foreach ( $options as $id => $value ) {
				printf( '<option value="%s"%s>%s</option>',
					esc_attr( $id ),
					selected( $id, $featured_video, false ),
					esc_html( $value )
				);
			}
			?>
		</select>
	</p>
	<?php
}
add_action( 'audiotheme_archive_settings_meta_box', 'huesos_audiotheme_admin_video_archive_meta_box', 20 );


/*
 * Supported plugin hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Disable Jetpack Infinite Scroll on AudioTheme post types.
 *
 * @since 1.0.0
 *
 * @param bool $supported Whether Infinite Scroll is supported for the current request.
 * @return bool
 */
function huesos_audiotheme_infinite_scroll_archive_supported( $supported ) {
	$post_type = get_post_type() ? get_post_type() : get_query_var( 'post_type' );

	if ( $post_type && false !== strpos( $post_type, 'audiotheme_' ) ) {
		$supported = false;
	}

	return $supported;
}
add_filter( 'infinite_scroll_archive_supported', 'huesos_audiotheme_infinite_scroll_archive_supported' );


/*
 * Template tags.
 * -----------------------------------------------------------------------------
 */

/**
 * Display ticket information.
 *
 * @since 1.0.0
 *
 * @param string $before Optional. String to prepend to the default HTML.
 * @param string $after Optional. String to append to the default HTML.
 * @return string
 */
function huesos_the_audiotheme_tickets_html( $before = '', $after = '' ) {
	$gig_tickets_price = get_audiotheme_gig_tickets_price();
	$gig_tickets_url = get_audiotheme_gig_tickets_url();

	if ( ! $gig_tickets_price || ! $gig_tickets_url ) {
		return;
	}

	$html = __( 'Tickets', 'huesos' );

	if ( $gig_tickets_price ) {
		$html .= sprintf( ' <span class="gig-ticket-price" itemprop="price">%s</span>', esc_html( $gig_tickets_price ) );
	}

	if ( $gig_tickets_url ) {
		$html = sprintf( '<a class="gig-tickets-link button js-maybe-external" href="%s" itemprop="url">%s</a>',
			esc_html( $gig_tickets_price ),
			$html
		);
	}

	echo $before . $html . $after; // XSS OK
}

/**
 * Add HTML attributes to a track element.
 *
 * @since 1.0.0
 *
 * @param int $track_id Optional. The track ID. Defaults to the current track in the loop.
 */
function huesos_track_attributes( $track_id = 0 ) {
	$track = get_post( $track_id );

	$classes = 'track';
	if ( get_audiotheme_track_file_url( $track->ID ) ) {
		$classes .= ' is-playable js-play-record';
	}

	$attributes = array(
		'class'          => $classes,
		'itemprop'       => 'track',
		'itemtype'       => 'http://schema.org/MusicRecording',
		'data-record-id' => absint( $track->post_parent ),
		'data-track-id'  => absint( $track->ID ),
	);

	foreach ( $attributes as $key => $value ) {
		printf(
			' %1$s="%2$s"',
			$key, // XSS OK
			esc_attr( $value )
		);
	}
}


/*
 * Template Helpers.
 * -----------------------------------------------------------------------------
 */

/**
 * Load the featured video template part.
 *
 * @since 1.0.0
 */
function huesos_audiotheme_template_featured_video() {
	$post_type = is_audiotheme_post_type_archive_id( get_post()->ID );

	if (
		'audiotheme_video' === $post_type ||
		! $featured_video = get_audiotheme_archive_meta( 'huesos_featured_video', true, '', $post_type )
	) {
		return;
	}

	include( locate_template( 'audiotheme/parts/featured-video.php' ) );
}
add_action( 'huesos_block_grid_before', 'huesos_audiotheme_template_featured_video' );


/*
 * AJAX callbacks.
 * -----------------------------------------------------------------------------
 */

/**
 * Set up JSON video data for use in JS.
 *
 * @since 1.0.0
 */
function huesos_ajax_audiotheme_get_video_data() {
	wp_send_json( array(
		'videoHtml' => get_audiotheme_video( absint( $_GET['post_id'] ) ),
	) );

	exit;
}
add_action( 'wp_ajax_huesos_get_video_data', 'huesos_ajax_audiotheme_get_video_data' );
add_action( 'wp_ajax_nopriv_huesos_get_video_data', 'huesos_ajax_audiotheme_get_video_data' );
