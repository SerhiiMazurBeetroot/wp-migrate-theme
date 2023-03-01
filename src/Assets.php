<?php

namespace MigrateTheme;

use MigrateTheme\PluginAjax;

defined('ABSPATH') || exit;

class Assets
{
	protected static $instance = null;

	protected static $assets = [];

	protected static $external = [];


	private function __construct()
	{
		add_action('admin_enqueue_scripts', [__CLASS__, 'admin_enqueue_scripts']);
	}

	public static function instance()
	{
		// If the single instance hasn't been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function admin_enqueue_scripts($hook_suffix)
	{
		if (!isset($hook_suffix)) {
			return;
		}

		$screen = get_current_screen();

		if ($hook_suffix == $screen->id) {
			foreach (self::$assets as $name) {
				self::loadAsset($name, 'internal');
			}

			foreach (self::$external as $name) {
				self::loadAsset($name, 'external');
			}
		}
	}

	public function loadInternalAssets($assets)
	{
		self::$assets = $assets;

		return $this;
	}

	public function loadExternalAssets($assets)
	{
		self::$external = $assets;

		return $this;
	}

	private static function loadAsset($name, $type = 'internal')
	{
		$fileName  = pathinfo($name, PATHINFO_FILENAME);
		$extension = pathinfo($name, PATHINFO_EXTENSION);
		$assetUrl  = $type === 'internal' ? WPMT_PLUGIN_URL . "assets/$extension/$name" : $name;
		$assetName = "wpmt-$fileName";

		switch ($extension) {
			case 'css':
				wp_enqueue_style($assetName, $assetUrl, [], WPMT_PLUGIN_VERSION);
				break;
			case 'js':
				wp_enqueue_script($assetName, $assetUrl, ['jquery'], WPMT_PLUGIN_VERSION);
				break;
		}

		if ($type === 'internal') {
			wp_localize_script($assetName, 'wpmt_vars', array(
				'endpoint' 		=> PluginAjax::get_endpoint(),
				'ajax_nonce' 	=> wp_create_nonce('ajax_nonce'),
			));
		}
	}
}
