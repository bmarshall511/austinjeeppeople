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

// [complete_address]
function hello_elementor_child_shortcode_complete_address() {
  $address = get_field( 'ajp_address' );

  if ( ! $address ) { return; }

  $full_address = false;

  if ( ! empty( $address['address_1'] ) ) {
    $full_address = $address['address_1'];
  }

  if ( ! empty( $address['address_2'] ) ) {
    if ( $full_address ) { $full_address .= ' '; }
    $full_address .= $address['address_2'];
  }

  if ( ! empty( $address['city'] ) ) {
    if ( $full_address ) { $full_address .= ', '; }
    $full_address .= $address['city'];
  }

  if ( ! empty( $address['state'] ) ) {
    if ( $full_address ) { $full_address .= ', '; }
    $full_address .= $address['state'];
  }

  if ( ! empty( $address['zip'] ) ) {
    if ( $full_address ) { $full_address .= ' '; }
    $full_address .= $address['zip'];
  }

  return $full_address;
}
add_shortcode( 'complete_address', 'hello_elementor_child_shortcode_complete_address' );
