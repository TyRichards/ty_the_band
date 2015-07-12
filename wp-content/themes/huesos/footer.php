<?php
/**
 * The template for displaying the site footer.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

			<?php do_action( 'huesos_content_bottom' ); ?>

		</div> <!-- .site-content -->

		<?php do_action( 'huesos_content_after' ); ?>

		<?php do_action( 'huesos_after' ); ?>

	</div><!-- .site -->

	<?php wp_footer(); ?>
    <div class="footer">
        <div class="credits" role="contentinfo">
            <div class="copyright">&copy; <?php bloginfo('name'); ?> <?php echo date("Y") ?></div>         

            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <nav class="footer-navigation" role="navigation">                
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'depth'          => 1
                    ) );
                    ?>
                </nav>
            <?php endif; ?>        
            <!--<?php // huesos_credits(); ?> -->
        </div>
    </div>
</body>
</html>
