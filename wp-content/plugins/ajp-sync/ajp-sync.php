<?php
/**
 * AJP Sync WordPress Plugin
 *
 * @package           WordPress
 * @subpackage        AJPSync
 * @author            Ben Marshall
 * @copyright         2019 Ben Marshall
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       AJP Sync
 * Plugin URI:        https://austinjeeppeople.com
 * Description:       Handles syncing data from v1 of the site to v2.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ben Marshall
 * Author URI:        https://benmarshall.me
 * Text Domain:       ajpsync
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ajp_sync_v1_sync_vehicles() {
  if ( empty( $_REQUEST['sync_vehicles'] ) || 'sync' != $_REQUEST['sync_vehicles'] ) { return false; }

  $test_run = false;
  $limit    = ! empty( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : 1;

  $log = [];

  try {
    $conn = new PDO('mysql:host=' . AJP_V1_DBHOST . ';dbname=' . AJP_V1_DBNAME, AJP_V1_DBUSER, AJP_V1_DBPASS );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'posts WHERE post_type = :post_type AND post_status = :post_status' );
    $stmt->execute([
      'post_type'   => 'wpcm_vehicle',
      'post_status' => 'publish'
    ]);

    $vehicles = $stmt->fetchAll();

    $log[] = 'Found ' . count( $vehicles ) . ' vehicles in the v1 database.';

    $count = 0;
    foreach( $vehicles as $key => $vehicle ) { $count ++;
      if ( $count > $limit ) { break; }

      $postarr = [
        'post_title'        => $vehicle['post_title'],
        'post_date'         => $vehicle['post_date'],
        'post_date_gmt'     => $vehicle['post_date_gmt'],
        'post_content'      => $vehicle['post_content'],
        'post_excerpt'      => $vehicle['post_excerpt'],
        'post_status'       => 'publish',
        'post_type'         => 'ajp_vehicle',
        'comment_status'    => 'open',
        'ping_status'       => 'closed',
        'post_name'         => $vehicle['post_name'],
        'post_modified'     => $vehicle['post_modified'],
        'post_modified_gmt' => $vehicle['post_modified_gmt'],
        'tax_input'         => []
      ];

      // Step 1. Check if the user exists in the database
      $user_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'users WHERE ID = :ID LIMIT 1' );
      $user_stmt->execute([ 'ID' => $vehicle['post_author'] ]);
      $user = $user_stmt->fetchAll();
      if ( ! $user ) {
        $log[] = 'User could not be found in the v1 database: ' . $vehicle['post_author'];
        continue;
      }

      $user = get_user_by( 'login', $user[0]['user_login'] );
      if ( ! $user ) {
        $log[] = 'User does not exist in the v2 database: ' . $user[0]['user_login'];
        continue;
      }

      $postarr['post_author'] = $user->ID;

      // Step 2. Check if the vehicle is already in the database.
      $vehicle_check = get_posts([
        'post_type'   => 'ajp_vehicle',
        'numberposts' => 1,
        'author'      => $user->ID,
        'name'        => $vehicle['post_name']
      ]);
      if ( $vehicle_check ) {
        $log[] = 'Vehicle already exists in the v2 database: ' . $vehicle_check[0]->ID;

        $postarr['ID'] = $vehicle_check[0]->ID;
      } else {
        $log[] = 'Vehicle does not exist: ' . $vehicle['post_name'];
      }

      // Step 3. Get the vehicle meta info.
      $log[] = 'Getting vehicle meta for: ' . $vehicle['ID'];
      $meta_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'postmeta WHERE post_id = :ID' );
      $meta_stmt->execute([ 'ID' => $vehicle['ID'] ]);
      $meta = $meta_stmt->fetchAll();

      $acfdata       = [];
      $featued_image = false;
      $gallery       = false;
      $make_id       = false;
      $model_id      = false;

      foreach( $meta as $k => $v ) {
        switch( $v['meta_key'] ) {
          case 'wpcm_mileage':
            $acfdata['ajp_mileage'] = $v['meta_value'];
          break;
          case 'wpcm_color':
            $acfdata['ajp_color'] = $v['meta_value'];
          break;
          case 'wpcm_transmission':
            $acfdata['ajp_transmission'] = $v['meta_value'];
          break;
          case 'wpcm_doors':
            $acfdata['ajp_doors'] = $v['meta_value'];
          break;
          case 'wpcm_engine':
            $acfdata['ajp_engine'] = $v['meta_value'];
          break;
          case 'wpcm_frdate':
            $acfdata['ajp_year'] = date( 'Y', strtotime( $v['meta_value'] ) );
          break;
          case 'wpcm_condition':
            $acfdata['ajp_condition'] = $v['meta_value'];
          break;
          case '_thumbnail_id':
            $featued_image = $v['meta_value'];
          break;
          case '_car_gallery':
            $gallery = $v['meta_value'];
          break;
          case 'wpcm_make':
            $make_id = $v['meta_value'];
          break;
          case 'wpcm_model':
            $model_id = $v['meta_value'];
          break;
        }
      }

      // Step 4. Get Make & model
      if ( $make_id ) {
        $make_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'terms WHERE term_id = :ID LIMIT 1' );
        $make_stmt->execute([ 'ID' => $make_id ]);
        $make = $make_stmt->fetchAll();
        $make = array_pop( $make );

        $make_term = get_term_by( 'slug', $make['slug'], 'ajp_make_model' );
        if ( $make_term ) {
          $postarr['tax_input']['ajp_make_model'][] = $make_term->term_id;
        }
      }

      if ( $model_id ) {
        $model_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'terms WHERE term_id = :ID LIMIT 1' );
        $model_stmt->execute([ 'ID' => $model_id ]);
        $model = $model_stmt->fetchAll();
        $model = array_pop( $model );

        $model_term = get_term_by( 'slug', $model['slug'], 'ajp_make_model' );
        if ( $model_term ) {
          $postarr['tax_input']['ajp_make_model'][] = $model_term->term_id;
        }
      }

      $log[] = 'Vehicle:';
      $log[] = $postarr;

      $log[] = 'ACF:';
      $log[] = $acfdata;

      if ( ! $test_run ) {
        $vehicle_id = wp_insert_post( $postarr );

        if ( ! is_wp_error( $vehicle_id ) ) {
          $log[] = 'Success. Vehicle ' . $postarr['post_name'] . ' (' . $user->ID . ') was successfully synced. Syncing ACF...';

          // Sync ACF
          foreach( $acfdata as $k => $val ) {
            update_field( $k, $val, $vehicle_id );
          }

          // Get featured image
          include_once( ABSPATH . 'wp-admin/includes/admin.php' );
          global $wpdb;
          if ( $featued_image ) {
            $log[] = 'Featured image available. Downloading...';

            $image_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'posts WHERE ID = :ID LIMIT 1' );
            $image_stmt->execute([ 'ID' => $featued_image ]);
            $image = $image_stmt->fetchAll();

            if ( $image ) {
              $image_url = $image[0]['guid'];

              // Check if image has already been uploaded.
              $fileID = false;
              $image_check = $wpdb->get_var( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_type = "attachment" AND guid LIKE "%' . basename( $image_url ) . '" AND post_parent = ' . $vehicle_id ) );
              if ( $image_check ) {
                $fileID = $image_check;
              } else {
                $fileID = media_sideload_image( $image_url, $vehicle_id, $postarr['post_title'], 'id' );

                if ( is_wp_error( $fileID ) ) {
                  $log[] = 'Error saving image: ';
                  $log[] = $fileID->get_error_messages();
                }
              }

              if ( $fileID ) {
                $attach_image = set_post_thumbnail( $vehicle_id, $fileID );

                if ( $attach_image ) {
                  $log[] = 'Image successfully downloaded & attached to the vehicle.';
                } else {
                  $log[] = 'Could not attach the image to the vehicle.';
                }
              }
            }
          }

          // Get gallery images
          if ( $gallery ) {
            $gallery_image_ids = [];
            $log[] = 'Gallery images available. Downloading...';

            $gallery_stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'posts WHERE ID IN (' . $gallery . ')' );
            $gallery_stmt->execute();
            $gallery_images = $gallery_stmt->fetchAll();

            foreach( $gallery_images as $k => $v ) {
              $img_url = $v['guid'];

              $gallery_img_id = false;
              $gallery_image_check = $wpdb->get_var( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_type = "attachment" AND guid LIKE "%' . basename( $img_url ) . '" AND post_parent = ' . $vehicle_id ) );
              if ( $gallery_image_check ) {
                $gallery_img_id = $gallery_image_check;
              } else {
                $gallery_img_id = media_sideload_image( $img_url, $vehicle_id, $postarr['post_title'], 'id' );

                if ( is_wp_error( $gallery_img_id ) ) {
                  $log[] = 'Error saving gallery image: ';
                  $log[] = $fileID->get_error_messages();
                } else {
                  $gallery_image_ids[] = $gallery_img_id;
                }
              }
            }

            if ( $gallery_image_ids ) {
              update_field( 'ajp_gallery', $gallery_image_ids, $vehicle_id );
            }
          }
        } else {
          $log[] = 'Error. Vehicle ' . $postarr['post_name'] . ' could not be synced: ' . $vehicle_id->get_error_message();
        }
      }
    }

  } catch(PDOException $e) {
    $log[] = $e->getMessage();
  }

  print_r( $log );
  die();
}
add_action( 'init', 'ajp_sync_v1_sync_vehicles' );

function ajp_sync_v1_sync_users() {
  if ( empty( $_REQUEST['sync_users'] ) || 'sync' != $_REQUEST['sync_users'] ) { return false; }

  $test_run = false;
  $limit    = ! empty( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : 300;
  $offset   = ! empty( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : 0;

  $log = [];

  try {
    $conn = new PDO('mysql:host=' . AJP_V1_DBHOST . ';dbname=' . AJP_V1_DBNAME, AJP_V1_DBUSER, AJP_V1_DBPASS );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'users' );
    $stmt->execute();

    $users = $stmt->fetchAll();

    $log[]        = 'Found ' . count( $users ) . ' users in the v1 database.';
    $count        = 0;
    $offset_count = 0;
    foreach( $users as $key => $user ) {
      $offset_count++;
      if ( $offset_count < $offset ) { continue; }

      $count++;
      if ( $count > $limit ) { break; }

      $userdata = ['role' => 'subscriber'];
      $acfdata  = [];

      if ( $user['user_email'] == 'bmarshall511@gmail.com' ) {
        $userdata['role'] = 'administrator';
      }

      // Step 1. Get the extra user data from the v1 user meta table.
      $data_stmt = $conn->prepare( 'SELECT meta_key, meta_value FROM ' . AJP_V1_DBPREFIX . 'usermeta WHERE user_id = :id' );
      $data_stmt->execute([ 'id' => $user['ID'] ]);
      $data_stmt_meta = $data_stmt->fetchAll();

       // ACF fields
       $acfdata['user_basic']   = [];
       $acfdata['user_contact'] = [];
       $acfdata['user_profile'] = [];

      foreach( $data_stmt_meta as $k => $meta ) {
        // Core fields
        if ( $meta['meta_key'] == 'first_name' ) { $userdata['first_name'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'last_name' ) { $userdata['last_name'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'nickname' ) { $userdata['nickname'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'description' ) { $userdata['description'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'rich_editing' ) { $userdata['rich_editing'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'syntax_highlighting' ) { $userdata['syntax_highlighting'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'comment_shortcuts' ) { $userdata['comment_shortcuts'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'admin_color' ) { $userdata['admin_color'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'use_ssl' ) { $userdata['use_ssl'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'show_admin_bar_front' ) { $userdata['show_admin_bar_front'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'locale' ) { $userdata['locale'] = $meta['meta_value']; }

        // ACF fields
        if ( $meta['meta_key'] == 'user_gender' ) { $acfdata['user_basic']['gender'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'user_dob' ) { $acfdata['user_basic']['dob'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'user_city' ) { $acfdata['user_contact']['city'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'user_state' ) { $acfdata['user_contact']['state'] = $meta['meta_value']; }
        if ( $meta['meta_key'] == 'user_interests' ) {
          $acfdata['user_profile']['interests'] = unserialize($meta['meta_value']);
        }
      }

      // Step 2. Check if the v1 user email exists in the v2 user table.
      $exists = email_exists( $user['user_email'] );
      if ( $exists  ) {
        // User exists
        $log[] = 'User ' . $user['user_email'] . ' exists.';

        $userdata['ID'] = $exists;
      } else {
        // User doesn't exist
        $log[] = 'User ' . $user['user_email'] . ' doesn\'t exist.';
      }

      // Step 3. Generate the user data from the v1 user.
      $userdata['user_login']      = $user['user_login'];
      $userdata['user_nicename']   = $user['user_nicename'];
      $userdata['user_url']        = $user['user_url'];
      $userdata['user_email']      = $user['user_email'];
      $userdata['display_name']    = $user['display_name'];
      $userdata['user_registered'] = $user['user_registered'];

      // Step 4. Sync the user with v2.
      $log[] = $userdata;
      $log[] = $acfdata;

      if ( ! $test_run ) {
        $user_id = wp_insert_user( $userdata );
        if ( ! is_wp_error( $user_id ) ) {
          $log[] = 'Success. User ' . $userdata['user_email'] . ' (' . $user_id . ') was successfully synced. Syncing ACF...';

          // Sync ACF
          foreach( $acfdata as $k => $val ) {
            update_field( $k, $val, 'user_' . $user_id );
          }
        } else {
          $log[] = 'Error. User ' .$userdata['user_email'] . ' could not be synced: ' . $user_id->get_error_message();
        }
      }
    }

  } catch(PDOException $e) {
    $log[] = $e->getMessage();
  }

  print_r( $log );
  die();
}
add_action( 'init', 'ajp_sync_v1_sync_users' );
