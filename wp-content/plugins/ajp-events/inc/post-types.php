<?php
/**
 * Defines the Events post type and related taxonomies.
 *
 * @package WordPress
 * @subpackage AJPevents
 * @since 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists('ajpevents_post_types') ) {

  // Register Custom Post Type
  function ajpevents_post_types() {

    $labels = array(
      'name'                  => _x( 'Events', 'Post Type General Name', 'ajpevents' ),
      'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'ajpevents' ),
      'menu_name'             => __( 'Events', 'ajpevents' ),
      'name_admin_bar'        => __( 'Company', 'ajpevents' ),
      'archives'              => __( 'Event Archives', 'ajpevents' ),
      'attributes'            => __( 'Event Attributes', 'ajpevents' ),
      'parent_item_colon'     => __( 'Parent Event:', 'ajpevents' ),
      'all_items'             => __( 'All Events', 'ajpevents' ),
      'add_new_item'          => __( 'Add New Event', 'ajpevents' ),
      'add_new'               => __( 'Add New', 'ajpevents' ),
      'new_item'              => __( 'New Event', 'ajpevents' ),
      'edit_item'             => __( 'Edit Event', 'ajpevents' ),
      'update_item'           => __( 'Update Event', 'ajpevents' ),
      'view_item'             => __( 'View Event', 'ajpevents' ),
      'view_items'            => __( 'View Events', 'ajpevents' ),
      'search_items'          => __( 'Search Event', 'ajpevents' ),
      'not_found'             => __( 'Not found', 'ajpevents' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'ajpevents' ),
      'featured_image'        => __( 'Featured Image', 'ajpevents' ),
      'set_featured_image'    => __( 'Set featured image', 'ajpevents' ),
      'remove_featured_image' => __( 'Remove featured image', 'ajpevents' ),
      'use_featured_image'    => __( 'Use as featured image', 'ajpevents' ),
      'insert_into_item'      => __( 'Insert into event', 'ajpevents' ),
      'uploaded_to_this_item' => __( 'Uploaded to this event', 'ajpevents' ),
      'items_list'            => __( 'Events list', 'ajpevents' ),
      'items_list_navigation' => __( 'Events list navigation', 'ajpevents' ),
      'filter_items_list'     => __( 'Filter events list', 'ajpevents' ),
    );
    $rewrite = array(
      'slug'                  => 'events',
      'with_front'            => true,
      'pages'                 => true,
      'feeds'                 => true,
    );
    $args = array(
      'label'                 => __( 'Event', 'ajpevents' ),
      'description'           => __( 'AJP member events.', 'ajpevents' ),
      'labels'                => $labels,
      'show_in_rest'          => true,
      'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt' ),
      'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-tickets-alt',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => 'events',
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'rewrite'               => $rewrite,
      'capability_type'       => 'post',
    );
    register_post_type( 'ajp_event', $args );

  }
  add_action( 'init', 'ajpevents_post_types', 0 );
}
