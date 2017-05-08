<?php

/**
 * Mai Theme.
 *
 * @package MaiTheme
 * @author  Mike Hemberger
 * @license GPL-2.0+
 * @link    https://bizbudding.com
 */

/**
 * Add body class to enabled specific settings.
 *
 * @since   1.0.0
 *
 * @param   array  The body classes.
 *
 * @return  array  The modified classes.
 */
add_filter( 'body_class', 'mai_do_settings_body_classes' );
function mai_do_settings_body_classes( $classes ) {
	/**
	 * Add sticky header styling
	 * Fixed header currently only works with standard mobile menu
	 *
	 * DO NOT USE WITH SIDE MENU!
	 */
	if ( mai_is_sticky_header_enabled() ) {
		$classes[] = 'sticky-header';
	}

	if ( mai_is_shrink_header_enabled() ) {
		$classes[] = 'shrink-header';
	}

	/**
	 * Use a side mobile menu in place of the standard the mobile menu
	 */
	if ( mai_is_side_menu_enabled() ) {
		$classes[] = 'side-menu';
	}

	return $classes;
}

/**
 * Add boxed class to all entries and WooCommerce products.
 *
 * @since   1.0.0
 *
 * @return  array  The modified classes
 */
// add_filter( 'post_class', 'mai_do_boxed_content_class' );
// add_filter( 'product_cat_class', 'mai_do_boxed_content_class' );
function mai_do_boxed_content_class( $classes ) {
	if ( ! is_main_query() ) {
		return $classes;
	}
    if ( mai_is_boxed_content_enabled() ) {
    	$classes[] = 'boxed';
    }
    return $classes;
}

/**
 * Add boxed class to all elements affected by box styling.
 *
 * @since   1.0.0
 *
 * @return  array  The modified classes
 */
// add_filter( 'genesis_attr_sidebar-primary', 'mai_do_boxed_content_attributes' );
// add_filter( 'genesis_attr_sidebar-secondary', 'mai_do_boxed_content_attributes' );
// add_filter( 'genesis_attr_author-box', 'mai_do_boxed_content_attributes' );
// add_filter( 'genesis_attr_adjacent-entry-pagination', 'mai_do_boxed_content_attributes' );
function mai_do_boxed_content_attributes( $attributes ) {
    if ( mai_is_boxed_content_enabled() ) {
    	$attributes['class'] .= ' boxed';
    }
    return $attributes;
}

// add_action( 'wp_enqueue_scripts', 'mai_css' );
/**
 * Checks the settings for the link color color, accent color, and header.
 * If any of these value are set the appropriate CSS is output.
 *
 * @since 1.0.0
 */
function mai_css() {

	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

	$color_accent = get_theme_mod( 'mai_accent_color', mai_customizer_get_default_accent_color() );

	// $opts = apply_filters( 'mai_images', array( '1', '3', '5', '7' ) );

	$settings = array();

	foreach( $opts as $opt ) {
		$settings[$opt]['image'] = preg_replace( '/^https?:/', '', get_option( $opt .'-mai-image', sprintf( '%s/images/bg-%s.jpg', MAITHEME_ENGINE_PLUGIN_PLUGIN_DIR, $opt ) ) );
	}

	$css = '';

	foreach ( $settings as $section => $value ) {

		$background = $value['image'] ? sprintf( 'background-image: url(%s);', $value['image'] ) : '';

		if ( is_front_page() ) {
			$css .= ( ! empty( $section ) && ! empty( $background ) ) ? sprintf( '.front-page-%s { %s }', $section, $background ) : '';
		}

	}

	$css .= ( mai_customizer_get_default_accent_color() !== $color_accent ) ? sprintf( '

		a,
		.entry-title a:focus,
		.entry-title a:hover,
		.featured-content .entry-meta a:focus,
		.featured-content .entry-meta a:hover,
		.front-page .genesis-nav-menu a:focus,
		.front-page .genesis-nav-menu a:hover,
		.front-page .offscreen-content-icon button:focus,
		.front-page .offscreen-content-icon button:hover,
		.front-page .white .genesis-nav-menu a:focus,
		.front-page .white .genesis-nav-menu a:hover,
		.genesis-nav-menu a:focus,
		.genesis-nav-menu a:hover,
		.genesis-nav-menu .current-menu-item > a,
		.genesis-nav-menu .sub-menu .current-menu-item > a:focus,
		.genesis-nav-menu .sub-menu .current-menu-item > a:hover,
		.genesis-responsive-menu .genesis-nav-menu a:focus,
		.genesis-responsive-menu .genesis-nav-menu a:hover,
		.menu-toggle:focus,
		.menu-toggle:hover,
		.offscreen-content button:hover,
		.offscreen-content-icon button:hover,
		.site-footer a:focus,
		.site-footer a:hover,
		.sub-menu-toggle:focus,
		.sub-menu-toggle:hover {
			color: %1$s;
		}

		button,
		input[type="button"],
		input[type="reset"],
		input[type="select"],
		input[type="submit"],
		.button,
		.enews-widget input:hover[type="submit"],
		.front-page-1 a.button,
		.front-page-3 a.button,
		.front-page-5 a.button,
		.front-page-7 a.button,
		.footer-widgets .button:hover {
			background-color: %1$s;
			color: %2$s;
		}

		', $color_accent, mai_color_contrast( $color_accent ) ) : '';

	if ( $css ) {
		wp_add_inline_style( $handle, $css );
	}

}