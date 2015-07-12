<?php
/**
 * The template used for displaying a gig on the archive page or at the top of
 * the single gig page.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<?php if ( audiotheme_gig_has_venue() ) : ?>

	<dt class="gig-title"><?php the_audiotheme_gig_link(); ?></dt>

	<dd class="gig-permalink">
		<a href="<?php the_permalink(); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'More', 'huesos' ); ?></span>
		</a>
	</dd>

<?php else : ?>

	<dt class="gig-venue">
		<span class="gig-title"><?php esc_html_e( 'Gig venue details are missing or incomplete.', 'huesos' ); ?></span>
		<?php edit_post_link( __( 'Edit Gig', 'huesos' ) ); ?>
	</dt>

<?php endif; ?>


<dd class="gig-date date">
	<meta content="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>" itemprop="startDate">
	<time class="dtstart" datetime="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
		<?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'M d, Y' ) ) ); ?>
	</time>
</dd>


<?php if ( audiotheme_gig_has_venue() ) : ?>

	<dd class="gig-location location vcard" itemprop="location" itemscope itemtype="http://schema.org/EventVenue">
		<?php
		the_audiotheme_venue_vcard( array(
			'container'         => '',
			'show_name_link'    => false,
			'show_phone'        => false,
			'separator_country' => '/',
		) );
		?>
	</dd>

<?php endif; ?>

<?php
the_audiotheme_gig_description( '<dd class="gig-description" itemprop="description">', '</dd>' );
