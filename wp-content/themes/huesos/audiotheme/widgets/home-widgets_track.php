<?php
/**
 * Template to display a Track widget.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php if ( $thumbnail_id = get_audiotheme_track_thumbnail_id( $post->ID ) ) : ?>
	<figure class="featured-image">
		<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
			<?php echo wp_get_attachment_image( $thumbnail_id, $image_size ); ?>
		</a>
	</figure>
<?php endif; ?>

<?php
if ( ! empty( $title ) ) :
	printf( ' <a href="%s">%s</a>',
		esc_url( get_permalink( $post->ID ) ),
		$before_title . $title . $after_title
	);
endif;
?>

<?php
if ( ! empty( $link_text ) ) {
	$text .= sprintf( ' <a class="more-link" href="%s">%s</a>',
		esc_url( get_permalink( $post->ID ) ),
		$link_text
	);
};

if ( ! empty( $text ) ) :
	echo '<div class="widget-description">' . wpautop( $text ) . '</div>';
endif;
?>

<?php
$purchase_url = get_audiotheme_track_purchase_url( $post->ID );
$download_url = is_audiotheme_track_downloadable( $post->ID );

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
