<?php
/**
 * The template used for displaying individual gigs.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/MusicEvent">
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>
		<?php get_template_part( 'audiotheme/parts/gig-meta' ); ?>
	</header>

	<?php if ( audiotheme_gig_has_venue() ) : ?>
		<div class="entry-meta content-side">
			<?php get_template_part( 'audiotheme/parts/venue-meta' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content content" itemprop="description">
		<?php the_content( '' ); ?>
	</div>
</article>
