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
define('DB_NAME', 'wrd_4hn54foojf');

/** MySQL database username */
define('DB_USER', 'wrdQOjB5fC3');

/** MySQL database password */
define('DB_PASSWORD', '2PtnOrDywO');

/** MySQL hostname */
define('DB_HOST', 'vapvarun.ipowermysql.com');

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
define('AUTH_KEY',         'HOLy4HO2uIdCFjLJh3h89whct86CVP0ziWrzL4TDsVRRumW2douQONAsVLONJEKr');
define('SECURE_AUTH_KEY',  'piobowoljVGpKzni1x8DfI7nEujLWjbirfWpHsMEYslPNzYfhp6YXVxVXYXHV41D');
define('LOGGED_IN_KEY',    '8xviqIPecSyVXkGm6bDHEvv5OAIRsulBZZUk8vC8wYitXFOcMvb4J2zCHO4lMHMu');
define('NONCE_KEY',        'HOiQbMEywyaXpEoTct8O6IxBMtsQstwm8FzTf1BIjsFTIqS7jusvsEDgiNDJmNHI');
define('AUTH_SALT',        'fsJQySRrdYAsFHZnTIAinXpgg2xnpL5PSxCv4kJWIqe1n4bUMSECBdwa3eHuIgRZ');
define('SECURE_AUTH_SALT', 'oJeUaWD5kdoO0Um2U9hwbTuvvXJ05OQxv6DhhwQy4S8F2mSUmtu8iUEb2XNGdvtq');
define('LOGGED_IN_SALT',   'AkSWV0AC9zO5dUMJhdrY14dXkF3QewnTeQwezbYDjQ3A0oAw3r3r91rHdimw1hFi');
define('NONCE_SALT',       'Ijd9BSEt0zqXJvZZ38qLN4b0b0w4RzkLjfscLMJFUBqwhsJ9mE477K7A1854mLZ6');

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
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
$base = '/';
define('DOMAIN_CURRENT_SITE', 'microcerpt.biz');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');