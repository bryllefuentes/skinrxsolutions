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
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/w8662853/public_html/wp-content/plugins/wp-super-cache/' );
define( 'DB_NAME', 'w8662853_wp2' );

/** MySQL database username */
define( 'DB_USER', 'w8662853_wp2' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Q.X3AwF1O7OL9vlG2kW18' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         '9dAQPUPUA6ArT1tquRKBTqCnkw3p23RQzouTDzPDBr0fqlKrGFgGKRelZxjZD832');
define('SECURE_AUTH_KEY',  'YLX323TCsPq1hHIOoY25NTIzeEItEDhQ0x132e3leppbkhe6PRZhjKEUSD1M4L6u');
define('LOGGED_IN_KEY',    '2rsDtbNxKe2Ov1hQj30LNEtId1uKz6FfSmDT3cGAM6JkjTJnLR9NFecokGvVqH7M');
define('NONCE_KEY',        'wiCv75hrj1ufzcSgRY3UBGVO2jMMyFDggWHYgQZ4pLvlkHztM2Q9OxtpShKxKP3Z');
define('AUTH_SALT',        'LcVreVbuv2bCz7jsCdw4viZmxTEEyg4xCzBPNZjsxJONKLe3SssCcP2pMTTDSmIg');
define('SECURE_AUTH_SALT', '6Hc9KgLfMDCgD7MhWFNFALUDO8XgKvwnWMnIiR5fBCvqqoSXdlU1w58KBk5OXe5q');
define('LOGGED_IN_SALT',   'REq8bSp0zFLnapDFfO0JtojgzDT8Q2pLFRPXJpAOxp6289681KGecFAQDzpAFVNn');
define('NONCE_SALT',       'cCAFqBvxrMYtF49TlsbDH55EEdAMRvL763V5kBL9IlFqA0lIaaamoMU3BO4RI57f');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed externally by Installatron.
 * If you remove this define() to re-enable WordPress's automatic background updating
 * then it's advised to disable auto-updating in Installatron.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';