<?php
/**
 * The template used for displaying a gig meta on single gig pages.
 *
 * @package Huesos
 * @since 1.0.0
 */

$gig = get_audiotheme_gig();
?>

<h2 class="gig-date-time">
	<span class="gig-date date">
		<meta content="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>" itemprop="startDate">
		<time datetime="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
			<?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'F d, Y' ) ) ); ?>
		</time>
	</span>

	<span class="gig-time"><?php echo esc_html( get_audiotheme_gig_time( '', 'g:i A', false, array( 'empty_time' => __( 'TBD', 'huesos' ) ) ) ); ?></span>
</h2>

<h3 class="gig-location">
	<?php echo get_audiotheme_venue_location( $gig->venue->ID ); ?>
</h3>

<?php the_audiotheme_gig_description( '<div class="gig-description" itemprop="description">', '</div>' ); ?>

<?php
huesos_the_audiotheme_tickets_html(
	'<div class="gig-tickets" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><h4 class="screen-reader-text">' . __( 'Tickets', 'huesos' ) . '</h4>',
	'</div>'
);
