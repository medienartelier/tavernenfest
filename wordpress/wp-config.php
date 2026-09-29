<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

/**
 * Load environment variables from .env.
 *
 * The repository root .env is preferred, with wordpress/.env as a fallback.
 */
$tavernenfest_env_files = array(
	dirname( __DIR__ ) . '/.env',
	__DIR__ . '/.env',
);

foreach ( $tavernenfest_env_files as $tavernenfest_env_file ) {
	if ( ! is_readable( $tavernenfest_env_file ) ) {
		continue;
	}

	foreach ( file( $tavernenfest_env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) as $tavernenfest_env_line ) {
		$tavernenfest_env_line = trim( $tavernenfest_env_line );

		if ( '' === $tavernenfest_env_line || 0 === strpos( $tavernenfest_env_line, '#' ) || false === strpos( $tavernenfest_env_line, '=' ) ) {
			continue;
		}

		list( $tavernenfest_env_key, $tavernenfest_env_value ) = explode( '=', $tavernenfest_env_line, 2 );
		$tavernenfest_env_key   = trim( $tavernenfest_env_key );
		$tavernenfest_env_value = trim( $tavernenfest_env_value );
		$tavernenfest_env_value = trim( $tavernenfest_env_value, "\"'" );

		if ( '' !== $tavernenfest_env_key && false === getenv( $tavernenfest_env_key ) ) {
			putenv( $tavernenfest_env_key . '=' . $tavernenfest_env_value );
			$_ENV[ $tavernenfest_env_key ]    = $tavernenfest_env_value;
			$_SERVER[ $tavernenfest_env_key ] = $tavernenfest_env_value;
		}
	}

	break;
}

function tavernenfest_env( $key, $default = null ) {
	$value = getenv( $key );

	if ( false === $value ) {
		return $default;
	}

	if ( in_array( strtolower( $value ), array( 'true', '(true)' ), true ) ) {
		return true;
	}

	if ( in_array( strtolower( $value ), array( 'false', '(false)' ), true ) ) {
		return false;
	}

	return $value;
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', tavernenfest_env( 'DB_NAME', 'tavernenfest' ) );

/** Database username */
define( 'DB_USER', tavernenfest_env( 'DB_USER', 'root' ) );

/** Database password */
define( 'DB_PASSWORD', tavernenfest_env( 'DB_PASSWORD', 'password' ) );

/** Database hostname */
define( 'DB_HOST', tavernenfest_env( 'DB_HOST', 'localhost' ) );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', tavernenfest_env( 'DB_CHARSET', 'utf8mb4' ) );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', tavernenfest_env( 'DB_COLLATE', '' ) );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         tavernenfest_env( 'AUTH_KEY', 'change-me' ) );
define( 'SECURE_AUTH_KEY',  tavernenfest_env( 'SECURE_AUTH_KEY', 'change-me' ) );
define( 'LOGGED_IN_KEY',    tavernenfest_env( 'LOGGED_IN_KEY', 'change-me' ) );
define( 'NONCE_KEY',        tavernenfest_env( 'NONCE_KEY', 'change-me' ) );
define( 'AUTH_SALT',        tavernenfest_env( 'AUTH_SALT', 'change-me' ) );
define( 'SECURE_AUTH_SALT', tavernenfest_env( 'SECURE_AUTH_SALT', 'change-me' ) );
define( 'LOGGED_IN_SALT',   tavernenfest_env( 'LOGGED_IN_SALT', 'change-me' ) );
define( 'NONCE_SALT',       tavernenfest_env( 'NONCE_SALT', 'change-me' ) );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', tavernenfest_env( 'WP_DEBUG', false ) );

/* Add any custom values between this line and the "stop editing" line. */

define( 'WP_ENVIRONMENT_TYPE', tavernenfest_env( 'WP_ENV', 'production' ) );
define( 'WP_DEBUG_LOG', tavernenfest_env( 'WP_DEBUG_LOG', false ) );
define( 'WP_DEBUG_DISPLAY', tavernenfest_env( 'WP_DEBUG_DISPLAY', false ) );
define( 'SCRIPT_DEBUG', tavernenfest_env( 'SCRIPT_DEBUG', false ) );
define( 'DISALLOW_FILE_EDIT', tavernenfest_env( 'DISALLOW_FILE_EDIT', true ) );

if ( tavernenfest_env( 'WP_HOME' ) ) {
	define( 'WP_HOME', tavernenfest_env( 'WP_HOME' ) );
}

if ( tavernenfest_env( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', tavernenfest_env( 'WP_SITEURL' ) );
}



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
