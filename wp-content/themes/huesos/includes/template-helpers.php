<?php
/**
 * Helper methods for loading or displaying template partials.
 *
 * These are typically miscellaneous template parts used outside the loop.
 * Although if the partial requires any sort of set up or tearddown, moving that
 * logic into a helper keeps the parent template a little more lean, clean,
 * reusable and easier to override in child themes.
 *
 * Loading these partials within an action hook will allow them to be easily
 * added, removed, or reordered without changing the parent template file.
 *
 * Take a look at huesos_register_template_parts() to see where most of these
 * are inserted.
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Display the Home widget area on the front page.
 *
 * @since 1.0.0
 */
function huesos_home_widgets() {
	if ( ! is_front_page() || ! is_active_sidebar( 'home-widgets' ) ) {
		return;
	}
	?>
	<div class="widget-area widget-area--home">
		<?php do_action( 'huesos_home_widgets_top' ); ?>

		<?php dynamic_sidebar( 'home-widgets' ); ?>

		<?php do_action( 'huesos_home_widgets_bottom' ); ?>
	</div>
	<?php
}
add_action( 'huesos_main_bottom', 'huesos_home_widgets' );
