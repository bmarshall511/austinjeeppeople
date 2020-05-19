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

function ajp_sync_v1_sync_users() {
  if ( empty( $_REQUEST['sync_users'] ) || 'sync' != $_REQUEST['sync_users'] ) { return false; }

  $test_run = false;
  $limit    = ! empty( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : 5;

  $log = [];

  try {
    $conn = new PDO('mysql:host=' . AJP_V1_DBHOST . ';dbname=' . AJP_V1_DBNAME, AJP_V1_DBUSER, AJP_V1_DBPASS );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare( 'SELECT * FROM ' . AJP_V1_DBPREFIX . 'users' );
    $stmt->execute();

    $users = $stmt->fetchAll();

    $log[] = 'Found ' . count( $users ) . ' users in the v1 database.';
    $count = 0;
    foreach( $users as $key => $user ) { $count ++;
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
