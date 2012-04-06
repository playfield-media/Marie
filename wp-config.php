<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'marie');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')0P$,v[z{{Clu9:6a^rtnQ+sPGwSQ8&c}@YUh-`[Wh1GY]<;}lR=;1B*Ejl6*6.G');
define('SECURE_AUTH_KEY',  '}C%@PTPNM$eOdCH)Jej4)-{IS(LCIx~v}F3+]`%<d(OqDAxA~jQB|4N)TdZig&mu');
define('LOGGED_IN_KEY',    '`TC/`EwX.yU`&?Xt{~gv]8;V[UEut5q{],=df*<oB4o;,LX e*W/b|h<4 Nca[3R');
define('NONCE_KEY',        'uQ3z0)]HUF-2Ntxz],qzVy([r{S<9TJQ;{R]g&I~1&g!;@{srW<#4`nc)7SjA*ge');
define('AUTH_SALT',        ':@v..A4E.s?l}_M(C`iC75qzW,:44g<47m&TcU_x>C4LGj1NxC~|24ufc@2OV(a6');
define('SECURE_AUTH_SALT', 'LVKe&jGc8b?eH}[}rW3)1`|jx?*(XhNT2dS.]dPIKAs!M]*L_7hNnD*+mIV`3wS*');
define('LOGGED_IN_SALT',   'NeLe0w<Hb</GxDFC6XEIMRtl?(S2Nrd)p<em&+CvjH)dJ%1.h8h!7hag5q<oDOy7');
define('NONCE_SALT',       'F3(q.rQq>)Z@I@Dp#&Wxf.<j:jICRr(@(1|_5ys -SRf*q!}k;*gw9*.^T!|Q<W|');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'nl_NL');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
