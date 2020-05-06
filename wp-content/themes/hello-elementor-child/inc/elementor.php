<?php
/**
 * Elementor functionality
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

// topics_filter Elementor query ID - Displays forum topics.
add_action( 'elementor/query/topics_filter', function( $query ) {
  $query->set( 'post_type', 'topic' );
});
