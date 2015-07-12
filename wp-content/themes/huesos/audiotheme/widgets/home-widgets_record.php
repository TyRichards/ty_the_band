<?php
/**
 * Template to display a Record widget.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
	<figure class="featured-image">
		<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo get_the_post_thumbnail( $post->ID, $image_size ); ?></a>
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

<?php if ( $links = get_audiotheme_record_links( $post->ID ) ) : ?>

	<div class="meta-links">
		<h3 class="screen-reader-text"><?php esc_html_e( 'Record Links', 'huesos' ); ?></h3>
		<ul>
			<?php
			foreach ( $links as $link ) {
				printf( '<li><a class="button js-maybe-external" href="%s" itemprop="url">%s</a></li>',
					esc_url( $link['url'] ),
					esc_html( $link['name'] )
				);
			}
			?>
		</ul>
	</div>

<?php
endif;
