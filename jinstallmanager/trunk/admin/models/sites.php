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
class JPackageModelSites extends JModel
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
     * @var     string
     * @access  protected
     */
    var $_task;
    
    /**
     * @var     string
     * @access  protected
     */
    var $_return;
    
    /**
     * Default constructor
     *
     * @param   object &$dbo A JDatabase object
     * @since   1.5
     */
    function __construct( &$dbo )
    {
        $this->_db      = & $dbo;
        $this->_site    = JRequest::getVar( 'site', 0, 'default', 'INT' );
        $this->_task    = JRequest::getVar( 'task', '', 'default', 'STRING' );
        $this->_return  = JRequest::getVar( 'return', '', 'default', 'STRING' );
    }
    
    /**
     * Gets the task
     */
    function getTask()
    {
        return $this->_task;
    }
    
    /**
     * Gets the return task
     */
    function getReturn()
    {
        return $this->_return;
    }
        
    /**
     * Gets the site id
     */
    function getSite()
    {
        return $this->_site;
    }
    
    /**
     * Gets the sites
     */
    function getSites()
    {
        $sites = JPackageXmlRpc::getSites();
        
        return $sites;
    }
    
    /**
     * Adds the site
     */
    function addSite()
    {
        // get values
        $name       = JRequest::getVar( 'site_name', '', 'default', 'STRING' );
        $url        = JRequest::getVar( 'site_url', '', 'default', 'STRING' );
        $username   = JRequest::getVar( 'site_username', '', 'default', 'STRING' );
        $password   = JRequest::getVar( 'site_password', '', 'default', 'STRING' );
        $method     = JRequest::getVar( 'site_method', '', 'default', 'STRING' );
        // generate query
        $query = 'INSERT INTO #__jpackage_sites VALUES (null, \'' . 
            $name . '\', \'' .
            $url . '\', \'' .
            $username . '\', \'' .
            $password . '\', \'' .
            $method . '\');';
        $this->_db->setQuery( $query );
        $this->_db->query();
        if ($this->_db->getErrorNum()) {
            return JText::_( 'There was an error adding the site' ) . ': ' . $this->_db->stderr();
        } else {
            return JText::_( 'The site was added successfully' );
        }
    }
    
    /**
     * Edits the site
     */
    function editSite()
    {
        // get values
        $name       = JRequest::getVar( 'site_name', '', 'default', 'STRING' );
        $url        = JRequest::getVar( 'site_url', '', 'default', 'STRING' );
        $username   = JRequest::getVar( 'site_username', '', 'default', 'STRING' );
        $password   = JRequest::getVar( 'site_password', '', 'default', 'STRING' );
        $method     = JRequest::getVar( 'site_method', '', 'default', 'STRING' );
        // generate query
        $query = 'UPDATE #__jpackage_sites SET name = \'' . $name . 
            '\', url = \'' . $url . 
            '\', username = \'' .$username . 
            '\', password = \'' .$password . 
            '\', method = \'' . $method . 
            '\' WHERE site_id = ' . $this->_site . ';';
        $this->_db->setQuery( $query );
        $this->_db->query();
        if ($this->_db->getErrorNum()) {
            return JText::_( 'There was an error editing the site' ) . ': ' . $this->_db->stderr();
        } else {
            return JText::_( 'The site was updated successfully' );
        }
    }
    
    /**
     * Deletes the site
     */
    function deleteSite()
    {
        // generate query
        $query = 'DELETE FROM #__jpackage_sites WHERE site_id = ' . $this->_site . ';';
        $this->_db->setQuery( $query );
        $this->_db->query();
        if ($this->_db->getErrorNum()) {
            return JText::_( 'There was an error deleting the site' ) . ': ' . $this->_db->stderr();
        } else {
            return JText::_( 'The site was deleted successfully' );
        }
    }
}
