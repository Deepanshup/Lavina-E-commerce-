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
define( 'DB_NAME', 'lavina' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'SCtihKv*$[K*%l}@{Wc>okg,R)hpMK]?;j!fBkq`CjIBXO3pjq3:%A9y)TY5^4Y1' );
define( 'SECURE_AUTH_KEY',  '*k2+2PzBgpL:dR=S$jZIfbx;Pa4x1o+9$Y]:x8Z&LDjn:]]%+2h7@*v1@+O.~!%(' );
define( 'LOGGED_IN_KEY',    '%;P{5~1w|pq(?`j%hX(g?g.~c onlxUa-0hwfAl0i|cK3(?m1b$U)YBt`,YtKXlp' );
define( 'NONCE_KEY',        'Pl850z^(EFdiOlfY0V0F&O_2Mf|[ELXpxvT0[o5wHsX*k[`*`D1-.0pQHm|6TP98' );
define( 'AUTH_SALT',        'Yu[%;.@p+}bwhTvb`KL6=@qdfGY.SUPsjXv@|[SRifAfS7@o:LCNwdxcku&iU$uB' );
define( 'SECURE_AUTH_SALT', '#|}g(ds=BjsAb|kV?4me$u4]KdgN3d3kVV9qao*?{#L)]K{0hR=LuGSkXPl*@wL#' );
define( 'LOGGED_IN_SALT',   '1lVM@lbxqN0xZK.K`k,|I$UZ>RuH*{e}e5+e>eAm5TRc=BW[]5?A(#jJmK(rOva#' );
define( 'NONCE_SALT',       '/YQ%iY/4Lp*xV@k@.U&u$Z=>qi>M9tQ .38A*jm(fkw_apgH+Ph`?>@8xM#s9f&%' );

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
