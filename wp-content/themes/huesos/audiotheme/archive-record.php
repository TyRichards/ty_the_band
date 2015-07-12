<?php
/**
 * The template for displaying a record archives.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" <?php audiotheme_archive_class( array( 'content-area', 'archive-record' ) ); ?> role="main" itemprop="mainContentOfPage">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php the_audiotheme_archive_title( '<h1 class="page-title" itemprop="headline">', '</h1>' ); ?>
		</header>

		<?php the_audiotheme_archive_description( '<div class="page-content" itemprop="text">', '</div>' ); ?>

		<?php
		huesos_block_grid( array(
			'columns' => huesos_has_sidebar() ? 2 : 3,
		) );
		?>

		<?php
		the_posts_navigation( array(
			'prev_text' => __( 'Next', 'huesos' ),
			'next_text' => __( 'Previous', 'huesos' ),
		) );
		?>

	<?php else : ?>

		<?php get_template_part( 'audiotheme/parts/content-none', 'record' ); ?>

	<?php endif; ?>

</main>

<?php
get_sidebar( 'archive-record' );

get_footer();
