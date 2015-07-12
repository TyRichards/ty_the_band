<?php
/**
 * The template used for displaying individual records.
 *
 * @package Huesos
 * @since 1.0.0
 */

$has_sidebar = huesos_has_sidebar();
$record_type = str_replace( 'record-type-', '', get_audiotheme_record_type() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $has_sidebar && has_post_thumbnail() ) : ?>
		<figure class="record-artwork">
			<a class="post-thumbnail" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" itemprop="image">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		</figure>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

		<?php if ( ! $has_sidebar && $artist = get_audiotheme_record_artist() ) : ?>
			<h2 class="entry-subtitle record-artist" itemprop="byArtist"><?php echo esc_html( $artist ); ?></h2>
		<?php endif; ?>
	</header>

	<div class="entry-meta content-side">
		<?php get_template_part( 'audiotheme/parts/record-meta', $record_type ); ?>
	</div>

	<?php get_template_part( 'audiotheme/parts/record-tracklist', $record_type ); ?>

	<div class="entry-content content" itemprop="description">
		<?php the_content( '' ); ?>
	</div>
</article>
