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
        'config',        // Add this hook to process form submissions
        'public_head',
        'install',       // Good practice to include these for plugin setup
        'uninstall'      // Good practice to include these for plugin cleanup
    );

    /**
     * Add the OpenLIME JavaScript and CSS to the public theme header.
     */
    public function hookPublicHead($args)
    {
        $pluginWebDir = WEB_PLUGIN . '/RTIViewer';
        echo '<script type="text/javascript" src="' . $pluginWebDir . '/openlime/openlime.min.js"></script>';
        echo '<link rel="stylesheet" href="' . $pluginWebDir . '/openlime/skin.css">';
    }

    /**
     * Shows plugin configuration page.
     */
    public function hookConfigForm($args)
    {
        $view = $args['view'];
        include 'config_form.php';
    }

    /**
     * Process the configuration form.
     */
    public function hookConfig($args)
    {
        $post = $args['post'];
        // Process configuration options here
    }

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        // Set default options if needed
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        // Remove options if needed
    }
}