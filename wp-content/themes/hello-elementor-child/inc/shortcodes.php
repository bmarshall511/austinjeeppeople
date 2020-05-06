<?php
/**
 * Theme shortcodes
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

 // [the_content]
function hello_elementor_child_shortcode_the_content( $atts ) {
	the_content();
}
add_shortcode( 'the_content', 'hello_elementor_child_shortcode_the_content' );
