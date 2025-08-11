<?php
/**
 * RTIViewerPlugin
 *
 * created by Molly Moulton @ UMD Michelle Smith Collaboratory
 */
class RTIViewerPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'config_form',
        'config',
        'public_head',
        'admin_head',
        'install',
        'uninstall',
        'define_routes'
    );
    
    protected $_filters = array(
        'admin_navigation_main'
    );
    
    protected $_options = array(
        'rti_viewer_default_height' => '500',
        'rti_viewer_default_width' => '500',
        'rti_viewer_storage_option' => 'local',
    );
    
    public function hookPublicHead($args)
    {
        $pluginWebDir = WEB_PLUGIN . '/RTIViewer';
        echo '<script type="text/javascript" src="' . $pluginWebDir . '/openlime/openlime.min.js"></script>';
        echo '<link rel="stylesheet" href="' . $pluginWebDir . '/openlime/skin.css">';
        
        // Add CSS for default RTI viewer dimensions
        $defaultWidth = get_option('rti_viewer_default_width') ?: '500';
        $defaultHeight = get_option('rti_viewer_default_height') ?: '500';
        echo '<style>
            iframe.rti-viewer-default {
                width: ' . htmlspecialchars($defaultWidth) . 'px !important;
                height: ' . htmlspecialchars($defaultHeight) . 'px !important;
            }
        </style>';
    }
    
    public function hookAdminHead($args)
    {
        $defaultWidth = get_option('rti_viewer_default_width') ?: '500';
        $defaultHeight = get_option('rti_viewer_default_height') ?: '500';
        echo '<style>
            iframe.rti-viewer-default {
                width: ' . htmlspecialchars($defaultWidth) . 'px !important;
                height: ' . htmlspecialchars($defaultHeight) . 'px !important;
            }
        </style>';
    }
    
    public function hookConfigForm($args)
    {
        include 'config_form.php';
    }

    public function hookConfig($args)
    {
        set_option('rti_viewer_default_height', $_POST['rti_viewer_default_height']);
        set_option('rti_viewer_default_width', $_POST['rti_viewer_default_width']);
        set_option('rti_viewer_storage_option', $_POST['rti_viewer_storage_option']);
    }
    
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('RTI Viewer'),
            'uri' => admin_url('rti-viewer')
        );
        return $nav;
    }
    
    private function _rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->_rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
    
    public function hookInstall()
    {
        $this->_installOptions();
        $dir = dirname(__FILE__) . '/rti_files';
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    public function hookUninstall()
    {
        $this->_uninstallOptions();
    }

    public function hookDefineRoutes($args)
    {
        $router = $args['router'];
        
        $router->addRoute('rti_viewer_admin', new Zend_Controller_Router_Route(
            'admin/rti-viewer',
            array(
                'module' => 'RTIViewer',
                'controller' => 'index',
                'action' => 'index'
            )
        ));
        
        $router->addRoute('rti_viewer_upload', new Zend_Controller_Router_Route(
            'admin/rti-viewer/upload',
            array(
                'module' => 'RTIViewer',
                'controller' => 'upload',
                'action' => 'index'
            )
        ));
        
        $router->addRoute('rti_viewer_delete', new Zend_Controller_Router_Route(
            'admin/rti-viewer/delete',
            array(
                'module' => 'RTIViewer',
                'controller' => 'delete',
                'action' => 'index'
            )
        ));
    }
}