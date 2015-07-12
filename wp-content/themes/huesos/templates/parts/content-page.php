<?php
/**
 * The template used for displaying regular page content.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/CreativeWork">
	<?php huesos_entry_image(); ?>

	<header class="entry-header">
		<?php huesos_entry_title(); ?>
	</header>

	<div class="entry-content" itemprop="text">
		<?php the_content(); ?>
		<?php huesos_page_links(); ?>
	</div>
</article>
