<?php
/**
 * @version $Id: core.php 136 2006-07-29 22:53:53Z schmalls $
 * @package JInstallManager
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport( 'joomla.application.model' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.installer.installer' );

/**
 * Include JPackage XML-RPC
 */
require_once( JPATH_COM_JPACKAGE . DS . 'helpers' . DS . 'xmlrpc.php');

/**
 * @author      Joshua Thompson
 * @package     Joomla
 * @subpackage  JPackage
 * @since       1.5
 */
class JPackageModelUpdates extends JModel
{
    /**
     * @var     object
     * @access  protected
     */
    var $_db;
    
    /**
     * @var     int
     * @access  protected
     */
    var $_site;
    
    /**
     * @var     array
     * @access  protected
     */
    var $_package_ids;
    
    /**
     * Default constructor
     *
     * @param   object &$dbo A JDatabase object
     * @since   1.5
     */
    function __construct( &$dbo )
    {
        $this->_db          = & $dbo;
        $this->_site        = JRequest::getVar( 'site', 1, 'default', 'INT' );
        $this->_package_ids = JRequest::getVar( 'package_ids', array(), 'default', 'ARRAY' );
    }
    
    /**
     * Gets the installed extensions
     * 
     * @return  array
     */
    function getExtensions()
    {        
        $extensions = array();
        $extensions['component'] = $this->_getComponents();
        $extensions['module']    = $this->_getModules();
        $extensions['plugin']    = $this->_getPlugins();
        $extensions['template']  = $this->_getTemplates();
        $extensions['language']  = $this->_getLanguages();
        
        return $extensions;
    }
    
    /**
     * Gets the available updates
     * 
     * @return  array
     */
    function getUpdates()
    {
        // set infinite time limit, this could take a while
        set_time_limit( 0 );
         
        $xmlrpc = JPackageXmlRpc::getInstance( $this->_site );
        
        $extensions = $this->getExtensions();
        
        $updates = array();
        foreach ($extensions as $type => $extensions2) {
            foreach ($extensions2 as $extension) {
                $update = $xmlrpc->getLatest( $type, $extension['name'], $extension['version'] );
                if (($update !== false)) {
                    $update['cur_version'] = $extension['version'];
                    $updates[$type][] = $update;
                }
            }
        }
        
        return $updates;
    }
    
    /**
     * Gets the package ids
     * 
     * @return  array
     */
    function getPackageIds()
    {
        return $this->_package_ids;
    }
    
    /**
     * Gets the extensions that will be installed for the update
     * 
     * @return  array
     */
    function getDependencies()
    {
        // set infinite time limit, this could take a while
        set_time_limit( 0 );
         
        $xmlrpc         = JPackageXmlRpc::getInstance( $this->_site );
        $dependencies   = array();
        
        foreach ($this->_package_ids as $package_id) {
            $this->_getDependencies( $xmlrpc, $dependencies, $package_id );
        }
        
        return $dependencies;
    }
    
    /**
     * Gets the extensions that will be installed for the update
     * 
     * @param   array $installed the currently installed extensions
     * @return  array
     */
    function doUpdateInstall( $installed )
    {
        // get dependencies
        $dependencies = $this->getDependencies();
        $dependencies = $this->reduceDependencies( $dependencies,
            array_merge( $installed['component'],
                $installed['module'],
                $installed['plugin'],
                $installed['language'],
                $installed['template'] ) );
        // install each dependency
        $message = '';
        foreach ($dependencies as $dependency) {
            // download package
            $download = JInstallerHelper::downloadPackage( $dependency['url'] );
            if ($download) {
                // get file information and check it
                $file      = JPATH_SITE . DS . 'tmp' . DS . $download;
                $checksum  = md5_file( $file );
                $filesize  = filesize( $file );
                if (($checksum == $dependency['checksum']) && ($filesize == $dependency['filesize'])) {
                    // unpack the package and initialize the installer
                    $package = JInstallerHelper::unpack( $file );
                    $installer = & JInstaller::getInstance( $this->_db, $package['type'] );
                    // install
                    if ($installer->install( $package['dir'] )) {
                        $message .= $dependency['name'] . ' installed successfully, ' . "\n";
                    } else {
                        $message .= 'Error installing ' . $dependency['name'] . ', ' . "\n";
                    }
                    // cleanup
                    JInstallerHelper::cleanupInstall( $file, $package['extractdir'] );
                } else {
                    $message .= 'Error: incorrect checksum and/or filesize for ' . $dependency['name'] . ', ' . "\n";
                }
            } else {
                $message .= 'Error downloading ' . $dependency['name'] . ', ' . "\n";
            }
        }
        
        return $message;
    }
    
    /**
     * Gets the currently installed component details
     * 
     * @access  private
     * @return  array
     */
    function _getComponents()
    {
        $query = 'SELECT `option` FROM #__components WHERE parent = 0 AND iscore = 0 ORDER BY name';
        $this->_db->setQuery( $query );
        $components = $this->_db->loadAssocList();
        if ($this->_db->getErrorNum()) {
            echo $this->_db->stderr();
            return false;
        }
        
        $baseDir = JPATH_ADMINISTRATOR . DS . 'components';
        
        $result = array();
        foreach ($components as $component) {

            // get the component folder and list of xml files in folder
            $folder = $baseDir .DS . $component['option'];
            if (JFolder::exists( $folder )) {
                $xmlFilesInDir = JFolder::files( $folder, '.xml$' );
            } else {
                $xmlFilesInDir = null;
            }
            foreach ($xmlFilesInDir as $xmlfile) {
                if ($data = JApplicationHelper::parseXMLInstallFile( $folder . DS . $xmlfile )) {
                    $result[$data['name']] = $data;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Gets the currently installed module details
     * 
     * @access  private
     * @return  array
     */
    function _getModules()
    {
        $query = 'SELECT client_id, module FROM #__modules WHERE module LIKE \'mod_%\' AND iscore = 0 ORDER BY client_id, module';
        $this->_db->setQuery( $query );
        $modules = $this->_db->loadAssocList();
        if ($this->_db->getErrorNum()) {
            echo $this->_db->stderr();
            return false;
        }
        
        $result = array();
        foreach ($modules as $module) {
            // path to module directory
            if ($module['client_id'] == '1') {
                $moduleBaseDir = JPATH_ADMINISTRATOR . DS . 'modules';
            } else {
                $moduleBaseDir = JPATH_SITE . DS . 'modules';
            }

            // xml file for module
            $xmlfile = $moduleBaseDir . DS . $module['module'] . DS . $module['module'] . '.xml';

            if (file_exists( $xmlfile ))
            {
                if ($data = JApplicationHelper::parseXMLInstallFile( $xmlfile )) {
                    $result[$data['name']] = $data;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Gets the currently installed plugin details
     * 
     * @access  private
     * @return  array
     */
    function _getPlugins()
    {
        $query = 'SELECT folder, element FROM #__plugins ORDER BY iscore, folder, name';
        $this->_db->setQuery( $query );
        $plugins = $this->_db->loadAssocList();
        if ($this->_db->getErrorNum()) {
            echo $this->_db->stderr();
            return false;
        }
        
        $baseDir = JPATH_SITE . DS . 'plugins';

        $result = array();
        foreach($plugins as $plugin) {
            $xmlfile = $baseDir . DS . $plugin['folder'] . DS . $plugin['element'] . '.xml';

            if (file_exists( $xmlfile ))
            {
                if ($data = JApplicationHelper::parseXMLInstallFile( $xmlfile )) {
                    $result[$data['name']] = $data;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Gets the currently installed template details
     * 
     * @access  private
     * @return  array
     */
    function _getTemplates()
    {
        // get the site templates
        $templateDirs = JFolder::folders( JPATH_SITE . DS . 'templates' );
        foreach ($templateDirs as $templateDir) {
            $template = array();
            $template['folder']     = $templateDir;
            $template['client']     = 0;
            $template['baseDir']    = JPATH_SITE . DS . 'templates';

            $templates[] = $template;
        }
        
        // get the admin templates
        $templateDirs = JFolder::folders( JPATH_ADMINISTRATOR . DS . 'templates' );
        foreach ($templateDirs as $templateDir) {
            $template = array();
            $template['folder']     = $templateDir;
            $template['client']     = 1;
            $template['baseDir']    = JPATH_ADMINISTRATOR . DS . 'templates';

            $templates[] = $template;
        }

        $result = array();
        foreach($templates as $template) {
            $dirName = $template['baseDir'] . DS . $template['folder'];
            $xmlFilesInDir = JFolder::files( $dirName, '.xml$' );

            foreach($xmlFilesInDir as $xmlfile) {
                if ($data = JApplicationHelper::parseXMLInstallFile( $dirName . DS. $xmlfile )) {
                    $results[$data['name']] = $data;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Gets the currently installed language details
     * 
     * @access  private
     * @return  array
     */
    function _getLanguages()
    {
        $languages = array();
        // get the site languages
        $langBDir = JLanguage::getLanguagePath( JPATH_SITE );
        $langDirs = JFolder::folders( $langBDir );
        foreach ($langDirs as $langDir) {
            $lang = array();
            $lang['folder']     = $langDir;
            $lang['client']     = 0;
            $lang['baseDir']    = $langBDir;

            $languages[] = $lang;
        }
        
        // get the admin languages
        $langBDir = JLanguage::getLanguagePath( JPATH_ADMINISTRATOR );
        $langDirs = JFolder::folders( $langBDir );
        foreach ($langDirs as $langDir) {
            $lang = array();
            $lang['folder']     = $langDir;
            $lang['client']     = 1;
            $lang['baseDir']    = $langBDir;

            $languages[] = $lang;
        }

        $result = array();
        foreach ($languages as $language) {
            $files = JFolder::files( $language['baseDir'] . DS . $language['folder'], '^([-_A-Za-z]*)\.xml$' );
            foreach ($files as $file) {
                if ($data = JApplicationHelper::parseXMLLangMetaFile( $language['baseDir'] . DS . $language['folder'] . DS . $file )) {
                    $results[$data['name']] = $data;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Recursive function to get the package's dependencies
     * 
     * @param   object &$xmlrpc
     * @param   array &$dependencies
     * @param   int $id
     */
    function _getDependencies( &$xmlrpc, &$dependencies, $id )
    {
        $temp = $xmlrpc->getDependencies( $id );
        foreach ($temp as $package) {
            if (isset( $dependencies[$package['name']] )) {
                if ($dependencies[$package['name']]['version'] < $package['version']) {
                    $dependencies[$package['name']] = $package;
                    $this->_getDependencies( $xmlrpc, $dependencies, $package['id'] );
                }
            } else {
                $dependencies[$package['name']] = $package;
                $this->_getDependencies( $xmlrpc, $dependencies, $package['id'] );
            }
        }
    }
    
    /**
     * Reduces dependency array to only those that are needed
     * 
     * @param   array $array1
     * @param   array $array2
     * @return  array
     */
    function reduceDependencies( $array1, $array2 )
    {
        $result = array();
        foreach ($array1 as $key => $value) {
            if ((!isset( $array2[$key] )) || ($array2[$key]['version'] < $value['version'])) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}
