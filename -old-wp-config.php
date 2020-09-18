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
define( 'DB_NAME', 'bee' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '#*9Y2*9}QPim2!YuK_NEA{6}Wpqkj31wk.$gjnObM4*kO|I2$pYfzW7*od1==NfW' );
define( 'SECURE_AUTH_KEY',  '-L-COA3`{2u%Ny|Sy}=oLpprEYiyzog.y{U+S9aMTZ4G^&J&{R7BY*(DN#YFAr;H' );
define( 'LOGGED_IN_KEY',    'i<K*2?v79#;S3 Q3d]=}8^_U!A4q:0RkH(dwc^Q_*/PfWgAr3u2t)g(O(yOMar3=' );
define( 'NONCE_KEY',        'YaZj_4Z_34O~b.h-V_j)VFjiy4_aY!}Wk43K_vOJ8pD(Y%SM=<Hu;iIl~I}.]xqR' );
define( 'AUTH_SALT',        'hXZ/.<U9Bos@[y%`ot`ZhiiglF^haO#:zVgAyuhwEz_GzQbX,!DREy*[dm-&`;G2' );
define( 'SECURE_AUTH_SALT', 'DD4*m(yUXcP0LVgfadh56iQqEfyjXyQ$+01-.h0g]xb4,yxZqwm5,SNG) Lf56>N' );
define( 'LOGGED_IN_SALT',   '|}U7#@#vN*Me,7Y4i~ox%e05]E8-]uaT}t~|Y~0Y$Be:0#:A3C_VIiUy:_bX`R[X' );
define( 'NONCE_SALT',       '_<YIE$*:?^/.}1RHPJ^fwwJ0,Kf-IsL+8Mo?>]7iH,{2{*KGWyf!?woeSx[m#~bv' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bee_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
