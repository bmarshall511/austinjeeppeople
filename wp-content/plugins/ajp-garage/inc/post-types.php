<?php
/**
 * Defines the vehicle post type.
 *
 * @package WordPress
 * @subpackage AJPGarage
 * @since 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists('ajpgarage_post_types') ) {

  // Register Custom Post Type
  function ajpgarage_post_types() {

    $labels = array(
      'name'                  => _x( 'Vehicles', 'Post Type General Name', 'ajpgarage' ),
      'singular_name'         => _x( 'Vehicle', 'Post Type Singular Name', 'ajpgarage' ),
      'menu_name'             => __( 'Vehicles', 'ajpgarage' ),
      'name_admin_bar'        => __( 'Vehicle', 'ajpgarage' ),
      'archives'              => __( 'Vehicle Archives', 'ajpgarage' ),
      'attributes'            => __( 'Vehicle Attributes', 'ajpgarage' ),
      'parent_item_colon'     => __( 'Parent Vehicle:', 'ajpgarage' ),
      'all_items'             => __( 'All Vehicles', 'ajpgarage' ),
      'add_new_item'          => __( 'Add New Vehicle', 'ajpgarage' ),
      'add_new'               => __( 'Add New', 'ajpgarage' ),
      'new_item'              => __( 'New Vehicle', 'ajpgarage' ),
      'edit_item'             => __( 'Edit Vehicle', 'ajpgarage' ),
      'update_item'           => __( 'Update Vehicle', 'ajpgarage' ),
      'view_item'             => __( 'View Vehicle', 'ajpgarage' ),
      'view_items'            => __( 'View Vehicles', 'ajpgarage' ),
      'search_items'          => __( 'Search Vehicle', 'ajpgarage' ),
      'not_found'             => __( 'Not found', 'ajpgarage' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'ajpgarage' ),
      'featured_image'        => __( 'Featured Image', 'ajpgarage' ),
      'set_featured_image'    => __( 'Set featured image', 'ajpgarage' ),
      'remove_featured_image' => __( 'Remove featured image', 'ajpgarage' ),
      'use_featured_image'    => __( 'Use as featured image', 'ajpgarage' ),
      'insert_into_item'      => __( 'Insert into vehicle', 'ajpgarage' ),
      'uploaded_to_this_item' => __( 'Uploaded to this vehicle', 'ajpgarage' ),
      'items_list'            => __( 'Vehicles list', 'ajpgarage' ),
      'items_list_navigation' => __( 'Vehicles list navigation', 'ajpgarage' ),
      'filter_items_list'     => __( 'Filter vehicles list', 'ajpgarage' ),
    );
    $rewrite = array(
      'slug'                  => 'garage',
      'with_front'            => true,
      'pages'                 => true,
      'feeds'                 => true,
    );
    $args = array(
      'label'                 => __( 'Vehicle', 'ajpgarage' ),
      'description'           => __( 'AJP member vehicles.', 'ajpgarage' ),
      'labels'                => $labels,
      'show_in_rest'          => true,
      'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'comments' ),
      'taxonomies'            => [],
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-image-filter',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => 'garage',
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'rewrite'               => $rewrite,
      'capability_type'       => 'post',
    );
    register_post_type( 'ajp_vehicle', $args );

  }
  add_action( 'init', 'ajpgarage_post_types', 0 );
}
