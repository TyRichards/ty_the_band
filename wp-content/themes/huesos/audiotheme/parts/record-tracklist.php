<?php
/**
 * The template for displaying a track list.
 *
 * @package Huesos
 * @since 1.0.0
 */

if ( $tracks = get_audiotheme_record_tracks() ) :
?>

	<div class="tracklist-area">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Record Tracklist', 'huesos' ); ?></h2>
		<meta itemprop="numTracks" content="<?php echo esc_attr( count( $tracks ) ); ?>" />
		<ol class="tracklist">
			<?php foreach ( $tracks as $track ) : ?>

				<li id="track-<?php echo absint( $track->ID ); ?>" <?php huesos_track_attributes( $track->ID ); ?>>
					<?php
					printf(
						'<a class="track-title" href="%1$s" itemprop="url"><span itemprop="name">%2$s</span></a>',
						esc_url( get_permalink( $track->ID ) ),
						get_the_title( $track->ID )
					);
					?>
				</li>

			<?php endforeach; ?>
		</ol>
	</div>

<?php
endif;
