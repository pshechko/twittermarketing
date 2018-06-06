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
define('DB_NAME', 'u566430076_gtrap');

/** MySQL database username */
define('DB_USER', 'u566430076_root');

/** MySQL database password */
define('DB_PASSWORD', 'elche#96');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '`(y1>i(/dA?oZP#j7{AoP;A!&RS KX.ZV+STm L^i.jQq:Z#o{i>)jAWG=F#X10.');
define('SECURE_AUTH_KEY',  '{xVvEQ@~(6;>(W8H}`?b *%Z_,|LrcZg&k/J%:yt[?jC_8e^0*G(f^~>{*[1{-DG');
define('LOGGED_IN_KEY',    'Cnc1o?sz{M!gj/1rQr]2a/*rP=4):{17,1(sl7j{80iaj1i;Mdl4[Tn<y~b L_*6');
define('NONCE_KEY',        '4}fB&j0EH7(Y[;T EI=#iwQ4kh&InU+,,/9H9SBIZ-LG5tX)T7x KJR&b;o,>@(]');
define('AUTH_SALT',        '{`hYw($=@@]BVzjl}LA_4GNrk>l6PgPRE(j0^kcOvnMWq&{6$Cjz^+[c8R6oArbQ');
define('SECURE_AUTH_SALT', 'FR4>?y:N>n;_mv/50]$9W/MCtyhFqBj&&1Rh5<<<:KwCyvi:qG_<JF&0I%iQYVe~');
define('LOGGED_IN_SALT',   ']zAF)?4rHb>~A;ZC_|$%+~!/&V+N0x0HV8gJr=0Rt4si+q= *YoPL9|]=*%t]Dyn');
define('NONCE_SALT',       'V<dox$pMY04xz!OX%d2a!JQ(QIya7NtT^.aG(|`t>$_W(hLD8>u <D--/diokAq5');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

define('TWITTER_CONSUMER_KEY', 'XL65oKSdkS6cYgcJ8rM8pgYlG');
define('TWITTER_CONSUMER_SECRET', 'YkS6FvQXSFhNgTjgGkMCOyMsyRWolnlasT95vIBWrVWiAuBuqe');
define('TWITTER_ACCESS_TOKEN', '2974301745-axKbbsQGdhPmhwuP4frwPudmEifQxofTPTJWZpl');
define('TWITTER_ACCESS_TOKEN_SECRET', 'bkvpP8SDqrf51CcWwynLMWgRYawwdQwQ6WFoMcYIMdm6U');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

