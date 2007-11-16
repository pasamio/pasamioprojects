<?php
/**
 * @version $Id: install.php 202 2006-08-20 07:05:55Z schmalls $
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
class JPackageModelInstall extends JModel
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
     * @var     int
     * @access  protected
     */
    var $_id;
    
    /**
     * Default constructor
     *
     * @param   object &$dbo A JDatabase object
     * @since   1.5
     */
    function __construct( &$dbo )
    {
    	$this->_db      = & $dbo;
        $this->_site    = JRequest::getVar( 'site', 1, 'default', 'INT' );
        $this->_id      = JRequest::getVar( 'id', 0, 'default', 'INT' );
    }
    
    /**
     * @return      int the package id
     */
    function getPackageId()
    {
    	return $this->_id;
    }
    
    /**
     * @return      array the package's dependencies
     */
    function getDependencies()
    {
        static $dependencies;
        
        if(!$dependencies) {
            $xmlrpc         = JPackageXmlRpc::getInstance( $this->_site );
            $dependencies   = array();
            $this->_getDependencies( $xmlrpc, $dependencies, $this->_id );
        }
        
        return $dependencies;
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
     * Does the installation
     * 
     * @param   array $installed the currently installed extensions
     * @return  string success or error message
     */
    function doInstall( $installed )
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
