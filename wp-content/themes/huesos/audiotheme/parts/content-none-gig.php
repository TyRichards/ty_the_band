<?php
/**
 * The template for displaying a message when there aren't any upcoming gigs.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<section class="no-results not-found">
	<?php
	$recent_gigs = new Audiotheme_Gig_Query( array(
		'order'          => 'desc',
		'posts_per_page' => 5,
		'meta_query'     => array(
			array(
				'key'     => '_audiotheme_gig_datetime',
				'value'   => current_time( 'mysql' ),
				'compare' => '<=',
				'type'    => 'DATETIME',
			),
		),
	) );

	if ( $recent_gigs->have_posts() ) :
	?>

		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Recent Shows', 'huesos' ); ?></h1>
		</header>

		<div class="page-content">
			<p><em><?php esc_html_e( "There currently aren't any scheduled shows. Check back soon!", 'huesos' ); ?></em></p>
		</div>

		<article id="gigs" class="gig-list vcalendar">
			<header class="gig-list-header">
				<span class="gig-list-date"><?php esc_html_e( 'Date', 'huesos' ); ?></span>
				<span class="gig-list-location"><?php esc_html_e( 'Location', 'huesos' ); ?></span>
			</header>

			<?php while ( $recent_gigs->have_posts() ) : $recent_gigs->the_post(); ?>
				<dl class="gig-card vevent" itemscope itemtype="http://schema.org/MusicEvent">
					<?php get_template_part( 'audiotheme/parts/gig-card' ); ?>
				</dl>
			<?php endwhile; ?>
		</article>

	<?php else : ?>

		<div class="page-content">
			<?php if ( current_user_can( 'publish_posts' ) ) : ?>
				<p>
					<?php
					printf( _x( 'Ready to publish your first gig? <a href="%1$s" class="">Get started here</a>.', 'add post type link', 'huesos' ),
						esc_url( add_query_arg( 'post_type', get_post_type_object( 'audiotheme_gig' )->name, admin_url( 'post-new.php' ) ) )
					);
					?>
				</p>
			<?php endif; ?>

			<p>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Upcoming  Shows', 'huesos' ); ?></h1>
				</header>				
				<?php esc_html_e( "There currently aren't any scheduled shows. Check back soon!", 'huesos' ); ?>
				<br/><br/><br/>
				<?php gravity_form( 2, true, true, false, '', true ); ?>
			</p>
		</div>

	<?php endif; ?>
</section>
