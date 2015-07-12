<?php
/**
 * The template for displaying a single video.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="content-area single-video" role="main" itemprop="mainContentOfPage">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'audiotheme/parts/content-video' ); ?>

		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>

</main>

<?php
get_sidebar();

get_footer();
