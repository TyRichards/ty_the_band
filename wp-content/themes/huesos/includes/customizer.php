<?php
/**
 * Customizer
 *
 * @package Huesos
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title in the Customizer.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function huesos_customize_register( $wp_customize ) {
	$color_scheme = huesos_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_setting( 'huesos_text_scheme', array(
		'default'           => 'dark-text-scheme',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'huesos_sanitize_text_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_setting( 'huesos_background_color', array(
		'default'           => $color_scheme['background_color'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'huesos_background_color', array(
		'label'    => __( 'Background Color', 'huesos' ),
		'section'  => 'colors',
		'settings' => 'huesos_background_color',
	) ) );

	$wp_customize->add_setting( 'huesos_accent_color', array(
		'default'           => $color_scheme['accent_color'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'huesos_accent_color', array(
		'label'    => __( 'Accent Color', 'huesos' ),
		'section'  => 'colors',
		'settings' => 'huesos_accent_color',
	) ) );

	$wp_customize->add_section( 'huesos_background_image', array(
		'title'    => __( 'Background Image', 'huesos' ),
		'priority' => 80,
	) );

	$wp_customize->add_setting( 'huesos_background_image', array(
		'capability'        => 'manage_options',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'huesos_background_image', array(
		'label'    => __( 'Background Image', 'huesos' ),
		'section'  => 'huesos_background_image',
		'settings' => 'huesos_background_image',
	) ) );

	$wp_customize->add_section( 'theme_options', array(
		'title'    => __( 'Theme', 'huesos' ),
		'priority' => 120,
	) );

	$wp_customize->add_setting( 'huesos_player_scheme', array(
		'default'           => 'dark-player-scheme',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'huesos_sanitize_player_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_setting( 'disable_player', array(
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'huesos_customize_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'huesos_disable_player', array(
		'label'    => __( 'Disable the site-wide audio player?', 'huesos' ),
		'section'  => 'theme_options',
		'settings' => 'disable_player',
		'type'     => 'checkbox',
	) );
}
add_action( 'customize_register', 'huesos_customize_register' );

/**
 * Enqueue assets to load in the Customizer preview.
 *
 * @since 1.0.0
 */
function huesos_customize_enqueue_assets() {
	wp_enqueue_script(
		'huesos-customize-preview',
		get_template_directory_uri() . '/assets/js/customize-preview.js',
		array( 'customize-preview', 'underscore', 'wp-util' ),
		'20141221',
		true
	);
}
add_action( 'customize_preview_init', 'huesos_customize_enqueue_assets' );

/**
 * Enqueue scripts for the Customizer.
 *
 * @since 1.0.0
 */
function huesos_customize_enqueue_controls_assets() {
	wp_enqueue_script(
		'huesos-customize-controls',
		get_template_directory_uri() . '/assets/js/customize-controls.js',
		array( 'customize-controls', 'underscore' ),
		'20141221',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'huesos_customize_enqueue_controls_assets' );

/**
 * Sanitization callback for checkbox controls in the Customizer.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string 1 if checked, empty string otherwise.
 */
function huesos_customize_sanitize_checkbox( $value ) {
	return empty( $value ) || ! $value ? '' : '1';
}

/**
 * Sanitization callback for text scheme.
 *
 * @since 1.0.0
 *
 * @param string $value Scheme name value.
 * @return string
 */
function huesos_sanitize_text_scheme( $value ) {
	if ( 'light-text-scheme' !== $value ) {
		$value = 'dark-text-scheme';
	}

	return $value;
}

/**
 * Sanitization callback for player scheme.
 *
 * @since 1.0.0
 *
 * @param string $value Scheme name value.
 * @return string
 */
function huesos_sanitize_player_scheme( $value ) {
	if ( 'light-player-scheme' !== $value ) {
		$value = 'dark-player-scheme';
	}

	return $value;
}

/**
 * Register default color scheme values.
 *
 * @since 1.0.0
 */
function huesos_get_color_scheme() {
	return array(
		'accent_color'     => '#e52e2e',
		'background_color' => '#ffffff',
	);
}

/**
 * Print an Underscores template with CSS to generate based on options
 * selected in the Customizer.
 *
 * @since 1.0.0
 */
function huesos_customize_styles_template() {
	if ( ! is_customize_preview() ) {
		return;
	}

	$colors = array(
		'accent_color'     => '{{ data.accentColor }}',
		'background_color' => '{{ data.backgroundColor }}',
	);

	printf(
		'<script type="text/html" id="tmpl-huesos-customizer-styles">%s</script>',
		huesos_get_custom_css( $colors )
	);
}
add_action( 'wp_footer', 'huesos_customize_styles_template' );

/**
 * Enqueue front-end CSS for custom colors.
 *
 * @since 1.0.0
 *
 * @see WP_Styles::print_inline_style()
 */
function huesos_customize_add_inline_css() {
	$css = preg_replace( '/[\s]{2,}/', '', huesos_get_custom_css() );
	printf( "<style id='huesos-custom-css' type='text/css'>\n%s\n</style>\n", $css );
}
add_action( 'wp_head', 'huesos_customize_add_inline_css', 11 );

/**
 * Print a background overlay element.
 *
 * @since 1.0.0
 */
function huesos_customize_background_image() {
	$image_url = get_theme_mod( 'huesos_background_image', '' );

	if ( empty( $image_url ) && ! is_customize_preview() ) {
		return;
	}

	printf( '<div class="huesos-background-overlay" style="background-image: url(\'%s\')"></div>', esc_url( $image_url ) );
}
add_action( 'wp_footer', 'huesos_customize_background_image' );

/**
 * Retrieve CSS rules for implementing custom colors.
 *
 * @since 1.0.0
 *
 * @param array $colors Optional. An array of colors.
 * @return string
 */
function huesos_get_custom_css( $colors = array() ) {
	$css      = '';
	$defaults = huesos_get_color_scheme();

	$colors = wp_parse_args( $colors, array(
		'accent_color'     => get_theme_mod( 'huesos_accent_color', $defaults['accent_color'] ),
		'background_color' => get_theme_mod( 'huesos_background_color', $defaults['background_color'] ),
	) );

	$css .= huesos_get_accent_color_css( $colors['accent_color'] );

	if ( $colors['background_color'] !== $defaults['background_color'] || is_customize_preview() ) {
		$css .= huesos_get_background_color_css( $colors['background_color'] );
	}

	return $css;
}

/**
 * Get background color CSS
 *
 * @since 1.0.0
 *
 * @param  string $color Hex color.
 * @return string
 */
function huesos_get_background_color_css( $color ) {
	$css = <<<CSS
	body {
		background-color: {$color};
	}
CSS;

	if ( ! get_theme_mod( 'huesos_background_image', '' ) || is_customize_preview() ) {
		$css .= <<<CSS
	.no-background-image button,
	.no-background-image button:hover,
	.no-background-image button:focus,
	.no-background-image input[type="button"],
	.no-background-image input[type="button"]:hover,
	.no-background-image input[type="button"]:focus,
	.no-background-image input[type="reset"],
	.no-background-image input[type="reset"]:hover,
	.no-background-image input[type="reset"]:focus,
	.no-background-image input[type="submit"],
	.no-background-image input[type="submit"]:hover,
	.no-background-image input[type="submit"]:focus,
	.no-background-image .button,
	.no-background-image .button:hover,
	.no-background-image .button:focus {
		color: {$color};
	}
CSS;
	}

	$css .= <<<CSS
	@media only screen and ( min-width: 1024px ) {
		.huesos-player,
		.light-text-scheme .huesos-player,
		.huesos-player .volume-slider {
			background-color: {$color};
		}

		.huesos-player .volume-slider {
			border-color: {$color};
		}
	}

CSS;

	return $css;
}

/**
 * Get accent color CSS
 *
 * @since 1.0.0
 *
 * @param  string $color Hex color.
 * @return string
 */
function huesos_get_accent_color_css( $color ) {
	$css = <<<CSS
	.gig-card:hover,
	.edd-required-indicator,
	.comments-area .required,
	.page-links > span,
	.post-navigation a:hover,
	.posts-navigation a:hover,
	.post-navigation a:focus,
	.posts-navigation a:focus,
	.site-navigation ul a:hover,
	.site-navigation ul a:focus,
	.social-navigation a:hover,
	.huesos-mejs-container.mejs-container button:focus,
	.huesos-mejs-container.mejs-container button:hover {
		color: {$color};
	}

	.infinite-loader:before,
	.fluid-width-video-wrapper:before {
		border-color: {$color};
	}
CSS;

	return $css;
}
