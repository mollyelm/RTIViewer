<?php
/**
 * RTI Viewer Index Controller
 */
class RTIViewer_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction()
    {
        $this->view->storage_option = get_option('rti_viewer_storage_option') ?: 'local';
        $this->view->default_height = get_option('rti_viewer_default_height') ?: '500';
        $this->view->default_width = get_option('rti_viewer_default_width') ?: '500';

        $this->view->datasets = $this->_getDatasets();
    }
    
    private function _getDatasets()
    {
        $datasets = array();
        $dir = dirname(dirname(__FILE__)) . '/rti_files';
        
        if (is_dir($dir)) {
            $items = array_diff(scandir($dir), array('..', '.'));
            foreach ($items as $item) {
                if (is_dir($dir . '/' . $item)) {
                    $datasets[] = array(
                        'name' => $item,
                        'storage' => get_option('rti_viewer_dataset_' . $item . '_storage') ?: 'local'
                    );
                }
            }
        }
        
        return $datasets;
    }
}