<?php
/**
 * Theme helpers
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

function hello_elementor_child_helper_calculate_age( $dob ) {
  $date = new DateTime( $dob );
  $now  = new DateTime();

  return $now->diff( $date )->y;
}
