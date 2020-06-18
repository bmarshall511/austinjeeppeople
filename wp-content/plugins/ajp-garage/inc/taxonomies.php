<?php
/**
 * Defines the vehicle taxonomies.
 *
 * @package WordPress
 * @subpackage AJPGarage
 * @since 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'ajp_garage_taxonomies' ) ) {

// Register Custom Taxonomy
function ajp_garage_taxonomies() {

  // Make & model
	$labels = array(
		'name'                       => _x( 'Makes & Models', 'Taxonomy General Name', 'ajp_garage' ),
		'singular_name'              => _x( 'Make & Model', 'Taxonomy Singular Name', 'ajp_garage' ),
		'menu_name'                  => __( 'Make & Model', 'ajp_garage' ),
		'all_items'                  => __( 'All Makes/Models', 'ajp_garage' ),
		'parent_item'                => __( 'Parent Make', 'ajp_garage' ),
		'parent_item_colon'          => __( 'Parent Make:', 'ajp_garage' ),
		'new_item_name'              => __( 'New Make/Model Name', 'ajp_garage' ),
		'add_new_item'               => __( 'Add New Make/Model', 'ajp_garage' ),
		'edit_item'                  => __( 'Edit Make/Model', 'ajp_garage' ),
		'update_item'                => __( 'Update Make/Model', 'ajp_garage' ),
		'view_item'                  => __( 'View Make/Model', 'ajp_garage' ),
		'separate_items_with_commas' => __( 'Separate makes/models with commas', 'ajp_garage' ),
		'add_or_remove_items'        => __( 'Add or remove makes/models', 'ajp_garage' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ajp_garage' ),
		'popular_items'              => __( 'Popular Makes/Models', 'ajp_garage' ),
		'search_items'               => __( 'Search Makes/Models', 'ajp_garage' ),
		'not_found'                  => __( 'Not Found', 'ajp_garage' ),
		'no_terms'                   => __( 'No makes/models', 'ajp_garage' ),
		'items_list'                 => __( 'Makes/Models list', 'ajp_garage' ),
		'items_list_navigation'      => __( 'Makes/Models list navigation', 'ajp_garage' ),
	);
	$rewrite = array(
		'slug'                       => 'garage/browse',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
    'show_in_rest'               => true
	);
  register_taxonomy( 'ajp_make_model', array( 'ajp_vehicle' ), $args );

  // Features
  $labels = array(
		'name'                       => _x( 'Features', 'Taxonomy General Name', 'ajp_garage' ),
		'singular_name'              => _x( 'Feature', 'Taxonomy Singular Name', 'ajp_garage' ),
		'menu_name'                  => __( 'Features', 'ajp_garage' ),
		'all_items'                  => __( 'All Features', 'ajp_garage' ),
		'parent_item'                => __( 'Parent Feature', 'ajp_garage' ),
		'parent_item_colon'          => __( 'Parent Feature:', 'ajp_garage' ),
		'new_item_name'              => __( 'New Feature Name', 'ajp_garage' ),
		'add_new_item'               => __( 'Add New Feature', 'ajp_garage' ),
		'edit_item'                  => __( 'Edit Feature', 'ajp_garage' ),
		'update_item'                => __( 'Update Feature', 'ajp_garage' ),
		'view_item'                  => __( 'View Feature', 'ajp_garage' ),
		'separate_items_with_commas' => __( 'Separate features with commas', 'ajp_garage' ),
		'add_or_remove_items'        => __( 'Add or remove features', 'ajp_garage' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ajp_garage' ),
		'popular_items'              => __( 'Popular Features', 'ajp_garage' ),
		'search_items'               => __( 'Search Features', 'ajp_garage' ),
		'not_found'                  => __( 'Not Found', 'ajp_garage' ),
		'no_terms'                   => __( 'No features', 'ajp_garage' ),
		'items_list'                 => __( 'Features list', 'ajp_garage' ),
		'items_list_navigation'      => __( 'Features list navigation', 'ajp_garage' ),
	);
	$rewrite = array(
		'slug'                       => 'garage/browse/features',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
    'show_in_rest'               => true
	);
	register_taxonomy( 'ajp_features', array( 'ajp_vehicle' ), $args );

}
add_action( 'init', 'ajp_garage_taxonomies', 0 );

}
