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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'biellabiju.ro' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'HTEUd3J9hBjo' );

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
define( 'AUTH_KEY',         '6m.tz8k%_`)1QSpwvOGj@jQ>l]]k3(ltqqT(Ym|ls>xaxl@hWYvt+nW0vUF%.v[T' );
define( 'SECURE_AUTH_KEY',  'BG7+JXHFN#{^&}v?Vqir/^^JJ1uZdV#lxPw9Rv;4<X]cIO@aqZ5r+rrAiHdL/V$Q' );
define( 'LOGGED_IN_KEY',    '|D]Vlp}<`LQ]{$B@?>@g0bYSLn`M+6hEz{cBwa 6+QHu|Gq.=abBx k4>@UK/]/_' );
define( 'NONCE_KEY',        'VnE+oGRo~/? d_KT|$;sCgHviOF^e<gYRt~x8K0@X?lx-O^G+zRd=k34,n!-<bRj' );
define( 'AUTH_SALT',        'B90e^7JRiRxG>7^p7m=uS@?|@8l+Vu+D N$U8kYaM, _Rc,f5uHI0[.x)WsGHg}U' );
define( 'SECURE_AUTH_SALT', '@7xguv^lu-Og|KB]^T&2?rB8U!&^cp]?$Boo&&#[tI:+rZ#~6bv 33p75wz~zT;l' );
define( 'LOGGED_IN_SALT',   '#F@;s/cZg5O%Ow;HpUBb0ln&}k^D./(`t!MaU~]x!m<8_|s_XuR6>Gt[(kF5Jtd ' );
define( 'NONCE_SALT',       'VwGy[:^oXtV|m__cM,8Ss>#G0k87A36YmRV[S)q4;,Rb$K2kuTBI^QTly2[S>Bvc' );

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

