<?php
/**
 * The template for displaying a single track.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="content-area single-record single-record--track" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/MusicRecording">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'audiotheme/parts/content-track' ); ?>

		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>

</main>

<?php
get_sidebar();

get_footer();
