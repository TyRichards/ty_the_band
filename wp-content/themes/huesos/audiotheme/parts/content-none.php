<?php
/**
 * The template for displaying a message that posts cannot be found.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'huesos' ); ?></h1>
	</header>

	<div class="page-content">
		<?php
		$post_type        = get_post_type() ? get_post_type() : get_query_var( 'post_type' );
		$post_type_object = get_post_type_object( $post_type );

		if ( empty( $post_type_object ) && is_tax() ) {
			$taxonomy = get_taxonomy( get_queried_object()->taxonomy );
			$post_type_object = get_post_type_object( $taxonomy->object_type[0] );
		}
		?>

		<?php if ( current_user_can( 'publish_posts' ) ) : ?>

			<p>
				<?php
				printf( _x( 'Ready to publish your first %1$s? <a href="%2$s">Get started here</a>.', 'post type label; add post type link', 'huesos' ),
					esc_html( $post_type_object->labels->singular_name ),
					esc_url( add_query_arg( 'post_type', $post_type_object->name, admin_url( 'post-new.php' ) ) )
				);
				?>
			</p>

		<?php else : ?>

			<p>
				<?php
				printf( _x( 'There are currently not any %s available.', 'post type label', 'huesos' ),
					esc_html( $post_type_object->label )
				);
				?>
			</p>

		<?php endif; ?>
	</div>
</section>
