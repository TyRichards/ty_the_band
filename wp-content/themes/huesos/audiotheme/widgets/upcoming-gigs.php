<?php
/**
 * Template to display a Upcoming Gigs widget.
 *
 * @package Huesos
 * @since 1.0.0
 */

if ( ! empty( $title ) ) :
	echo $before_title . $title . $after_title;
endif;

if ( $loop->have_posts() ) :
?>

	<div class="gig-list vcalendar">
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

			<dl <?php post_class( 'gig-card vevent' ); ?> itemscope itemtype="http://schema.org/MusicEvent">
				<?php get_template_part( 'audiotheme/parts/gig-card' ); ?>
			</dl>

		<?php endwhile; ?>
	</div>

	<footer>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'audiotheme_gig' ) ); ?>" class="more-link"><?php esc_html_e( 'More', 'huesos' ); ?>&hellip;</a>
	</footer>

<?php else : ?>

	<p class="no-results">
		<?php esc_html_e( 'No gigs are currently scheduled.', 'huesos' ); ?>
	</p>

<?php
endif;
