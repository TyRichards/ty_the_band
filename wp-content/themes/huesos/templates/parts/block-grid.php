<?php
/**
 * The template used for displaying block grid content.
 *
 * Loaded by huesos_block_grid() in includes/template-tags.php.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

		<article id="block-grid-item-<?php the_id(); ?>" <?php post_class( 'block-grid-item' ); ?>>
			<?php do_action( 'huesos_block_grid_item_top', get_the_ID() ); ?>

			<?php
			printf( '<a class="block-grid-item-thumbnail post-thumbnail" href="%1$s">%2$s</a>',
				esc_url( get_the_permalink() ),
				apply_filters( 'huesos_block_grid_item_thumbnail', get_the_post_thumbnail(), get_the_ID() )
			);
			?>

			<?php the_title( '<h2 class="block-grid-item-title"><a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

			<?php do_action( 'huesos_block_grid_item_bottom', get_the_ID() ); ?>
		</article>

	<?php endwhile; ?>

</div>
