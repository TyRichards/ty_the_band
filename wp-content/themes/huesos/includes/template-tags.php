<?php
/**
 * Custom template tags for this theme.
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Display the mobile navigation buttons.
 *
 * @since 1.0.0
 */
function huesos_mobile_navigation() {
	$html = '<button class="secondary-toggle">' . __( 'Menu', 'huesos' ) . '</button>';
	$html .= '<button class="player-toggle"><span class="screen-reader-text">' . __( 'Player', 'huesos' ) . '</span></button>';
	echo apply_filters( 'huesos_mobile_navigation_html', $html ); // XSS OK
}

if ( ! function_exists( 'huesos_site_branding' ) ) :
/**
 * Display the site logo, title, and description.
 *
 * @since 1.0.0
 */
function huesos_site_branding() {
	// Site logo.
	$output = huesos_theme()->logo->html();

	// Site title.
	$output .= sprintf(
		'<h1 class="site-title"><a href="%1$s" rel="home">%2$s</a></h1>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name', 'display' ) )
	);

	// Site description.
	$output .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';

	echo $output; // XSS OK
}
endif;

if ( ! function_exists( 'huesos_entry_title' ) ) :
/**
 * Display entry title with permalink on archive type pages.
 *
 * @since 1.0.0
 */
function huesos_entry_title() {
	$format = get_post_format();
	$title = get_the_title();

	if ( ! $title ) {
		return;
	}

	if ( ! is_singular() || 'link' === $format ) {
		$title = sprintf(
			'<a class="permalink" href="%1$s" rel="bookmark" itemprop="url">%2$s</a>',
			esc_url( ( 'link' === $format ) ? huesos_theme()->post_media->get_link_url() : get_the_permalink() ),
			$title
		);
	}

	printf( '<h1 class="entry-title" itemprop="headline">%s</h1>', $title );
}
endif;

if ( ! function_exists( 'huesos_entry_image' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since 1.0.0
 *
 * @param string|array Image size.
 */
function huesos_entry_image( $size = '' ) {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( empty( $size ) ) {
		$size = huesos_has_sidebar() ? 'post-thumbnail' : 'large';
	}

	if ( is_singular() ) :
	?>
		<figure class="post-thumbnail" itemprop="image">
			<?php the_post_thumbnail( $size ); ?>
		</figure>
	<?php else : ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" itemprop="image">
			<?php the_post_thumbnail( $size, array( 'alt' => get_the_title() ) ); ?>
		</a>
	<?php
	endif;
}
endif;

if ( ! function_exists( 'huesos_entry_more_link' ) ) :
/**
 * Display entry more link outside of the_content() wrapper.
 *
 * @since 1.0.0
 */
function huesos_entry_more_link() {
	if ( is_singular() || post_password_required() ) {
		return;
	}

	$content = get_post()->post_content;

	if ( false === strpos( $content, '<!--more' ) ) {
		return;
	}

	$link_text = sprintf(
		__( 'Continue reading %s', 'huesos' ),
		the_title( '<span class="screen-reader-text">', '</span>', false )
	);

	// Account for custom more link text.
	if ( preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
		$content = explode( $matches[0], $content, 2 );
		if ( ! empty( $matches[1] ) ) {
			$link_text = strip_tags( wp_kses_no_null( trim( $matches[1] ) ) );
		}
	}

	printf(
		'<span class="entry-more-link"><a class="more-link" href="%s">%s</a></span>',
		esc_url( get_permalink() ),
		$link_text
	);
}
endif;

if ( ! function_exists( 'huesos_entry_comments_link' ) ) :
/**
 * Display linked entry comment count.
 *
 * @since 1.0.0
 */
function huesos_entry_comments_link() {
	if ( is_singular() || post_password_required() || ! comments_open() || ! get_comments_number() ) {
		return;
	}

	echo '<span class="entry-comments-link">';
	comments_popup_link(
		__( 'Leave a comment', 'huesos' ),
		__( '1 Comment', 'huesos' ),
		__( '% Comments', 'huesos' )
	);
	echo '</span>';
}
endif;

if ( ! function_exists( 'huesos_get_entry_author' ) ) :
/**
 * Retrieve entry author.
 *
 * @since 1.0.0
 *
 * @return string
 */
function huesos_get_entry_author() {
	$html  = '<span class="entry-author author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person">';
	$html .= sprintf(
		'<a class="url fn n" href="%1$s" rel="author" itemprop="url"><span itemprop="name">%2$s</span></a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
	$html .= '</span>';

	return $html;
}
endif;

if ( ! function_exists( 'huesos_get_entry_date' ) ) :
/**
 * Retrieve HTML with meta information for the current post-date/time.
 *
 * @since 1.0.0
 *
 * @param bool $updated Optional. Whether to print the updated time, too. Defaults to true.
 * @return string
 */
function huesos_get_entry_date( $updated = true ) {
	$time_string = '<time class="entry-time published" datetime="%1$s">%2$s</time>';

	// To appease rich snippets, an updated class needs to be defined.
	// Default to the published time if the post has not been updated.
	if ( $updated ) {
		if ( get_the_time( 'U' ) === get_the_modified_time( 'U' ) ) {
			$time_string .= '<time class="entry-time updated" datetime="%1$s">%2$s</time>';
		} else {
			$time_string .= '<time class="entry-time updated" datetime="%3$s">%4$s</time>';
		}
	}

	return sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
}
endif;

if ( ! function_exists( 'huesos_posted_by' ) ) :
/**
 * Display post author byline.
 *
 * @since 1.0.0
 */
function huesos_posted_by() {
	?>
	<span class="posted-by byline">
		<?php
		/* translators: %s: Author name */
		printf( __( '<span class="sep">by</span> %s', 'huesos' ), huesos_get_entry_author() );
		?>
	</span>
	<?php
}
endif;

if ( ! function_exists( 'huesos_posted_on' ) ) :
/**
 * Display post date/time with link.
 *
 * @since 1.0.0
 */
function huesos_posted_on() {
	?>
	<span class="posted-on">
		<?php
		$html = sprintf(
			'<span class="entry-date"><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_the_permalink() ),
			huesos_get_entry_date()
		);

		/* translators: %s: Publish date */
		printf( __( '<span class="sep">on</span> %s', 'huesos' ), $html );
		?>
	</span>
	<?php
}
endif;

if ( ! function_exists( 'huesos_entry_terms' ) ) :
/**
 * Display terms for a given taxonomy.
 *
 * @since 1.0.0
 *
 * @param array $taxonomies Optional. List of taxonomy objects with labels.
 */
function huesos_entry_terms( $taxonomies = array() ) {
	if ( ! is_singular() || post_password_required() ) {
		return;
	}

	echo huesos_get_entry_terms( $taxonomies );
}
endif;

if ( ! function_exists( 'huesos_get_entry_terms' ) ) :
/**
 * Retrieve terms for a given taxonomy.
 *
 * @since 1.0.0
 *
 * @param array $taxonomies Optional. List of taxonomy objects with labels.
 * @param int|WP_Post $post Optional. Post ID or object. Defaults to the current post.
 */
function huesos_get_entry_terms( $taxonomies = array(), $post = null ) {
	$default = array(
		'category' => __( 'Posted In:', 'huesos' ),
		'post_tag' => __( 'Tagged:', 'huesos' ),
	);

	// Set default taxonomies if empty or not an array.
	if ( ! $taxonomies || ! is_array( $taxonomies ) ) {
		$taxonomies = $default;
	}

	// Allow plugins and themes to override taxonomies and labels.
	$taxonomies = apply_filters( 'huesos_entry_terms_taxonomies', $taxonomies );

	// Return early if the taxonomies are empty or not an array.
	if ( ! $taxonomies || ! is_array( $taxonomies ) ) {
		return;
	}

	$post   = get_post( $post );
	$output = '';

	// Get object taxonomy list to validate taxonomy later on.
	$object_taxonomies = get_object_taxonomies( get_post_type() );

	// Loop through each taxonomy and set up term list html.
	foreach ( (array) $taxonomies as $taxonomy => $label ) {
		// Continue if taxonomy is not in the object taxonomy list.
		if ( ! in_array( $taxonomy, $object_taxonomies ) ) {
			continue;
		}

		// Get term list
		$term_list = get_the_term_list( $post->ID, $taxonomy, '<li>', '</li><li>', '</li>' );

		// Continue if there is not one or more terms in the taxonomy.
		if ( ! $term_list || ! huesos_theme()->template->has_multiple_terms( $taxonomy ) ) {
			continue;
		}

		if ( $label ) {
			$label = sprintf( '<h3 class="term-title">%s</h3>', $label );
		}

		$term_list = sprintf( '<ul class="term-list">%s</ul>', $term_list );

		// Set term list output html.
		$output .= sprintf(
			'<div class="term-group term-group--%1$s">%2$s %3$s</div>',
			esc_attr( $taxonomy ),
			$label,
			$term_list
		);
	}

	// Return if no term lists were created.
	if ( ! $output ) {
		return;
	}

	printf( '<div class="entry-terms">%s</div>', $output );
}
endif;

if ( ! function_exists( 'huesos_content_navigation' ) ) :
/**
 * Display navigation to next/previous posts when applicable.
 *
 * @since 1.0.0
 */
function huesos_content_navigation() {
	if ( is_singular() ) :
		the_post_navigation( array(
			'prev_text' => _x( 'Previous <span class="screen-reader-text">Post: %title</span>', 'Previous post link', 'huesos' ),
			'next_text' => _x( 'Next <span class="screen-reader-text">Post: %title</span>', 'Next post link', 'huesos' ),
		) );
	else :
		the_posts_navigation( array(
			'prev_text' => __( 'Previous', 'huesos' ),
			'next_text' => __( 'Next', 'huesos' ),
		) );
	endif;
}
endif;

if ( ! function_exists( 'huesos_comment_navigation' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since 1.0.0
 */
function huesos_comment_navigation() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() < 2 || ! get_option( 'page_comments' ) ) {
		return;
	}
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'huesos' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'huesos' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'huesos' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div>
	</nav>
	<?php
}
endif;

if ( ! function_exists( 'huesos_page_links' ) ) :
/**
 * Wrapper for wp_link_pages() to help maintain consistent markup.
 *
 * @since 1.0.0
 *
 * @return string
 */
function huesos_page_links() {
	if ( ! is_singular() ) {
		return;
	}

	wp_link_pages( array(
		'before'      => '<nav class="page-links"><span class="page-links-title">' . __( 'Pages', 'huesos' ) . '</span>',
		'after'       => '</nav>',
		'link_before' => '<span class="page-links-number">',
		'link_after'  => '</span>',
		'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'huesos' ) . ' </span>%',
		'separator'   => '<span class="screen-reader-text">, </span>',
	) );
}
endif;

/**
 * Determine if a post has content.
 *
 * Mimics the_content() to closely approximate the output.
 *
 * If a post ID is passed, the post data will be reset afterward.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post_id Optional. Post ID or WP_Post object. Defaults to the current global post.
 * @return bool
 */
function huesos_has_content( $post_id = null ) {
	global $post;

	if ( ! empty( $post_id ) ) {
		$post = get_post( $post_id );
		setup_postdata( $post );
	}

	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace( ']]>', ']]&gt;', $content );

	$has_content = apply_filters( 'huesos_has_content', ! empty( $content ), $post );

	if ( ! empty( $post_id ) ) {
		wp_reset_postdata();
	}

	return (bool) $has_content;
}

/**
 * Determine if a sidebar is being displayed.
 *
 * @since 1.0.0
 *
 * @param mixed $index Optional. Sidebar name, id or number to check.
 * @return bool True if the sidebar is in use and page can display a sidebar, false otherwise.
 */
function huesos_has_sidebar( $index = 'sidebar-1' ) {
	$has_sidebar = is_active_sidebar( $index );

	if ( huesos_is_full_width() ) {
		$has_sidebar = false;
	}

	return (bool) apply_filters( 'huesos_has_sidebar', $has_sidebar, $index );
}

/**
 * Determine if a page's content should be display in full width.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function huesos_is_full_width() {
	$is_full_width = false;

	if ( is_page_template( 'templates/full-width.php' ) ) {
		$is_full_width = true;
	}

	return (bool) apply_filters( 'huesos_is_full_width', $is_full_width );
}

if ( ! function_exists( 'huesos_block_grid' ) ) :
/**
 * Display block grid media objects.
 *
 * Display posts in a block grid on archive type pages.
 *
 * @since 1.0.0
 *
 * @param array $args List of arguments for modifying the query and display.
 */
function huesos_block_grid( $args = array() ) {
	global $wp_query;

	$args = apply_filters( 'huesos_block_grid_args', wp_parse_args( $args, array(
		'classes'       => array(),
		'columns'       => 3,
		'loop'          => $wp_query,
		'template_name' => '',
		'template_slug' => 'templates/parts/block-grid',
	) ) );

	array_unshift( $args['classes'], 'block-grid' );

	$classes   = $args['classes'];
	$columns   = $args['columns'];
	$loop      = $args['loop'];
	$templates = array();

	if ( $columns ) {
		$classes[] = 'block-grid-' . $columns;
	}

	if ( '' !== $args['template_name'] ) {
		$templates[] = $args['template_slug'] . '-' . $args['template_name'] . '.php';
	}

	$templates[] = $args['template_slug'] . '.php';

	do_action( 'huesos_block_grid_before' );
	include( locate_template( $templates ) );
	do_action( 'huesos_block_grid_after' );
	wp_reset_postdata();
}
endif;

if ( ! function_exists( 'huesos_primary_nav_menu_fallback_cb' ) ) :
/**
 * Display primary nav menu fallback message.
 *
 * @since 1.0.0
 */
function huesos_primary_nav_menu_fallback_cb() {
	if ( ! current_user_can( 'edit_theme_options' ) || ! is_customize_preview() ) {
		return;
	}
	?>
	<p class="menu-fallback-notice">
		<?php
		printf( '<a class="button" href="%1$s">%2$s</a>',
			esc_url( admin_url( 'nav-menus.php' ) ),
			esc_html__( 'Create Menu', 'huesos' )
		);
		?>
	</p>
	<?php
}
endif;

if ( ! function_exists( 'huesos_credits' ) ) :
/**
 * Theme credits text.
 *
 * @since 1.0.0
 *
 * @param string $text Text to display.
 * @return string
 */
function huesos_credits() {
	$text = sprintf( __( '%1$s WordPress theme by %2$s.', 'huesos' ),
		'<a href="https://audiotheme.com/view/huesos/">Huesos</a>',
		'<a href="http://www.cedaro.com/">Cedaro</a>'
	);

	echo apply_filters( 'huesos_credits', $text );
}
endif;
