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
jimport( 'joomla.version' );

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
class JPackageModelCore extends JModel
{
    /**
     * @var     object
     * @access  protected
     */
    var $_db;

	/**
     * @var     int current version
     * @access  protected
	 */
    var $_current;
    
    /**
     * @var     int update version
     * @access  protected
     */
    var $_version;
    
    /**
     * @var     int
     * @access  protected
     */
    var $_site;
    
    /**
     * Default constructor
     *
     * @param   object &$dbo A JDatabase object
     * @since   1.5
     */
    function __construct( &$dbo )
    {
    	$this->_db      = & $dbo;
        $this->_current = JVersion::RELEASE . JVersion::DEV_LEVEL;
        $this->_version = JRequest::getVar( 'version', $this->_current, 'default', 'INT' );
        $this->_site    = JRequest::getVar( 'site', 1, 'default', 'INT' );
    }
    
    /**
     * @return  array the available updates
     */
    function getCore()
    {
        $xmlrpc     = JPackageXmlRpc::getInstance( $this->_site );
        $core       = $xmlrpc->getCore( $this->_current );
        
        return $core;
    }
    
    /**
     * Updates the core
     * 
     * @return  string success or error message
     */
    function updateCore()
    {
    	// get the core information
        $core = $this->getCore();
        $update = $core[$this->_version];
        // download the update
        $update_download = JInstallerHelper::downloadPackage( $update['url'] );
        if ($update_download) {
            // get file information and check it
            $update_file        = JPATH_SITE . DS . 'tmp' . DS . $update_download;
            $update_checksum    = md5_file( $update_file );
            $update_filesize    = filesize( $update_file );
            if (($update_checksum == $update['update_checksum']) && ($update_filesize == $update['update_filesize'])) {
                // unpack the update
                $update_package = JInstallerHelper::unpack( $update_file, true );
                // download the xml
                $xml_download = JInstallerHelper::downloadPackage( $update['url'] );
                if ($xml_download) {
                    // get file information and check it
                    $xml_file       = JPATH_SITE . DS . 'tmp' . DS . $xml_download;
                    $xml_checksum   = md5_file( $xml_file );
                    $xml_filesize   = filesize( $xml_file );
                    if (($xml_checksum == $update['xml_checksum']) && ($xml_filesize == $update['xml_filesize'])) {
                        $installer = & JInstaller::getInstance( $this->_db, 'core' );
                        // install
                        if ($installer->update( $xml_file )) {
                        	// cleanup (passing a file as the second parameter allows for a directory to not be deleted)
                            JInstallerHelper::cleanupInstall( $update_file, $update_file );
                            JInstallerHelper::cleanupInstall( $xml_file, $xml_file );
                            return ($update['name'] . ' updated successfully <br />' . "\n");
                        } else {
                            return ('Error updating ' . $update['name'] . '<br />' . "\n");
                        }
                    } else {
                        return ('Error: incorrect checksum and/or filesize for ' . $update['name'] . ' XML setup file.<br />' . "\n");
                    }
                } else {
                    return ('Error downloading ' . $update['name'] . ' XML setup file.<br />' . "\n");
                }
            } else {
                return ('Error: incorrect checksum and/or filesize for ' . $update['name'] . ' update file.<br />' . "\n");
            }
        } else {
            return ('Error downloading ' . $update['name'] . ' update file.<br />' . "\n");
        }
    }
}
