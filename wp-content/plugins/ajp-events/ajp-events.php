<?php
/**
 * AJP Events WordPress Plugin
 *
 * @package           WordPress
 * @subpackage        AJPEvents
 * @author            Ben Marshall
 * @copyright         2019 Ben Marshall
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       AJP Events
 * Plugin URI:        https://austinjeeppeople.com
 * Description:       Creates the events post type & related functionality.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ben Marshall
 * Author URI:        https://benmarshall.me
 * Text Domain:       ajpeventss
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Post types & taxonomies
 */
require plugin_dir_path( __FILE__ ) . '/inc/post-types.php';
