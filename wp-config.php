<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

/**
 * Local configuration information.
 *
 * If you are working in a local/desktop development environment and want to
 * keep your config separate, we recommend using a 'wp-config-local.php' file,
 * which you should also make sure you .gitignore.
 */
if (file_exists(dirname(__FILE__) . '/wp-config-local.php')):
  # IMPORTANT: ensure your local config does not include wp-settings.php
  require_once(dirname(__FILE__) . '/wp-config-local.php');

/**
 * Production settings. Everything you need should already be set.
 */
else:
  // ** MySQL settings - You can get this info from your web host ** //
  /** The name of the database for WordPress */
  define( 'DB_NAME', getenv('DB_NAME') );

  /** MySQL database username */
  define( 'DB_USER', getenv('DB_USER') );

  /** MySQL database password */
  define( 'DB_PASSWORD', getenv('DB_PASSWORD') );

  /** MySQL hostname */
  define( 'DB_HOST', 'localhost' );

  /** Database Charset to use in creating database tables. */
  define( 'DB_CHARSET', 'utf8mb4' );

  /** The Database Collate type. Don't change this if in doubt. */
  define( 'DB_COLLATE', '' );

  /**#@+
   * Authentication Unique Keys and Salts.
   *
   * Change these to different unique phrases!
   * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
   * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
   *
   * @since 2.6.0
   */
  define( 'AUTH_KEY',         '!%RIueMk+{~zjo%7hY28Pl,d/nE[?$Z.?+ZCt:iMB8SD``)cdW&;NRj>$&E1xak?' );
  define( 'SECURE_AUTH_KEY',  'lQ>D,N?<n`EA%%$xEI]{l nO~5%L95McD,d B8TB8Y#m95m?<B?P@vDf)0|#D!P{' );
  define( 'LOGGED_IN_KEY',    '{[@UGcCEYL`(l2GVZh,(lygXTk=p4yP8zZ:3aG$^rUxv?O| tJar^%$gfIgOSjM;' );
  define( 'NONCE_KEY',        '3T8=GOXSDlbn`qs ?0~/wlN3WP&vqb<)+DujMuB nT.2Qt}2a/>teP$-2<]mSA$-' );
  define( 'AUTH_SALT',        '*lB/,aIKYuP{tib2vBez{Gc:7cWY/2fK.Q)B#<;1XAX*|vkE@VQG49Avj:dz8Sdq' );
  define( 'SECURE_AUTH_SALT', '|Ad]dFj^:GavDFA_cj!fH9{NtVLZoEv`6IfsqG,ov50Od6TFw}+-&)!R9XUMgA?V' );
  define( 'LOGGED_IN_SALT',   ';7!bCnEYf XMP8?hPEkoSO+fv;dcb:}ygD6Sbk;gUL=w@6jtW(BL7N>m;pZLRiA,' );
  define( 'NONCE_SALT',       'A?5,v0IX2*`q3ZjAkv(gB|gSkd$H9!i?)fXVjJ$3/}iMQQyU6%_y=7%=8?lP#PyI' );

  /**#@-*/

  /**
   * WordPress Database Table prefix.
   *
   * You can have multiple installations in one database if you give each
   * a unique prefix. Only numbers, letters, and underscores please!
   */
  $table_prefix = 'wp_';

  /**
   * For developers: WordPress debugging mode.
   *
   * Change this to true to enable the display of notices during development.
   * It is strongly recommended that plugin and theme developers use WP_DEBUG
   * in their development environments.
   *
   * For information on other constants that can be used for debugging,
   * visit the documentation.
   *
   * @link https://wordpress.org/support/article/debugging-in-wordpress/
   */
  define( 'WP_DEBUG', false );

  /* That's all, stop editing! Happy publishing. */
endif;

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
