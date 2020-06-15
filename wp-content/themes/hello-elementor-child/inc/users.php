<?php
/**
 * User functionality
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

/**
 * Custom Author Base
 *
 * @return void
 */
function hello_elementor_child_custom_author_base() {
  global $wp_rewrite;

  $wp_rewrite->author_base = 'members';
}
add_action( 'init', 'hello_elementor_child_custom_author_base' );
