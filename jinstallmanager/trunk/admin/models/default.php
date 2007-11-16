<?php
/**
 * @version $Id: default.php 136 2006-07-29 22:53:53Z schmalls $
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
class JPackageModelDefault extends JModel
{
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
        $this->_site = JRequest::getVar( 'site', 1, 'default', 'INT' );
    }
    
    /**
     * Gets list of methods
     */
    function getMethods()
    {
        static $methods;
        
        if(!$methods) {
            $xmlrpc     = JPackageXmlRpc::getInstance( $this->_site );
            $methods    = $xmlrpc->listMethods();
        }
        
        return $methods;
    }
    
}
