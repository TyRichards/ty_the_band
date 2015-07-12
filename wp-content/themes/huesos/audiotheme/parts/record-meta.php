<?php
/**
 * The template for displaying a record meta.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php if ( has_post_thumbnail() && ! huesos_has_sidebar() ) : ?>

	<figure class="record-artwork">
		<a class="post-thumbnail" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" itemprop="image">
			<?php the_post_thumbnail( 'large' ); ?>
		</a>
	</figure>

<?php endif; ?>

<?php
$artist = get_audiotheme_record_artist();
$year   = get_audiotheme_record_release_year();
$genre  = get_audiotheme_record_genre();

if ( $artist || $year || $genre ) :
?>

	<div class="record-details">
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

<?php if ( $links = get_audiotheme_record_links() ) : ?>

	<div class="meta-links">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Record Links', 'huesos' ); ?></h2>
		<ul>
			<?php
			foreach ( $links as $link ) {
				printf(
					'<li><a class="button js-maybe-external" href="%s" itemprop="url">%s</a></li>',
					esc_url( $link['url'] ),
					esc_html( $link['name'] )
				);
			}
			?>
		</ul>
	</div>

<?php
endif;
