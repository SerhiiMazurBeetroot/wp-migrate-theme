<?php

namespace MigrateTheme;

use MigrateTheme\PluginActivation;
use MigrateTheme\SettingsPage;
use MigrateTheme\Assets;
use MigrateTheme\PluginAjax;
use MigrateTheme\PluginHistory;

defined('ABSPATH') || exit;

class Hooks
{

	public static function runHooks()
	{
		/*
		 * Activation
		 */
		register_activation_hook(WPMT_PLUGIN_FILE, [PluginActivation::class, 'activate']);
		register_deactivation_hook(WPMT_PLUGIN_FILE, [PluginActivation::class, 'deactivate']);

		/*
		 * Init
		 */
		SettingsPage::init();
		PluginHistory::setup();
		new PluginAjax();

		/*
		 * Assets
		 */
		Assets::instance()
			->loadInternalAssets([
				'styles.css',
				'scripts.js',
			]);
	}
}

Hooks::runHooks();
