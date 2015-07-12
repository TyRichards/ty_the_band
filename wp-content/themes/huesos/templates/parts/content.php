<?php
/**
 * The template used for displaying content.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
	<?php huesos_entry_image(); ?>

	<header class="entry-header">
		<?php huesos_entry_title(); ?>
	</header>

	<div class="entry-meta">
		<?php huesos_posted_by(); ?>
		<?php huesos_posted_on(); ?>
	</div>

	<div class="entry-content" itemprop="text">
		<?php the_content( '' ); ?>
		<?php huesos_page_links(); ?>
	</div>

	<footer class="entry-footer">
		<?php huesos_entry_terms(); ?>
		<?php huesos_entry_more_link(); ?>
		<?php huesos_entry_comments_link(); ?>
	</footer>
</article>
