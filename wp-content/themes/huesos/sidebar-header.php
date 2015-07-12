<?php
/**
 * The template used for displaying secondary sidebar content in the header.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<div id="secondary" class="secondary">
	<?php do_action( 'huesos_sidebar_top' ); ?>

	<nav id="site-navigation" class="site-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Main Menu', 'huesos' ); ?></h2>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav-menu',
			'fallback_cb'    => 'huesos_primary_nav_menu_fallback_cb',
		) );
		?>
	</nav>

	<?php if ( has_nav_menu( 'social' ) ) : ?>

		<nav class="social-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Social Media Profiles', 'huesos' ); ?></h2>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'social',
				'container'      => false,
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
			) );
			?>
		</nav>

	<?php endif; ?>

	<div class="credits" role="contentinfo">		
		<!--<?php // huesos_credits(); ?> -->
	</div>

	<?php do_action( 'huesos_sidebar_bottom' ); ?>
</div>
