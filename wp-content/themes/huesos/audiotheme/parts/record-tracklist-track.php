<?php
/**
 * The template for displaying a track list in a single track template.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<div class="tracklist-area">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Record Tracklist', 'huesos' ); ?></h2>
	<ol class="tracklist">
		<li id="track-<?php the_ID(); ?>" <?php huesos_track_attributes( get_the_ID() ); ?>>
			<?php the_title( '<span class="track-title" itemprop="name">', '</span>' ); ?>
			<meta content="<?php the_permalink(); ?>" itemprop="url" />
		</li>
	</ol>
</div>
