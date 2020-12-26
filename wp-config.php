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
define( 'DB_NAME', 'addwebsolutionwp' );

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
define( 'AUTH_KEY',         'l=d:wV?DxHa5eDD$_}UMj)<GWJI98MB}5Ke#LEz]M!V4Fu$Y=p7e^oeSLsBcNQ`>' );
define( 'SECURE_AUTH_KEY',  'L,Q%wL51s&3Xo&`dB:Ef53L:OOm04Fyy9xQPF5=?!Fv6zv58efaFe.vl=pkdW:!I' );
define( 'LOGGED_IN_KEY',    'l^l#2Ng:6h[|?e)VlrMgm-{.n8rm=*N>kH@nb}r*1))!L`)xlYu*6-R?3N~!>2[v' );
define( 'NONCE_KEY',        'b+N[9H78Y:naqS42k{?_mei4zs.L+C5/rmL(>~MRFh$67.K)}u:)}Ko~8q,V[Oj|' );
define( 'AUTH_SALT',        'jsB_gIq57E%jB[W2.BF2g#K [f?M/}HTeq)BK%+0yvg|7H;TOP_M`0E d^)Ih7R1' );
define( 'SECURE_AUTH_SALT', 'xw<9JTa}%p9Ah,~O/Uz]*w&KoSK:NRr~+<JNMQi~/MXsBh1S;ykdCaEPHG3a>pL~' );
define( 'LOGGED_IN_SALT',   '{/jH~{5b%)S>y&&:g_a>k+:)P)zY),DW@lLdY7k8)8w,AC+Cg}eaKD5#)&E(O5ai' );
define( 'NONCE_SALT',       '#CsM`@$u0BReQwL&!HL]1aXZgi,hrn}4I!8TV(%$Wv,Z|cYYsk7`|*;)ai{>X+K0' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'addwp_';

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

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
