<?php
/**
 * The template for displaying the theme header.
 *
 * @package Huesos
 * @since 1.0.0
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'huesos' ); ?></a>

		<?php do_action( 'huesos_before' ); ?>

		<div id="main-sidebar" class="sidebar main-sidebar">
			<header id="masthead" class="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
				<?php do_action( 'huesos_header_top' ); ?>

				<div class="mobile-navigation">
					<?php huesos_mobile_navigation(); ?>
				</div>

				<div class="site-branding">
					<?php huesos_site_branding(); ?>
				</div>

				<?php do_action( 'huesos_header_bottom' ); ?>
			</header>

			<?php get_sidebar( 'header' ); ?>
		</div>

		<?php do_action( 'huesos_content_before' ); ?>

		<div id="content" class="site-content">

			<?php do_action( 'huesos_content_top' ); ?>
