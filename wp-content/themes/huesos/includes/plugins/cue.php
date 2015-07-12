<?php
/**
 * Cue Compatibility File
 *
 * @package Huesos
 * @since 1.0.0
 * @link https://audiotheme.com/view/cue/
 */

/**
 * Register a player for Cue.
 *
 * @since 1.0.0
 *
 * @param array $players List of players.
 */
function huesos_register_cue_players( $players ) {
	$players['huesos_player'] = __( 'Player', 'huesos' );
	return $players;
}
add_filter( 'cue_players', 'huesos_register_cue_players' );

/**
 * Get Cue tracks.
 *
 * @since 1.0.0
 *
 * @param array $track List of tracks.
 */
function huesos_cue_player_tracks( $tracks ) {
	$tracks = get_cue_player_tracks( 'huesos_player', array( 'context' => 'wp-playlist' ) );

	foreach ( $tracks as $key => $track ) {
		$tracks[ $key ]['id'] = md5( $track['artist'] . $track['title'] . $track['src'] );
	}

	return $tracks;
}
add_filter( 'pre_huesos_player_tracks', 'huesos_cue_player_tracks' );
