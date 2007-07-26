<?php
/**
 * @version $Id: category.php 202 2006-08-20 07:05:55Z schmalls $
 * @package Joomla
 * @subpackage JPackage
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
class JPackageModelCategory extends JModel
{
    /**
     * @var     int
     * @access  protected
     */
    var $_site;
    
    /**
     * @var     int
     * @access  protected
     */
    var $_cat_id;
    
    /**
     * Default constructor
     *
     * @param   object &$dbo A JDatabase object
     * @since   1.5
     */
    function __construct( &$dbo )
    {
        $this->_site    = JRequest::getVar( 'site', 1, 'default', 'INT' );
        $this->_cat_id  = JRequest::getVar( 'cat_id', 0, 'default', 'INT' );
    }
    
    /**
     * Gets the cat id
     */
    function getCatId()
    {
        return $this->_cat_id;
    }
    
    /**
     * Gets the structure
     */
    function getStructure()
    {
        static $structure;
        
        if(!$structure) {
            $xmlrpc     = JPackageXmlRpc::getInstance( $this->_site );
            $structure  = $xmlrpc->getStructure( $this->_cat_id );
        }
        
        return $structure;
    }
    
    /**
     * Gets the category's links
     */
    function getLinks()
    {
        $xmlrpc     = JPackageXmlRpc::getInstance( $this->_site );
        $links      = $xmlrpc->getCategory( $this->_cat_id );
        
        return $links;
    }
    
    /**
     * Gets the tree
     */
    function getTree()
    {
        $cats = $this->getStructure();
        $tree = array();
        foreach ($cats as $key => $cat) {
            $tree[$cat['parent_id']][$cat['id']] = $key;
        }
        
        return $tree;
    }
    
    /**
     * Gets the reverse tree
     */
    function getRevTree()
    {
        static $rev_tree;
        
        if (!$rev_tree) {
            $cats = $this->getStructure();
            $rev_tree = array();
            foreach ($cats as $key => $cat) {
                $rev_tree[$cat['id']] = $cat['parent_id'];
            }
        }
        
        return $rev_tree;
    }
    
    /**
     * Gets the breadcrumbs
     */
    function getBreadcrumbs()
    {
        $cats = $this->getStructure();
        $rev_tree = $this->getRevTree();
        $id = $this->_cat_id;
        $breadcrumbs = array();
        while ($id != 0) {
            $id = $rev_tree[$id];
            $breadcrumbs[] = $id;
        }
        $breadcrumbs = array_reverse( $breadcrumbs );
        
        return $breadcrumbs;
    }
    
}