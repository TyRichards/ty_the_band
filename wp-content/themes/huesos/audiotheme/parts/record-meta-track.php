<?php
/**
 * The template for displaying a track's meta.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php
$thumbnail_id = get_audiotheme_track_thumbnail_id();

if ( $thumbnail_id && ! huesos_has_sidebar() ) :
?>

	<figure class="record-artwork">
		<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" itemprop="image">
			<?php echo wp_get_attachment_image( $thumbnail_id, 'large' ); ?>
		</a>
	</figure>

<?php endif; ?>

<?php
$artist = get_audiotheme_record_artist();
$year   = get_audiotheme_record_release_year( $post->post_parent );
$genre  = get_audiotheme_record_genre( $post->post_parent );

if ( $artist || $year || $genre ) :
?>

	<div class="record-details record-details--track">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Record Details', 'huesos' ); ?></h2>
		<dl>
			<?php if ( $artist ) : ?>
				<dt class="record-artist screen-reader-text"><?php esc_html_e( 'Artist', 'huesos' ); ?></dt>
				<dd class="record-artist" itemprop="byArtist"><?php echo esc_html( $artist ); ?></dd>
			<?php endif; ?>

			<?php if ( $year ) : ?>
				<dt class="record-year"><?php esc_html_e( 'Release', 'huesos' ); ?></dt>
				<dd class="record-year" itemprop="dateCreated"><?php echo esc_html( $year ); ?></dd>
			<?php endif; ?>

			<?php if ( $genre ) : ?>
				<dt class="record-genre"><?php esc_html_e( 'Genre', 'huesos' ); ?></dt>
				<dd class="record-genre" itemprop="genre"><?php echo esc_html( $genre ); ?></dd>
			<?php endif; ?>
		</dl>
	</div>

<?php endif; ?>

<?php
$purchase_url = get_audiotheme_track_purchase_url();
$download_url = is_audiotheme_track_downloadable();

if ( $purchase_url || $download_url ) :
?>

	<div class="meta-links">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Track Links', 'huesos' ); ?></h2>
		<ul>
			<?php if ( $purchase_url ) : ?>
				<li><a class="button" href="<?php echo esc_url( $purchase_url ); ?>" itemprop="url"><?php esc_html_e( 'Purchase', 'huesos' ); ?></a></li>
			<?php endif; ?>

			<?php if ( $download_url ) : ?>
				<li><a class="button" href="<?php echo esc_url( $download_url ); ?>" itemprop="url" download="<?php esc_attr( basename( $download_url ) ); ?>"><?php esc_html_e( 'Download', 'huesos' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>

<?php
endif;
