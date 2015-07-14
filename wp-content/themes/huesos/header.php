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
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<!-- Google Analytics -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-65116126-1', 'auto');
		ga('send', 'pageview');

	</script>	

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
