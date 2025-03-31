<?php
$storage_option = get_option('rti_viewer_storage_option') ?: 'local';
$google_drive_client_id = get_option('rti_viewer_google_drive_client_id') ?: '';
$google_drive_client_secret = get_option('rti_viewer_google_drive_client_secret') ?: '';
$google_drive_refresh_token = get_option('rti_viewer_google_drive_refresh_token') ?: '';
$google_drive_folder_id = get_option('rti_viewer_google_drive_folder_id') ?: '';
$default_height = get_option('rti_viewer_default_height') ?: '500';
?>

<h2><?php echo __('RTI Viewer Settings'); ?></h2>

<div class="field">
    <div class="two columns alpha">
        <label for="rti_viewer_default_height"><?php echo __('Default Viewer Height'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <input type="text" class="textinput" name="rti_viewer_default_height" value="<?php echo $default_height; ?>">
        <p class="explanation"><?php echo __('Default height for RTI viewers in pixels'); ?></p>
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
        <label for="rti_viewer_storage_option_google_drive">
            <input type="radio" name="rti_viewer_storage_option" id="rti_viewer_storage_option_google_drive" value="google_drive" <?php echo ($storage_option == 'google_drive') ? 'checked' : ''; ?>>
            <?php echo __('Hybrid Storage (Large files on Google Drive, small files on server)'); ?>
        </label>
        <p class="explanation"><?php echo __('Choose where to store RTI files. Hybrid storage saves server space but requires Google Drive API setup.'); ?></p>
    </div>
</div>

<div id="google-drive-settings" style="<?php echo ($storage_option == 'google_drive') ? '' : 'display: none;'; ?>">
    <h3><?php echo __('Google Drive API Settings'); ?></h3>
    
    <div class="field">
        <div class="two columns alpha">
            <label for="rti_viewer_google_drive_client_id"><?php echo __('Client ID'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <input type="text" class="textinput" name="rti_viewer_google_drive_client_id" value="<?php echo $google_drive_client_id; ?>">
            <p class="explanation"><?php echo __('Google Drive API Client ID from Google Cloud Console'); ?></p>
        </div>
    </div>
    
    <div class="field">
        <div class="two columns alpha">
            <label for="rti_viewer_google_drive_client_secret"><?php echo __('Client Secret'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <input type="password" class="textinput" name="rti_viewer_google_drive_client_secret" value="<?php echo $google_drive_client_secret; ?>">
            <p class="explanation"><?php echo __('Google Drive API Client Secret from Google Cloud Console'); ?></p>
        </div>
    </div>
    
    <div class="field">
        <div class="two columns alpha">
            <label for="rti_viewer_google_drive_folder_id"><?php echo __('Root Folder ID'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <input type="text" class="textinput" name="rti_viewer_google_drive_folder_id" value="<?php echo $google_drive_folder_id; ?>">
            <p class="explanation"><?php echo __('ID of the Google Drive folder where RTI files will be stored. This is the string of characters in the folder URL.'); ?></p>
        </div>
    </div>
    
    <div class="field">
        <div class="two columns alpha">
            <label for="rti_viewer_google_drive_refresh_token"><?php echo __('Refresh Token'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <input type="password" class="textinput" name="rti_viewer_google_drive_refresh_token" value="<?php echo $google_drive_refresh_token; ?>">
            <p class="explanation"><?php echo __('Google Drive API Refresh Token for long-term access'); ?></p>
        </div>
    </div>
    
    <?php if (empty($google_drive_refresh_token) && !empty($google_drive_client_id) && !empty($google_drive_client_secret)): ?>
    <div class="field">
        <div class="two columns alpha">
            <label><?php echo __('Authorization'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <a href="<?php echo url('rti-viewer/auth/google'); ?>" class="big green button"><?php echo __('Authorize Google Drive Access'); ?></a>
            <p class="explanation"><?php echo __('Click to authorize this plugin to access your Google Drive. You will need to complete this step before using Google Drive storage.'); ?></p>
        </div>
    </div>
    <?php elseif (!empty($google_drive_refresh_token)): ?>
    <div class="field">
        <div class="two columns alpha">
            <label><?php echo __('Authorization Status'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <p style="color: green;"><?php echo __('Google Drive authorized successfully'); ?></p>
            <a href="<?php echo url('rti-viewer/auth/revoke'); ?>" class="small red button"><?php echo __('Revoke Authorization'); ?></a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="field">
        <div class="two columns alpha">
            <label><?php echo __('Test Connection'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <a href="<?php echo url('rti-viewer/auth/test'); ?>" class="small blue button"><?php echo __('Test Google Drive Connection'); ?></a>
            <p class="explanation"><?php echo __('Check if your Google Drive API credentials are working correctly'); ?></p>
        </div>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('RTI Datasets'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <h4><?php echo __('Available RTI datasets:'); ?></h4>
        <table>
            <thead>
                <tr>
                    <th><?php echo __('Name'); ?></th>
                    <th><?php echo __('Storage'); ?></th>
                    <th><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $dir = dirname(dirname(__FILE__)) . '/rti_files';
                if (is_dir($dir)) {
                    $datasets = array_diff(scandir($dir), array('..', '.'));
                    if (count($datasets) > 0) {
                        foreach ($datasets as $dataset) {
                            if (is_dir($dir . '/' . $dataset)) {
                                $storageType = get_option('rti_viewer_dataset_' . $dataset . '_storage') ?: 'local';
                                echo '<tr>';
                                echo '<td>' . $dataset . '</td>';
                                echo '<td>' . ($storageType == 'local' ? 'Local' : 'Hybrid') . '</td>';
                                echo '<td>';
                                echo '<a href="' . html_escape(WEB_PUBLIC . '/plugins/RTIViewer/viewer.html?dataset=' . urlencode($dataset)) . '" target="_blank" class="small green button">View</a> ';
                                echo '<a href="' . url('rti-viewer/datasets/manage/id/' . $dataset) . '" class="small blue button">Manage</a> ';
                                echo '<a href="' . url('rti-viewer/datasets/delete/id/' . $dataset) . '" class="small red button" onclick="return confirm(\'' . __('Are you sure you want to delete this dataset?') . '\');">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    } else {
                        echo '<tr><td colspan="3">' . __('No datasets available') . '</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">' . __('RTI files directory not found') . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="<?php echo url('rti-viewer/datasets/add'); ?>" class="add-dataset big green button"><?php echo __('Add New RTI Dataset'); ?></a>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Embed Code Example'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div style="background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd; font-family: monospace;">
            &lt;iframe src="/omeka/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME style="width: 350px; height: 250px; border: none;" allowfullscreen&gt;&lt;/iframe&gt;
        </div>


        <p class="explanation"><?php echo __('Copy and paste this code into your Omeka pages or exhibits, replacing DATASET_NAME with the name of your RTI dataset.'); ?></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var storageRadios = document.querySelectorAll('input[name="rti_viewer_storage_option"]');
    var googleDriveSettings = document.getElementById('google-drive-settings');
    
    storageRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'google_drive') {
                googleDriveSettings.style.display = 'block';
            } else {
                googleDriveSettings.style.display = 'none';
            }
        });
    });
});
</script>