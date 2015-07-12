<?php
/**
 * The template used for displaying all single posts.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="content-area" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">

	<?php do_action( 'huesos_main_top' ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'templates/parts/content', get_post_format() ); ?>

		<?php huesos_content_navigation(); ?>

		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>

	<?php do_action( 'huesos_main_bottom' ); ?>

</main>

<?php
get_sidebar( 'single' );

get_footer();
