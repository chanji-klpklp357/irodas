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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4z4zPT60U9ncaWL4VfZAwvgJMkZ+lZJc84m30glxNJOaDVqeYUx/ZJe8bALyOrAqX6Zg4JBJ/fV+N93t6ahObQ==');
define('SECURE_AUTH_KEY',  'tKASg28LrOKqbfRSJc7g0lhgj7qJ5HAsVdpsg0uy0h5kbB1HhNfxbc3W/GCYDGpVMYfYyRtK1fgov9m4fy72xw==');
define('LOGGED_IN_KEY',    'Tcf0vxJWPOu5jHpmzFFgj9ZfGmZo1RX3ptTVi4rqlPYo5kK8N56FPACPLsPfmKgya71XTfgYr019/dJp9i9a4A==');
define('NONCE_KEY',        'ld7Fc/Sb6PCJroYtaSD4BfGBIa/4roawgGrBFZPXaPgN6mkXb3b0Bby98txghi0hnDIVMhbgVpGxNiR1qNIHiQ==');
define('AUTH_SALT',        'DTTD+LuWXO0XdHL3TBQGuXlGt5BfGJc8oIY1zxu4i9W71QermKoKVoIG+yd9ZaSQOE6Wi2tIYUS+4TN1MBDD6g==');
define('SECURE_AUTH_SALT', 'saFNIrhKvwQVXNAbLiCpCp0d2k8FRkGoEwDzOyNiAXtJ8phSxXJMBSKrfoVYJaJQTL/SOoODecl1XpNg65bDOQ==');
define('LOGGED_IN_SALT',   'EfpYzJyPhRSin8rQAMLzUI1vxWPNYjLvB9xNCyOVNtzPYQH1qoa25NzWgTvkAirgM9RA6/AgtbRtyyRJw6+IiA==');
define('NONCE_SALT',       '0vuSFbZiasBSxONqLGzaWugILTibS4JUDwi/gywoRzWFmgpGbMwpE3TDH985opQNdG9aJ7HjpfZw4YVXRtWXIw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
