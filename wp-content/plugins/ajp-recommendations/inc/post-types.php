<?php
/**
 * Defines the Recommendation post type and related taxonomies.
 *
 * @package WordPress
 * @subpackage AJPRecommendations
 * @since 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists('ajprecommendations_post_types') ) {

  // Register Custom Post Type
  function ajprecommendations_post_types() {

    $labels = array(
      'name'                  => _x( 'Companies', 'Post Type General Name', 'ajprecommendations' ),
      'singular_name'         => _x( 'Company', 'Post Type Singular Name', 'ajprecommendations' ),
      'menu_name'             => __( 'Companies', 'ajprecommendations' ),
      'name_admin_bar'        => __( 'Company', 'ajprecommendations' ),
      'archives'              => __( 'Company Archives', 'ajprecommendations' ),
      'attributes'            => __( 'Company Attributes', 'ajprecommendations' ),
      'parent_item_colon'     => __( 'Parent Company:', 'ajprecommendations' ),
      'all_items'             => __( 'All Companies', 'ajprecommendations' ),
      'add_new_item'          => __( 'Add New Company', 'ajprecommendations' ),
      'add_new'               => __( 'Add New', 'ajprecommendations' ),
      'new_item'              => __( 'New Company', 'ajprecommendations' ),
      'edit_item'             => __( 'Edit Company', 'ajprecommendations' ),
      'update_item'           => __( 'Update Company', 'ajprecommendations' ),
      'view_item'             => __( 'View Company', 'ajprecommendations' ),
      'view_items'            => __( 'View Companies', 'ajprecommendations' ),
      'search_items'          => __( 'Search Company', 'ajprecommendations' ),
      'not_found'             => __( 'Not found', 'ajprecommendations' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'ajprecommendations' ),
      'featured_image'        => __( 'Featured Image', 'ajprecommendations' ),
      'set_featured_image'    => __( 'Set featured image', 'ajprecommendations' ),
      'remove_featured_image' => __( 'Remove featured image', 'ajprecommendations' ),
      'use_featured_image'    => __( 'Use as featured image', 'ajprecommendations' ),
      'insert_into_item'      => __( 'Insert into company', 'ajprecommendations' ),
      'uploaded_to_this_item' => __( 'Uploaded to this recommendation', 'ajprecommendations' ),
      'items_list'            => __( 'Companies list', 'ajprecommendations' ),
      'items_list_navigation' => __( 'Companies list navigation', 'ajprecommendations' ),
      'filter_items_list'     => __( 'Filter companies list', 'ajprecommendations' ),
    );
    $rewrite = array(
      'slug'                  => 'recommendations',
      'with_front'            => true,
      'pages'                 => true,
      'feeds'                 => true,
    );
    $args = array(
      'label'                 => __( 'Recommendation', 'ajprecommendations' ),
      'description'           => __( 'AJP member recommendations for local businesses.', 'ajprecommendations' ),
      'labels'                => $labels,
      'show_in_rest'          => true,
      'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt' ),
      'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-thumbs-up',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => 'recommendations',
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'rewrite'               => $rewrite,
      'capability_type'       => 'post',
    );
    register_post_type( 'ajp_recommendation', $args );

  }
  add_action( 'init', 'ajprecommendations_post_types', 0 );
}
