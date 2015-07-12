<?php
/**
 * Template to display a Video widget.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php if ( get_audiotheme_video_url( $post->ID ) ) : ?>
	<figure class="entry-video responsive-video">
		<?php echo get_audiotheme_video( $post->ID ); ?>
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
