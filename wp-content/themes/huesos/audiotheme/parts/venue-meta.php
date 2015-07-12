<?php
/**
 * The template used for displaying a venue meta on single gig pages.
 *
 * @package Huesos
 * @since 1.0.0
 */

$gig = get_audiotheme_gig();
$venue = get_audiotheme_venue( $gig->venue->ID );
?>

<?php if ( ! empty( $venue->name ) ) : ?>
	<h3 class="venue-title"><?php echo esc_html( $venue->name ); ?></h3>
<?php endif; ?>

<figure class="venue-map">
	<?php
	printf( '<a href="%s" style="background-image: url(\'%s\')" target="_blank"></a>',
		esc_url( get_audiotheme_google_map_url() ),
		esc_url( get_audiotheme_google_static_map_url( array(
			'width'  => 640,
			'height' => 220,
		) ) )
	);
	?>
</figure>

<dl class="venue-meta" itemprop="location" itemscope itemtype="http://schema.org/EventVenue">
	<dt class="venue-address"><?php esc_html_e( 'Address', 'huesos' ); ?></dt>
	<dd class="venue-address">
		<?php
		the_audiotheme_venue_vcard( array(
			'container'         => '',
			'show_name_link'    => false,
			'show_phone'        => false,
			'separator_address' => '&nbsp;',
			'separator_country' => '',
		) );
		?>
	</dd>

	<?php if ( $venue->phone ) : ?>
		<dt class="venue-phone"><?php esc_html_e( 'Phone', 'huesos' ); ?></dt>
		<dd class="venue-phone"><?php echo esc_html( $venue->phone ); ?></dd>
	<?php endif; ?>

	<?php if ( $venue->website ) : ?>
		<dt class="venue-website"><?php esc_html_e( 'Website', 'huesos' ); ?></dt>
		<dd class="venue-website"><a href="<?php echo esc_url( $venue->website ); ?>" itemprop="url"><?php echo esc_html( audiotheme_simplify_url( $venue->website ) ); ?></a></dd>
	<?php endif; ?>
</dl>
