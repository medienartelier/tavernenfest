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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tavernenfest' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '6L?Kqwf+4u]-4$& ^Hf&,U}TS,OjLhw/tx~:(q+KWL;5d#S8~th!H1^VR{N U,hy' );
define( 'SECURE_AUTH_KEY',  'M-Z3F}7I]CG,/QL|v/snA{;AnLb!`,-is|mSz8gi`}sH?$cbylg.wAt2UKWv3]J;' );
define( 'LOGGED_IN_KEY',    'g?77W^-a#hY6?*VJe1VoSRy88QM@$BNgV?^TylSw^qsdpt[3`r75AoV9dOL>?rvp' );
define( 'NONCE_KEY',        'AD^I@k(lQu(t~)~Qw{iMB,yOce`04sg^dz+CYbz8Xpnon$ N]z u1mcpy:$$#M{p' );
define( 'AUTH_SALT',        '+>&e;kW>%>:2?mM[4Pqy]W!u x)Nm_cYcpHA}<HzxJ(L[*Wt;Tkf+^,{A#MH.@FI' );
define( 'SECURE_AUTH_SALT', 'cNF<>;!/3X4$uE9HfFkYIdd7DC.E<e+fHMxAF2n9e8+/m`P>KoaL=jp^sW<iCj?o' );
define( 'LOGGED_IN_SALT',   '0M _opNqaB/zqF+^Vc1kAcG$,WZnUX[0-l~ke.bbByC#WwnM5-t;RYHL*Xo1?FrC' );
define( 'NONCE_SALT',       'Qv4Ru0RtS^NW`Hw}qW}O6B<|OVhM9Que=#,a}q+%Bau0rQY|6fnj&V(f7&nO.Qm/' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
