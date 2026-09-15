<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'P|?|]fr^DGw$wk3}?SlY-6Jm#%a:eFwl,EZ;=~L/D!&_s1D@U9x_>o&:&D5Tjw-j' );
define( 'SECURE_AUTH_KEY',   '#wlpP2]dv9v)-,FAocU],2~ht3!k56eICBx.d/Ssamk2l}7_RX317CMmQLbTe90]' );
define( 'LOGGED_IN_KEY',     'XqJf]aCw{uNOd5*UP-&xA;Tdv^!)3ihYSife@N#BqTpZYedSbOpZWI;e*=K/cvK@' );
define( 'NONCE_KEY',         '4dr&&@[YJ[FA2Eh(SSp9f+TB<8lAAf;!:izqXtst&We:E{L-yT@(e{yM,V~Z$ms^' );
define( 'AUTH_SALT',         'qG<4?0=I8<@@)}O(T0#0?yU,xwN}|g&ZO4CX/xT^.wiqP|G$@5+4n[QL0|[s4W(@' );
define( 'SECURE_AUTH_SALT',  '!gsxUKi1!6z!%2HvU8IUG^gG|s~PZC3D>=|/,uLL9&{pv-SI@8_h0tnNN]}5h,jy' );
define( 'LOGGED_IN_SALT',    'zD(0LoWqgL*s`k_h_I*.4a=T<:fpQY_=+O6vO^w}rAV:0#l_&#)Isbjo>yC%g4x1' );
define( 'NONCE_SALT',        'SF<3]K&PIXIs(<^<B5/ZWpTx2lTS+pRwndm#]dv8E3zRS+d/-D*QkxRW|0H^bHS?' );
define( 'WP_CACHE_KEY_SALT', 'R`<w;yy+qHDjlX1U*r%sZ]>h?OX>{)e*8~7l!S}b$UCAG?f+6i`gebcT(TtD26_x' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
