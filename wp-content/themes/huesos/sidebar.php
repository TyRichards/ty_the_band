<?php
/**
 * The template used for displaying the tertiary sidebar content.
 *
 * @package Huesos
 * @since 1.0.0
 */

if ( huesos_has_sidebar( 'sidebar-1' ) ) :
?>

	<div id="tertiary" class="widget-area widget-area--sidebar" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
		<?php do_action( 'huesos_sidebar_top' ); ?>

		<?php dynamic_sidebar( 'sidebar-1' ); ?>

		<?php do_action( 'huesos_sidebar_bottom' ); ?>
	</div>

<?php
endif;
