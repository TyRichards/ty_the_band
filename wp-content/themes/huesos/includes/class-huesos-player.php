<?php
/**
 * Site-wide player functionality.
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Site-wide player class.
 *
 * @package Huesos
 * @since 1.0.0
 */
class Huesos_Player {
	/**
	 * Tracks in the player's playlist.
	 *
	 * @since 1.0.0
	 * @type array Array of tracks.
	 */
	protected $tracks;

	/**
	 * Load the site-wide player.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'wp_ajax_huesos_get_record_data',        array( $this, 'ajax_get_record_data' ) );
		add_action( 'wp_ajax_nopriv_huesos_get_record_data', array( $this, 'ajax_get_record_data' ) );
		add_action( 'save_post',                             array( $this, 'update_record_playable_status' ) );
		add_action( 'template_redirect',                     array( $this, 'register_hooks' ) );
	}

	/**
	 * Register hooks to set up the player.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		if ( get_theme_mod( 'disable_player' ) ) {
			return;
		}

		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'huesos_after',  array( $this, 'get_template_part' ) );
	}

	/**
	 * Load the site-wide player template part.
	 *
	 * @since 1.0.0
	 */
	public function get_template_part() {
		$tracks = $this->get_tracks();

		if ( empty( $tracks ) ) {
			return;
		}

		wp_enqueue_script( 'huesos-player' );

		$settings = array(
			'signature' => md5( implode( ',', wp_list_pluck( $tracks, 'src' ) ) ),
			'tracks'    => $tracks,
		);

		include( locate_template( 'templates/parts/player.php' ) );
		echo '<script type="application/json" id="huesos-player-settings">' . json_encode( $settings ) . '</script>';
	}

	/**
	 * Whether a record is playable.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id Record post ID.
	 * @return bool
	 */
	public function is_record_playable( $record_id ) {
		$is_playable = get_post_meta( $record_id, '_huesos_is_playable', true );

		if ( empty( $is_playable ) ) {
			$this->update_record_playable_status( $record_id );
			$is_playable = get_post_meta( $record_id, '_huesos_is_playable', true );
		}

		return ( 'yes' === $is_playable );
	}

	/**
	 * Update post meta indicating whether a record is playable.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Record or track post ID.
	 */
	public function update_record_playable_status( $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( ! in_array( $post_type, array( 'audiotheme_record', 'audiotheme_track' ) ) ) {
			return;
		}

		$record_id   = ( 'audiotheme_record' === $post_type ) ? $post_id : get_post( $post_id )->post_parent;
		$tracks      = get_audiotheme_record_tracks( $record_id, array( 'has_file' => true ) );
		$is_playable = empty( $tracks ) ? 'no' : 'yes';

		update_post_meta( $record_id, '_huesos_is_playable', $is_playable );
	}

	/*
	 * Hook callbacks
	 */

	/**
	 * Add classes to the body tag indicating the player status.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Array of HTML classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		$tracks = $this->get_tracks();

		if ( ! empty( $tracks ) ) {
			$classes[] = 'has-player';
		}

		return $classes;
	}

	/**
	 * AJAX callback to retrieve data about a record's tracks.
	 *
	 * @since 1.0.0
	 */
	public function ajax_get_record_data() {
		$record_id = absint( $_GET['record_id'] );
		$tracks    = $this->get_track_data( $record_id );

		if ( empty( $tracks ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( array(
			'tracks' => $tracks,
		) );
	}

	/*
	 * Protected methods.
	 */

	/**
	 * Retrieve the ID of the first record with playable tracks.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_first_playable_record_id() {
		$record_id = 0;

		$record_ids = $this->get_records( array(
			'fields'      => 'ids',
			'numberposts' => 50,
		) );

		if ( empty( $record_ids ) ) {
			return $record_id;
		}

		foreach ( $record_ids as $record_id ) {
			if ( $this->is_record_playable( $record_id ) ) {
				break;
			}
		}

		return $record_id;
	}

	/**
	 * Retrieve an array of record posts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $custom_args Custom args to pass to get_posts().
	 * @return array
	 */
	protected function get_records( $custom_args = array() ) {
		$args = array(
			'post_type'   => 'audiotheme_record',
			'post_status' => 'publish',
			'meta_key'    => '_audiotheme_release_year',
			'orderby'     => 'meta_value_num',
			'order'       => 'desc',
		);

		// Respect the archive order setting.
		$orderby = get_audiotheme_archive_meta( 'orderby', true, 'release_year', 'audiotheme_record' );

		switch ( $orderby ) {
			case 'custom' :
				$args['orderby'] = 'menu_order';
				$args['order']   = 'asc';
				unset( $args['meta_key'] );
				break;
			case 'title' :
				$args['orderby'] = 'title';
				$args['order']   = 'asc';
				unset( $args['meta_key'] );
				break;
		}

		$args = wp_parse_args( $custom_args, $args );

		return get_posts( $args );
	}

	/**
	 * Retrieve data about a record's tracks.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id Record post ID.
	 * @return array
	 */
	protected function get_track_data( $record_id ) {
		$tracks = array();
		$posts  = get_audiotheme_record_tracks( $record_id, array( 'has_file' => true ) );

		if ( empty( $posts ) ) {
			return $tracks;
		}

		foreach ( $posts as $track ) {
			$data   = array();
			$track  = get_post( $track );
			$record = get_post( $track->post_parent );

			$data['track_id'] = $track->ID;
			$data['title']    = $track->post_title;
			$data['artist']   = get_audiotheme_track_artist( $track->ID );
			$data['album']   = $record->post_title;

			$data['src']      = get_audiotheme_track_file_url( $track->ID );
			$data['length']   = get_audiotheme_track_length( $track->ID );

			if ( $thumbnail_id = get_audiotheme_track_thumbnail_id( $track ) ) {
				$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
				$data['artwork'] = $image[0];
			}

			$data['id'] = md5( $data['artist'] . $data['title'] . $data['src'] );
			$data['recordId'] = $record->ID;
			$data['trackId'] = $track->ID;

			$tracks[] = $data;
		}

		return $tracks;
	}

	/**
	 * Retrieve tracks for the site-wide player.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of tracks.
	 */
	protected function get_tracks() {
		$tracks = array();

		// Return cached tracks.
		if ( ! is_null( $this->tracks ) ) {
			return $this->tracks;
		}

		// Fetch from a Cue playlist.
		if ( class_exists( 'Cue' ) ) {
			$tracks = huesos_theme()->template->get_tracks( 'huesos_player' );
		}

		// Fetch tracks from the first playable record.
		if ( empty( $tracks ) && defined( 'AUDIOTHEME_VERSION' ) ) {
			$record_id = $this->get_first_playable_record_id();

			if ( $record_id ) {
				$tracks = $this->get_track_data( $record_id );
			}
		}

		// Cache the results locally for this request.
		$this->tracks = $tracks;

		return $tracks;
	}
}
