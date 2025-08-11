<?php
/**
 * RTI Upload Controller
 */
class RTIViewer_UploadController extends Omeka_Controller_AbstractActionController
{
    /**
     * Process uploaded RTI datasets
     */
    public function indexAction()
    {
        // debugging
        // $logFile = dirname(dirname(__FILE__)) . '/upload_debug.log';
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": ===== NEW UPLOAD REQUEST =====\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": FILES: " . print_r($_FILES, true) . "\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": PHP upload_max_filesize: " . ini_get('upload_max_filesize') . "\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": PHP post_max_size: " . ini_get('post_max_size') . "\n", FILE_APPEND);
        
        if ($this->getRequest()->isPost()) {
            if (empty($_POST) && empty($_FILES)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": ERROR: POST data is empty, likely exceeds post_max_size\n", FILE_APPEND);
                $this->redirect('rti-viewer' . '?error=' . urlencode(__('The upload failed because the file is too large. Maximum allowed size is ' . ini_get('upload_max_filesize'))));
                return;
            }
            
            $datasetName = isset($_POST['dataset']) ? trim($_POST['dataset']) : '';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": Dataset name: $datasetName\n", FILE_APPEND);
            
            if (empty($datasetName)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": ERROR: Empty dataset name\n", FILE_APPEND);
                $this->redirect('rti-viewer' . '?error=' . urlencode(__('The upload failed because the dataset name is required.')));
                return;
            }
            
            if (!preg_match('/^[a-zA-Z0-9._-]+$/', $datasetName)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": ERROR: Invalid dataset name\n", FILE_APPEND);
                $this->redirect('rti-viewer' . '?error=' . urlencode(__('The upload failed because the dataset name is invalid. Use only letters, numbers, dots, dashes, and underscores.')));
                return;
            }
            
            $uploadErrors = array(
                UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini (' . ini_get('upload_max_filesize') . ')',
                UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive',
                UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
            );
            
            if (empty($_FILES['zip_file'])) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": ERROR: No zip_file in FILES array\n", FILE_APPEND);
                $this->_helper->flashMessenger(__('No file was uploaded.'), 'error');
                $this->redirect('rti-viewer' . '?error=' . urlencode(__('The upload failed because no file was uploaded.')));
                return;
            }
            
            if ($_FILES['zip_file']['error'] !== UPLOAD_ERR_OK) {
                $errorCode = $_FILES['zip_file']['error'];
                $errorMessage = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : 'Unknown upload error (code: ' . $errorCode . ')';
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": ERROR: Upload error: $errorMessage\n", FILE_APPEND);
                $this->redirect('rti-viewer' . '?error=' . urlencode(__('File upload failed: %s', $errorMessage)));
                return;
            }
            
            try {
                $baseDir = dirname(dirname(__FILE__)) . '/rti_files';
                $datasetDir = $baseDir . '/' . $datasetName;
                
                if (!is_dir($baseDir)) {
                    if (!mkdir($baseDir, 0755, true)) {
                        throw new Exception('Failed to create base directory');
                    }
                }
                
                if (is_dir($datasetDir)) {
                    throw new Exception('A dataset with this name already exists');
                }
                
                if (!mkdir($datasetDir, 0755, true)) {
                    throw new Exception('Failed to create dataset directory');
                }
                
                $zipFile = $_FILES['zip_file'];
                $zipPath = $datasetDir . '/upload.zip';
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": Moving uploaded file to $zipPath\n", FILE_APPEND);
                if (!move_uploaded_file($zipFile['tmp_name'], $zipPath)) {
                    throw new Exception('Failed to move uploaded file');
                }
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": Opening ZIP file\n", FILE_APPEND);
                $zip = new ZipArchive();
                $zipResult = $zip->open($zipPath);
                if ($zipResult !== true) {
                    throw new Exception('Failed to open ZIP file (error code: ' . $zipResult . ')');
                }
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": Extracting ZIP contents\n", FILE_APPEND);
                if (!$zip->extractTo($datasetDir)) {
                    throw new Exception('Failed to extract ZIP file');
                }
                
                $zip->close();
                unlink($zipPath);
                
                $files = glob($datasetDir . '/*');
                $fileCount = count($files);
                
                set_option('rti_viewer_dataset_' . $datasetName . '_storage', 'local');
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": SUCCESS: Dataset created with $fileCount files\n", FILE_APPEND);
                $this->_helper->flashMessenger(__('Dataset "%s" created successfully with %s files', $datasetName, $fileCount), 'success');
                
            } catch (Exception $e) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
                $this->_helper->flashMessenger(__('Error: %s', $e->getMessage()), 'error');
            }
            
            $this->redirect('rti-viewer');
            return;
        }
        
        $this->redirect('rti-viewer');
    }
}