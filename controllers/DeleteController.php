<?php
/**
 * RTI Delete Controller
 */
class RTIViewer_DeleteController extends Omeka_Controller_AbstractActionController
{
    /**
     * Process deleting RTI datasets
     */
    public function indexAction()
    {
        // debugging
        // $logFile = dirname(dirname(__FILE__)) . '/delete_debug.log';
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": ===== NEW DELETE REQUEST =====\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
        // file_put_contents($logFile, date('Y-m-d H:i:s') . ": GET: " . print_r($_GET, true) . "\n", FILE_APPEND);
        
        $datasetName = '';
        if ($this->getRequest()->isPost()) {
            $datasetName = isset($_POST['delete_dataset']) ? trim($_POST['delete_dataset']) : '';
        } else {
            $datasetName = $this->getParam('delete_dataset') ?: '';
        }
        
        file_put_contents($logFile, date('Y-m-d H:i:s') . ": Dataset name to delete: $datasetName\n", FILE_APPEND);
        
        if (empty($datasetName)) {
            $this->_helper->flashMessenger(__('Dataset name is required for deletion.'), 'error');
            $this->redirect('rti-viewer');
            return;
        }
        
        try {
            $baseDir = dirname(dirname(__FILE__)) . '/rti_files';
            $datasetDir = $baseDir . '/' . $datasetName;
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": Dataset directory: $datasetDir\n", FILE_APPEND);
            
            if (!is_dir($datasetDir)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . ": Directory does not exist\n", FILE_APPEND);
                throw new Exception('Dataset directory does not exist');
            }
            
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": Attempting to delete directory\n", FILE_APPEND);
            
            system('rm -rf ' . escapeshellarg($datasetDir), $returnCode);
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": System delete command returned: $returnCode\n", FILE_APPEND);
            
            if ($returnCode !== 0) {
                throw new Exception('Failed to delete directory (system command failed)');
            }
            
            delete_option('rti_viewer_dataset_' . $datasetName . '_storage');
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": SUCCESS: Dataset deleted\n", FILE_APPEND);
            $this->_helper->flashMessenger(__('Dataset "%s" deleted successfully', $datasetName), 'success');
            
        } catch (Exception $e) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . ": EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
            $this->_helper->flashMessenger(__('Error: %s', $e->getMessage()), 'error');
        }
        
        $this->redirect('rti-viewer');
        return;
    }
}