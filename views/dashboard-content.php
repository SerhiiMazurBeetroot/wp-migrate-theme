<h1 class="mb-3"><?php echo $theme; ?></h1>

<form id="generator" class="container-fluid" method="post" action="javascript:;">
	<?php if ($themeFiles) : ?>
		<?php $index = 1; ?>
		<h2 class="mb-3"><?php _e('Check/Uncheck All', WPMT_PLUGIN_SLUG); ?></h2>

		<div class="row">
			<div class="col-sm-2 mb-2">
				<div class="custom-control custom-checkbox">
					<input name="all[]" value="all" checked type="checkbox" class="custom-control-input select-all" id="all">
					<label class="custom-control-label" for="all"><?php _e('Check/Uncheck All', WPMT_PLUGIN_SLUG); ?></label>
				</div>
			</div>

			<?php foreach ($themeFiles as $group) : ?>
				<div class="col-sm-2 mb-2">
					<div class="custom-control custom-checkbox">
						<input name="all[]" value="<?php echo $group['type']; ?>" checked type="checkbox" class="custom-control-input select-type" id="<?php echo $group['type']; ?>-all">
						<label class="custom-control-label" for="<?php echo $group['type']; ?>-all"><?php echo $group['title']; ?></label>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="row">
			<?php foreach ($themeFiles as $group) : ?>
				<?php if ($group['files']) : ?>
					<?php $counter = 0; ?>
					<div class="col-sm-6 mt-3">
						<h2 class="mb-3"><?php echo ($index) . '. ' . $group['title'] ?></h2>
						<?php foreach ($group['files'] as $block) : ?>
							<div class="custom-control custom-checkbox <?php echo $counter > 8 ? 'hidden' : ''; ?>">
								<input checked name="<?php echo $group['type']; ?>[]" value="<?php echo $block; ?>" type="checkbox" class="custom-control-input single-input" id="<?php echo $group['type']; ?>-<?php echo $block; ?>">
								<label class="custom-control-label" for="<?php echo $group['type']; ?>-<?php echo $block; ?>"><?php echo $block; ?></label>
							</div>
							<?php $counter++; ?>
						<?php endforeach; ?>

						<?php if ($counter > 8) : ?>
							<button type="submit" id="<?php echo $group['type']; ?>-all" class="btn btn-success btn-sm show-all mt-3"><?php _e('Show all', WPMT_PLUGIN_SLUG); ?></button>
						<?php endif; ?>
					</div>
					<?php $index++; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="mt-5">
		<div class="row">
			<div class="col-3">
				<button type="submit" id="generateTheme" class="btn btn-success"><?php _e('Generate ZIP', WPMT_PLUGIN_SLUG); ?></button>
			</div>
		</div>
	</div>
</form>