<?php
/**
 * The template for displaying a single record.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="content-area single-record" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/MusicAlbum">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'audiotheme/parts/content-record', str_replace( 'record-type-', '', get_audiotheme_record_type() ) ); ?>

		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>

</main>

<?php
get_sidebar();

get_footer();
