<?php
/**
 * The template for displaying a list of gigs.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" <?php audiotheme_archive_class( array( 'content-area', 'archive-gig' ) ); ?> role="main" itemprop="mainContentOfPage">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php the_audiotheme_archive_title( '<h1 class="page-title" itemprop="headline">', '</h1>' ); ?>
		</header>

		<?php the_audiotheme_archive_description( '<div class="page-content" itemprop="text">', '</div>' ); ?>

		<div id="gigs" class="gig-list vcalendar">

			<header class="gig-list-header">
				<span class="gig-list-date"><?php esc_html_e( 'Date', 'huesos' ); ?></span>
				<span class="gig-list-location"><?php esc_html_e( 'Location', 'huesos' ); ?></span>
			</header>

			<?php $last_year_shown = ''; while ( have_posts() ) : the_post(); ?>

				<?php
				$year = get_audiotheme_gig_time( 'Y' );
				if ( $year !== $last_year_shown ) {
					echo '<h2 class="gig-list-year">' . esc_html( $year ) . '</h2>';
				}
				$last_year_shown = $year;
				?>

				<dl id="post-<?php the_ID(); ?>" <?php post_class( 'gig-card vevent' ); ?> itemscope itemtype="http://schema.org/MusicEvent">
					<?php get_template_part( 'audiotheme/parts/gig-card' ); ?>
				</dl>

			<?php endwhile; ?>

		</div>

		<?php huesos_content_navigation(); ?>

	<?php else : ?>

		<?php get_template_part( 'audiotheme/parts/content-none', 'gig' ); ?>

	<?php endif; ?>

</main>

<?php
get_sidebar( 'archive-gig' );

get_footer();
