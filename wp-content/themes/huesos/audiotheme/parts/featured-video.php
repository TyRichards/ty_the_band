<?php
/**
 * The template for displaying the featured video when enabled.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<div id="huesos-preview" class="huesos-preview has-preview fade-in responsive-video" data-featured-video-id="<?php echo esc_attr( $featured_video ); ?>">
	<?php echo get_audiotheme_video( $featured_video ); ?>
</div>
