<?php
/**
 * Defines the discount post type and related taxonomies.
 *
 * @package WordPress
 * @subpackage AJPEvents
 * @since 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists('ajpdiscounts_post_types') ) {

  // Register Custom Post Type
  function ajpdiscounts_post_types() {

    $labels = array(
      'name'                  => _x( 'Discounts', 'Post Type General Name', 'ajpdisounts' ),
      'singular_name'         => _x( 'Discount', 'Post Type Singular Name', 'ajpdisounts' ),
      'menu_name'             => __( 'Discounts', 'ajpdisounts' ),
      'name_admin_bar'        => __( 'Discount', 'ajpdisounts' ),
      'archives'              => __( 'Discount Archives', 'ajpdisounts' ),
      'attributes'            => __( 'Discount Attributes', 'ajpdisounts' ),
      'parent_item_colon'     => __( 'Parent Discount:', 'ajpdisounts' ),
      'all_items'             => __( 'All Discounts', 'ajpdisounts' ),
      'add_new_item'          => __( 'Add New Discount', 'ajpdisounts' ),
      'add_new'               => __( 'Add New', 'ajpdisounts' ),
      'new_item'              => __( 'New Discount', 'ajpdisounts' ),
      'edit_item'             => __( 'Edit Discount', 'ajpdisounts' ),
      'update_item'           => __( 'Update Discount', 'ajpdisounts' ),
      'view_item'             => __( 'View Discount', 'ajpdisounts' ),
      'view_items'            => __( 'View Discounts', 'ajpdisounts' ),
      'search_items'          => __( 'Search Discount', 'ajpdisounts' ),
      'not_found'             => __( 'Not found', 'ajpdisounts' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'ajpdisounts' ),
      'featured_image'        => __( 'Featured Image', 'ajpdisounts' ),
      'set_featured_image'    => __( 'Set featured image', 'ajpdisounts' ),
      'remove_featured_image' => __( 'Remove featured image', 'ajpdisounts' ),
      'use_featured_image'    => __( 'Use as featured image', 'ajpdisounts' ),
      'insert_into_item'      => __( 'Insert into discount', 'ajpdisounts' ),
      'uploaded_to_this_item' => __( 'Uploaded to this discount', 'ajpdisounts' ),
      'items_list'            => __( 'Discounts list', 'ajpdisounts' ),
      'items_list_navigation' => __( 'Discounts list navigation', 'ajpdisounts' ),
      'filter_items_list'     => __( 'Filter discounts list', 'ajpdisounts' ),
    );
    $rewrite = array(
      'slug'                  => 'discounts',
      'with_front'            => true,
      'pages'                 => true,
      'feeds'                 => true,
    );
    $args = array(
      'label'                 => __( 'Discount', 'ajpdisounts' ),
      'description'           => __( 'AJP member discounts from local businesses.', 'ajpdisounts' ),
      'labels'                => $labels,
      'show_in_rest'          => true,
      'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt' ),
      'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-cart',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => 'discounts',
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'rewrite'               => $rewrite,
      'capability_type'       => 'post',
    );
    register_post_type( 'ajp_discount', $args );

  }
  add_action( 'init', 'ajpdiscounts_post_types', 0 );
}
