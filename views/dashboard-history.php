<?php

use MigrateTheme\PluginHistory;


?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h1>ZIP Archive</h1>

    <?php
    $history = new PluginHistory();
    $history->get_archive_list(WPMT_PLUGIN_UPLOADS);

    isset($_POST['ajax_nonce']) && isset($_POST['action']) && $history->delete_history(WPMT_PLUGIN_UPLOADS);
    ?>

    <div class="panel">
        <div class="panel-content">
            <?php if ($history->files) : ?>
                <form name="form1" method="post" action="">
                    <?php wp_nonce_field('form-settings'); ?>
                    <table class="wp-list-table widefat" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" class="manage-column"></th>
                                <th scope="col" class="manage-column"><?php _e('File', WPMT_PLUGIN_SLUG); ?></th>
                                <th scope="col" class="manage-column"><?php _e('Created', WPMT_PLUGIN_SLUG); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $history->generate_table(); ?>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="error_wrap"></div>
                    <div id="submit-wrap">
                        <?php wp_nonce_field('delete_archive_history', 'ajax_nonce'); ?>
                        <input type="hidden" name="action" value="delete_archive_history" />
                        <button id="delete_archive_history" type="submit" class="btn btn-danger mt-3"><?php _e('Delete All', WPMT_PLUGIN_SLUG); ?>
                        </button>

                        <div id="loader"></div>
                    </div>
                </form>
            <?php else : ?>
                <h2><?php _e('No files', WPMT_PLUGIN_SLUG); ?></h2>
            <?php endif; ?>
        </div>
    </div>
</div>