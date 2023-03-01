<div class="wrap">
	<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="available_theme">
		<option value=""><?php _e('Select theme', WPMT_PLUGIN_SLUG); ?></option>
		<?php foreach (wp_get_themes() as $key => $value) : ?>
			<option value="<?php echo $key; ?>"><?php echo ucfirst($key); ?></option>
		<?php endforeach; ?>
	</select>

	<div class="panel">
		<div class="panel-content">
		</div>

		<svg id="loader" class="loader" viewBox="0 0 50 50">
			<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
		</svg>
	</div>
</div>