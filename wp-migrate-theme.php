<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/SerhiiMazurBeetroot/
 * @since             1.0.0
 * @package           WP_Migrate_Theme
 *
 * @wordpress-plugin
 * Plugin Name:       Migrate Theme
 * Plugin URI:        https://github.com/SerhiiMazurBeetroot/wp-migrate-theme
 * Description:       This plugin was created to copy the current theme to a new one.
 * Version:           1.0.0
 * Author:            Serhii Mazur
 * Author URI:        https://github.com/SerhiiMazurBeetroot/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-migrate-theme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Required for get_plugin_data
 */
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

define('WPMT_PLUGIN_FILE', __FILE__);
define('WPMT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPMT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPMT_PLUGIN_NAME', get_plugin_data(__FILE__)['Name']);
define('WPMT_PLUGIN_SLUG', get_plugin_data(__FILE__)['TextDomain']);
define('WPMT_PLUGIN_VERSION', get_plugin_data(__FILE__)['Version']);
define('WPMT_PLUGIN_UPLOADS', WP_CONTENT_DIR . '/uploads/' . WPMT_PLUGIN_SLUG . '/');
define('WPMT_PLUGIN_UPLOADS_URL', WP_CONTENT_URL . '/uploads/' . WPMT_PLUGIN_SLUG . '/');

require_once WPMT_PLUGIN_PATH . 'src/Hooks.php';
