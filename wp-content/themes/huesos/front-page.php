<?php
/**
 * The front page template file.
 *
 * @package Huesos
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="content-area" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">

	<?php do_action( 'huesos_main_top' ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( huesos_has_content() || has_post_thumbnail() ) : ?>

			<?php get_template_part( 'templates/parts/content', 'page' ); ?>

		<?php elseif ( ! is_active_sidebar( 'home-widgets' ) ) : ?>

			<p class="front-page-fallback-notice">
				<?php
				if ( current_user_can( 'edit_pages' ) ) :
					printf( '<a class="button" href="%1$s">%2$s</a>',
						esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ),
						esc_html__( 'Add Page Content', 'huesos' )
					);
				endif;
				?>

				<?php
				if ( current_user_can( 'edit_theme_options' ) ) :
					printf( '<a class="button" href="%1$s">%2$s</a>',
						esc_url( admin_url( 'widgets.php' ) ),
						esc_html__( 'Add Home Widgets', 'huesos' )
					);
				endif;
				?>
			</p>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php do_action( 'huesos_main_bottom' ); ?>

</main>

<?php
get_sidebar( 'front-page' );

get_footer();
