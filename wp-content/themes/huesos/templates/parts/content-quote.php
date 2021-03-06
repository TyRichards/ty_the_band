<?php
/**
 * The template used for displaying quote post format content.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
	<?php huesos_entry_image(); ?>

	<header class="entry-header screen-reader-text">
		<?php huesos_entry_title(); ?>
	</header>

	<div class="entry-meta screen-reader-text">
		<?php huesos_posted_by(); ?>
	</div>

	<div class="entry-content" itemprop="text">
		<?php the_content( '' ); ?>
		<?php huesos_page_links(); ?>
	</div>

	<footer class="entry-footer">
		<?php huesos_entry_terms(); ?>
		<?php huesos_entry_more_link(); ?>
		<?php huesos_posted_on(); ?>
		<?php huesos_entry_comments_link(); ?>
	</footer>
</article>
