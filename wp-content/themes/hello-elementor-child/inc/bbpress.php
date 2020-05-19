<?php
/**
 * bbPress functionality
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

/**
 * Register & enqueue theme CSS & JS files
 *
 * @since 1.0.0
 */
function hello_elementor_child_bbpress_scripts() {
  if ( ! is_bbpress() ) { return; }

  // CSS
  wp_enqueue_style( 'hello-elementor-child-bbpress-core', get_stylesheet_directory_uri() . '/assets/css/add-ons/wordpress/bbpress.css', [], wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_bbpress_scripts', PHP_INT_MAX );
