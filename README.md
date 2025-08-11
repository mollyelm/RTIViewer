# RTI Viewer Plugin for Omeka Classic

A plugin for displaying Reflectance Transformation Imaging (RTI) datasets in Omeka Classic using the OpenLIME viewer.

## Features

- **Upload RTI Datasets**: Upload ZIP files containing RTI image sets through the admin interface
- **Configurable Viewer Dimensions**: Set default width and height for embedded viewers
- **Flexible Embedding**: Use default dimensions or specify custom sizes for each viewer
- **Dataset Management**: View, upload, and delete RTI datasets from the admin panel
- **Manual Upload Support**: Instructions for uploading large datasets that exceed server limits
- **Interactive Viewing**: Full RTI functionality with light direction control via OpenLIME

## Server Requirements

- Omeka Classic 2.x or higher
- PHP 5.6 or higher with ZIP extension enabled
- Web server with sufficient storage space for your RTI data sets

## RTI Format Requirements

This plugin requires RTI datasets in **HSH (Hemispherical Harmonics)** format.

When exporting from Relight:
1. Go to Export → RTI
2. Select "HSH" as the format
3. Choose JPEG for web compatibility
4. Export all files
5. ZIP the exported folder for upload

**Note**: PTM, RBF, and other RTI formats are not currently supported.

## Installation

1. Download the plugin:
   - Click the `<> Code` button on GitHub
   - Select "Download ZIP"
   - Extract the downloaded file

2. Install the plugin:
   - Rename the extracted folder to `RTIViewer` (exactly - no spaces or variations)
   - Upload the `RTIViewer` folder to your Omeka installation's `plugins` directory
   - The path should be: `/your-omeka-installation/plugins/RTIViewer/`

3. Activate the plugin:
   - Log in to your Omeka admin panel
   - Navigate to Settings → Plugins
   - Find "RTI Viewer" in the list
   - Click "Install"

## Configuration

### Plugin Settings
Access via Settings → Plugins → RTI Viewer → Configure

- **Default Viewer Height**: Set the default height in pixels (e.g., 500)
- **Default Viewer Width**: Set the default width in pixels (e.g., 500)
- **Storage Method**: Currently supports local storage only

### Managing Datasets
Access via the "RTI Viewer" link in the admin navigation menu

From the management page, you can:
- View all uploaded RTI datasets
- Test datasets with the "View" button
- Delete datasets you no longer need
- Upload new datasets via ZIP file
- See your server's upload limit
- Access manual upload instructions for large files

## Usage

### Uploading RTI Datasets

1. Prepare your RTI files:
   - Include all image planes (plane_0.jpg, plane_1.jpg, etc.)
   - Include the info.json file with RTI parameters
   - Include normals files if available
   - Compress all files into a single ZIP file

2. Upload via admin interface:
   - Go to RTI Viewer in the admin menu
   - Enter a dataset name (letters, numbers, dots, dashes, underscores only)
   - Select your ZIP file
   - Click "Upload Dataset"

### Manual Upload for Large Datasets

If your ZIP file exceeds the server's upload limit:

1. Access your Omeka installation directory on your server
2. Navigate to `/plugins/RTIViewer/rti_files/`
3. Create a new folder with your dataset name:
   - Use only letters, numbers, dots, dashes, and underscores
   - No spaces or special characters
   - Example: `artifact_001`, `C.1.85.1`, `roman-coin-front`
4. Upload your RTI files directly into that folder
5. Refresh the RTI Viewer management page to see your dataset

### Embedding RTI Viewers

#### Using Default Dimensions
```html
<iframe src="http://yoursite.com/omeka/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME" 
        class="rti-viewer-default" 
        style="border: none;" 
        allowfullscreen></iframe>
```

#### Using Custom Dimensions
```html
<iframe src="http://yoursite.com/omeka/plugins/RTIViewer/viewer.html?dataset=DATASET_NAME" 
        style="width: 800px; height: 600px; border: none;" 
        allowfullscreen></iframe>
```

Replace `DATASET_NAME` with the name of your uploaded dataset.

## File Structure Requirements

Your RTI dataset should include:
```
dataset_name/
├── plane_0.jpg (or .tzi, .dzi)
├── plane_1.jpg
├── plane_2.jpg
├── ... (additional planes)
├── info.json (RTI configuration)
└── normals.jpg (optional, for enhanced rendering)
```

## Troubleshooting

### Upload Fails with Large Files
- Check your server's PHP upload limits
- Use the manual upload method described above
- Contact your hosting provider to increase limits

### "Not a Valid URL" Error
- Ensure the plugin folder is named exactly `RTIViewer`
- Check that viewer.html exists in the plugin directory
- Verify file permissions are correct

### Viewer Not Displaying
- Check browser console for JavaScript errors
- Ensure all RTI files are properly uploaded
- Verify the dataset name in the embed code matches exactly

## Server Requirements

To increase upload limits, add to your `.htaccess` file:
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 300
```

Or contact your hosting provider for assistance.

## Credits

- Created by Molly Moulton @ UMD Michelle Smith Collaboratory
- Uses [OpenLIME](https://openlime.githib.io/) for RTI visualization
- Built for Omeka Classic