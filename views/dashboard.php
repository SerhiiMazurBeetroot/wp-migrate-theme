<?php

// Prevent direct access.
if (!defined('WPMT_PLUGIN_PATH')) exit;

// Determines which tab to display.
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'themes';
?>

<div class="wrap">

	<div class="header">
		<?php settings_errors(); ?>
	</div>

	<div class="nav-tab-wrapper">
		<ul>
			<li><a href="/wp-admin/admin.php?page=<?php echo WPMT_PLUGIN_SLUG; ?>&tab=themes" class="nav-tab <?php echo $active_tab == 'themes' ? 'nav-tab-active' : ''; ?>"><?php _e('Themes', WPMT_PLUGIN_SLUG); ?></a></li>
			<li><a href="/wp-admin/admin.php?page=<?php echo WPMT_PLUGIN_SLUG; ?>&tab=history" class="nav-tab <?php echo $active_tab == 'history' ? 'nav-tab-active' : ''; ?>"><?php _e('History', WPMT_PLUGIN_SLUG); ?></a></li>
		</ul>
	</div>

	<div class="dashboard-wrap">
		<?php
		// Include the correct tab template.
		$page_template = str_replace('_', '-', sanitize_file_name($active_tab)) . '.php';

		if (isset($_GET['error_message'])) {
			add_action('admin_notices', array($this, 'settingsPageSettingsMessages'));
			do_action('admin_notices', $_GET['error_message']);
		}

		if (file_exists(WPMT_PLUGIN_PATH . '/views/dashboard-' . $page_template)) {
			include WPMT_PLUGIN_PATH . '/views/dashboard-' . $page_template;
		} else {
			include WPMT_PLUGIN_PATH . '/views/dashboard-themes.php';
		}
		?>
	</div>
</div>