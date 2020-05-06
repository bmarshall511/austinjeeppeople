<?php
/**
 * Shortcodes
 *
 * @package WordPress
 * @subpackage AJPevents
 * @since 1.0.0
 */

// Event date
function ajpevents_shortcode_date() {
  $date = get_field( 'ajp_date' );

  if ( empty( $date ) ) { return; }

  $start = date( 'm/d/Y', strtotime( $date['start'] ) );
  $end   = ! empty( $date['end'] ) ? date( 'm/d/Y', strtotime( $date['end'] ) ) : false;

  if ( $start == $end ) {
    // Same day event
    echo date( 'l, F j, Y', strtotime( $date['start'] ) )
      . __( ' at ', 'ajpevents' )
      . date( 'h:i A', strtotime( $date['start'] ) )
      . ' - '
      . date( 'h:i A', strtotime( $date['end'] ) );
  } elseif ( ! $end ) {
    // No end time
    echo date( 'l, F j, Y', strtotime( $date['start'] ) )
      . __ ( ' at ' )
      . date( 'h:i A', strtotime( $date['start'] ) );
  } else {
    // Starts & ends different dates
    echo date( 'l, F j h:i A', strtotime( $date['start'] ) )
      . __( ' - ', 'ajpevents' )
      . date( 'F j, Y h:i A', strtotime( $date['end'] ) );
  }
}
add_shortcode( 'ajp_event_date', 'ajpevents_shortcode_date' );
