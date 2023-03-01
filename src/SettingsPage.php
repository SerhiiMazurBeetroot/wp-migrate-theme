<?php

namespace MigrateTheme;

defined('ABSPATH') || exit;

class SettingsPage
{
	public static function init()
	{
		add_action('admin_menu', [__CLASS__, 'menuPage']);
	}

	public static function menuPage()
	{
		add_menu_page(
			WPMT_PLUGIN_NAME,
			WPMT_PLUGIN_NAME,
			'administrator',
			WPMT_PLUGIN_SLUG,
			[self::class, 'menuPageCallback'],
			'dashicons-media-archive',
			81
		);
	}

	public static function menuPageCallback()
	{
		ob_start();

		include_once WPMT_PLUGIN_PATH . 'views/dashboard.php';

		$output = ob_get_clean();

		echo $output;
	}
}
