<?php
echo head(array('title' => 'RTI Viewer Management'));
?>

<?php
if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
    echo '<div class="success" style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">' . $message . '</div>';
}

if (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
    echo '<div class="error" style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">' . $message . '</div>';
}
?>

<h2><?php echo __('RTI Viewer Dataset Management'); ?></h2>

<div class="field">
    <h3><?php echo __('Current Datasets'); ?></h3>
    <table>
        <thead>
            <tr>
                <th><?php echo __('Name'); ?></th>
                <th><?php echo __('Storage'); ?></th>
                <th><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($this->datasets) > 0): ?>
                <?php foreach ($this->datasets as $dataset): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dataset['name']); ?></td>
                        <td><?php echo ($dataset['storage'] == 'local' ? 'Local' : 'Hybrid'); ?></td>
                        <td style="white-space: nowrap;">
                            <a href="<?php echo html_escape(WEB_ROOT . '/plugins/RTIViewer/viewer.html?dataset=' . urlencode($dataset['name'])); ?>" 
                               target="_blank" class="small green button">View</a>
                            <a href="<?php echo admin_url('rti-viewer/delete'); ?>?delete_dataset=<?php echo urlencode($dataset['name']); ?>" 
                               class="small red button" 
                               onclick="return confirm('Are you sure you want to delete this dataset?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3"><?php echo __('No datasets available'); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<hr style="margin: 30px 0;">

<div class="field">
    <h3><?php echo __('Add New Dataset'); ?></h3>
    
    <?php
    if (function_exists('ini_get')) {
        $upload_max = ini_get('upload_max_filesize');
        $post_max = ini_get('post_max_size');
        ?>
        <div style="background-color: #fffbcc; border: 1px solid #e6db55; padding: 15px; margin-bottom: 15px; border-radius: 3px;">
            <h4 style="margin-top: 0; color: #666;"><?php echo __('Upload Information'); ?></h4>
            <p><strong><?php echo __('Current server upload limit:'); ?></strong> <?php echo $upload_max; ?></p>
            <p><?php echo __('If your ZIP file is larger than this limit, the upload will fail. This is a server-side PHP restriction.'); ?></p>
            
            <details style="margin-top: 10px;">
                <summary style="cursor: pointer; color: #2e7db2; font-weight: bold;"><?php echo __('Alternative: Manual Upload Instructions'); ?></summary>
                <div style="margin-top: 10px; padding: 10px; background: #f5f5f5; border-left: 3px solid #2e7db2;">
                    <p><?php echo __('To manually upload large datasets:'); ?></p>
                    <ol style="margin: 5px 0;">
                        <li><?php echo __('Access your Omeka installation directory on your server'); ?></li>
                        <li><?php echo __('Navigate to:'); ?> <code>/plugins/RTIViewer/rti_files/</code></li>
                        <li><?php echo __('Upload your RTI folder directly into '); ?> <code>rti_files</code></li>
                        <li><?php echo __('Refresh this page to see your dataset in the list above'); ?></li>
                    </ol>
                    <p style="margin-bottom: 0;"><em><?php echo __('Note: You can also ask your hosting provider to increase the PHP upload limit.'); ?></em></p>
                </div>
            </details>
        </div>
        <?php
    }
    ?>
    
    <div style="padding: 15px; border: 1px solid #ccc; background: #f9f9f9;">
        <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('rti-viewer/upload'); ?>">
            <div class="field">
                <label for="rti_new_dataset_name"><?php echo __('Dataset Name:'); ?></label>
                <input type="text" name="dataset" id="rti_new_dataset_name" placeholder="e.g., C.1.85.1" required>
            </div>
            
            <div class="field">
                <label><?php echo __('Upload ZIP file:'); ?></label>
                <input name="zip_file" id="rti_zip_file" type="file" accept=".zip" required>
                <p class="explanation"><?php echo __('Upload a ZIP file containing your RTI dataset files'); ?></p>
            </div>
            
            <div class="field">
                <input type="submit" class="submit big green button" value="<?php echo __('Upload Dataset'); ?>">
            </div>
        </form>
    </div>
</div>

<hr style="margin: 30px 0;">

<div class="field">
    <h3><?php echo __('Configuration'); ?></h3>
    <p><?php echo __('To configure default viewer settings, please visit the '); ?>
    <a href="<?php echo admin_url('plugins/config?name=RTIViewer'); ?>"><?php echo __('RTI Viewer Plugin Configuration page'); ?></a>
    </p>
</div>

<div class="field">
    <h3><?php echo __('Embed Code'); ?></h3>
    
    <p><?php echo __('Use this code to embed RTI viewers in your Omeka pages. The viewer will automatically use your configured default dimensions (' . $this->default_width . 'x' . $this->default_height . 'px).'); ?></p>
    
    <div style="background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd; font-family: monospace; overflow-x: auto;">
        &lt;iframe src="<?php echo html_escape(WEB_ROOT . '/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME'); ?>" 
                class="rti-viewer-default" 
                style="border: none;" 
                allowfullscreen&gt;&lt;/iframe&gt;
    </div>
    
    <p class="explanation"><?php echo __('Replace DATASET_NAME with the name of your RTI dataset.'); ?></p>
    
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

<style>
iframe.rti-viewer-default {
    width: <?php echo htmlspecialchars($this->default_width); ?>px !important;
    height: <?php echo htmlspecialchars($this->default_height); ?>px !important;
}
</style>

<?php echo foot(); ?>