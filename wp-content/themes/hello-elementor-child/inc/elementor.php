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

// comments_most_filter Elementor query ID - Displays posts by most comments.
add_action( 'elementor/query/comments_most_filter', function( $query ) {
  $query->set( 'orderby', 'comment_count' );
});

// events_start_filter Elementor query ID - Displays events by start date.
add_action( 'elementor/query/events_start_filter', function( $query ) {
  $query->set( 'meta_key', 'ajp_date_start' );
  $query->set( 'orderby', 'meta_value' );
  $query->set( 'order', 'ASC' );
  $query->set( 'meta_query', [
    [
      'key'     => 'ajp_date_start',
      'value'   => date( 'Y-m-d H:i:s' ),
      'compare' => '>=',
      'type'    => 'CHAR'
    ]
  ]);
});

// comments_last_filter Elementor query ID - Displays posts by lastest comments.
add_action( 'elementor/query/comments_last_filter', function( $query ) {
  $comments_query = new WP_Comment_Query;
  $comments       = $comments_query->query( array(   'number' => '100'  ) );

  if ( $comments ) {
    foreach ( $comments as $comment ) {
      $comment_utf = strtotime($comment->comment_date);
      $latest_comments[$comment->comment_post_ID] = $comment_utf;
    }

    // Sort the array by date
    arsort( $latest_comments );
    foreach( $latest_comments as $key => $value ) {
      $posts_ordered[] = $key;
    }

    $query->set( 'post__in', $posts_ordered );
    $query->set( 'orderby', 'post__in' );
  }
});
