<?php

namespace MigrateTheme;

use MigrateTheme\Scanner;
use MigrateTheme\PluginZip;

defined('ABSPATH') || exit;

class PluginAjax
{

	public function __construct()
	{
		add_action('init', array($this, 'define_ajax'), 10);
		add_action('init', array($this, 'do_wpmt_ajax'), 20);
		$this->add_ajax_actions();
	}

	public static function get_endpoint()
	{
		return esc_url_raw(get_admin_url() . 'tools.php?page=' . WPMT_PLUGIN_NAME . '&wpmt-ajax=');
	}

	public function define_ajax()
	{
		if (isset($_GET['wpmt-ajax']) && !empty($_GET['wpmt-ajax'])) {

			// Define the WordPress "DOING_AJAX" constant.
			if (!defined('DOING_AJAX')) {
				define('DOING_AJAX', true);
			}

			// Prevent notices from breaking AJAX functionality.
			if (!WP_DEBUG || (WP_DEBUG && !WP_DEBUG_DISPLAY)) {
				@ini_set('display_errors', 0);
			}

			// Send the headers.
			send_origin_headers();
			@header('Content-Type: text/html; charset=' . get_option('blog_charset'));
			@header('X-Robots-Tag: noindex');
			send_nosniff_header();
			nocache_headers();
		}
	}

	/**
	 * Check if we're doing AJAX and fire the related action.
	 */
	public function do_wpmt_ajax()
	{
		global $wp_query;

		if (isset($_GET['wpmt-ajax']) && !empty($_GET['wpmt-ajax'])) {
			$wp_query->set('wpmt-ajax', sanitize_text_field($_GET['wpmt-ajax']));
		}

		if ($action = $wp_query->get('wpmt-ajax')) {
			do_action('wpmt_ajax_' . sanitize_text_field($action));
			die();
		}
	}

	/**
	 * Adds any AJAX-related actions.
	 */
	public function add_ajax_actions()
	{
		$actions = array(
			'process_migrate_theme',
			'process_load_theme',
		);

		foreach ($actions as $action) {
			add_action('wpmt_ajax_' . $action, array($this, $action));
		}
	}

	/**
	 * 
	 * 
	 */
	public function process_migrate_theme()
	{
		// Bail if not authorized.
		if (!check_admin_referer('ajax_nonce', 'ajax_nonce')) {
			return;
		}

		$exclude = !empty($_POST['data']) ? explode(',', $_POST['data']) : [];
		array_push($exclude, 'node_modules');

		$theme = !empty($_POST['theme']) ? $_POST['theme'] : '';
		$themeDir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme;

		$fileName = $theme . '-' . time() . '.zip';
		$destination = WPMT_PLUGIN_UPLOADS . DIRECTORY_SEPARATOR . $fileName;
		$fileUrl = WPMT_PLUGIN_UPLOADS_URL . DIRECTORY_SEPARATOR . $fileName;

		PluginZip::zipMake($themeDir, $destination, $exclude);
		PluginZip::zipDeleteEmptyDir($destination);

		$result = array(
			'fileUrl'  => $fileUrl,
			'fileName' => $fileName,
			'alert'    => 'All done! Enjoy!',
			'error'    => 'Failed to fetch data',
			'url' 	   => get_admin_url() . 'tools.php?page=' . WPMT_PLUGIN_NAME . '&tab=import',
		);

		// Send output as JSON for processing via AJAX.
		echo json_encode($result);
		exit;
	}

	/**
	 * 
	 * 
	 */
	public function process_load_theme()
	{

		// Bail if not authorized.
		if (!check_admin_referer('ajax_nonce', 'ajax_nonce')) {
			return;
		}
		$theme = $_POST['data'];

		if (empty($theme)) return;

		$themeDir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme;

		if (!is_dir($themeDir)) return;

		$files = Scanner::findDirs($themeDir);
		$themeFiles = Scanner::getThemeStructure($themeDir, $files);

		include WPMT_PLUGIN_PATH . '/views/dashboard-content.php';

		wp_reset_postdata();

		exit();
	}
}
