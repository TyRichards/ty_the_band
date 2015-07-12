<?php
/**
 * The template used for displaying search content.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php huesos_entry_title(); ?>
	</header>

	<div class="entry-summary" itemprop="text">
		<?php the_excerpt(); ?>
	</div>

	<?php if ( 'post' === get_post_type() ) : ?>

		<footer class="entry-footer">
			<?php huesos_posted_by(); ?>
			<?php huesos_posted_on(); ?>
		</footer>

	<?php endif; ?>
</article>
