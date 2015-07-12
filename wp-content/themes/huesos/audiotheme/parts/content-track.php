<?php
/**
 * The template used for displaying individual tracks.
 *
 * @package Huesos
 * @since 1.0.0
 */

$thumbnail_id = get_audiotheme_track_thumbnail_id();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $thumbnail_id && huesos_has_sidebar() ) : ?>
		<figure class="record-artwork">
			<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" itemprop="image">
				<?php echo esc_url( wp_get_attachment_image( $thumbnail_id, 'large' ) ); ?>
			</a>
		</figure>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>
		<h2 class="entry-subtitle">
			<a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" itemprop="inAlbum"><?php echo get_the_title( $post->post_parent ); ?></a>
		</h2>
	</header>

	<div class="entry-meta">
		<?php get_template_part( 'audiotheme/parts/record-meta', 'track' ); ?>
	</div>

	<?php get_template_part( 'audiotheme/parts/record-tracklist', 'track' ); ?>

	<div class="entry-content content" itemprop="description">
		<?php the_content( '' ); ?>
	</div>
</article>
