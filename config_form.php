<?php
    $default_height = get_option('rti_viewer_default_height') ?: '500';
    $default_width = get_option('rti_viewer_default_width') ?: '500';
    $storage_option = get_option('rti_viewer_storage_option') ?: 'local';
?>

<h2><?php echo __('RTI Viewer Settings'); ?></h2>

<div class="field">
    <div class="two columns alpha">
        <label for="rti_viewer_default_height"><?php echo __('Default Viewer Height'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <input type="text" class="textinput" name="rti_viewer_default_height" id="rti_viewer_default_height" value="<?php echo htmlspecialchars($default_height); ?>">
        <p class="explanation"><?php echo __('Default height in pixels for the RTI viewer'); ?></p>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label for="rti_viewer_default_width"><?php echo __('Default Viewer Width'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <input type="text" class="textinput" name="rti_viewer_default_width" id="rti_viewer_default_width" value="<?php echo htmlspecialchars($default_width); ?>">
        <p class="explanation"><?php echo __('Default width in pixels for the RTI viewer'); ?></p>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Storage Method'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <label for="rti_viewer_storage_option_local">
            <input type="radio" name="rti_viewer_storage_option" id="rti_viewer_storage_option_local" value="local" <?php echo ($storage_option == 'local') ? 'checked' : ''; ?>>
            <?php echo __('Local Storage (All files stored on server)'); ?>
        </label>
        <br>
        <label for="rti_viewer_storage_option_google_drive">
            <input type="radio" name="rti_viewer_storage_option" id="rti_viewer_storage_option_google_drive" value="google_drive" <?php echo ($storage_option == 'google_drive') ? 'checked' : ''; ?> disabled>
            <?php echo __('Hybrid Storage (Large files on Google Drive, small files on server)'); ?>
            <span style="color: #999; font-style: italic;"> - Service not available yet</span>
        </label>
    </div>
</div>

<hr style="margin: 30px 0;">

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Dataset Management'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p><?php echo __('To manage RTI datasets, please visit the '); ?>
        <a href="<?php echo admin_url('rti-viewer'); ?>"><?php echo __('RTI Viewer Management page'); ?></a>
        </p>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Embed Code Example'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div style="background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd; font-family: monospace; overflow-x: auto;">
            &lt;iframe src="<?php echo html_escape(WEB_ROOT . '/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME'); ?>" class="rti-viewer-default" style="border: none;" allowfullscreen&gt;&lt;/iframe&gt;
        </div>
        <p class="explanation"><?php echo __('This embed code will use your configured default dimensions. Copy and paste into your Omeka pages or exhibits, replacing DATASET_NAME with the name of your RTI dataset.'); ?></p>
    </div>

    <details style="margin-top: 10px;">
        <summary style="cursor: pointer; color: #2e7db2;"><?php echo __('Advanced: Custom dimensions'); ?></summary>
        <div style="margin-top: 10px;">
            <p><?php echo __('To use custom dimensions, add width and height to the style attribute:'); ?></p>
            <div style="background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd; font-family: monospace; overflow-x: auto;">
                &lt;iframe src="<?php echo html_escape(WEB_ROOT . '/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME'); ?>" 
                        style="width: 800px; height: 600px; border: none;" 
                        allowfullscreen&gt;&lt;/iframe&gt;
            </div>
        </div>
    </details>
</div>