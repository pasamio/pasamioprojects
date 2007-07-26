<?php
/**
* @version $Id: xmlrpc.php 202 2006-08-20 07:05:55Z schmalls $
* @package Joomla
* @subpackage JPackage
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

define('JPACKAGE_CACHE_PATH', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jpackage' . DS . 'cache');

jimport( 'joomla.common.base.object' );
jimport( 'phpxmlrpc.xmlrpc' );

/**
 * @package Joomla
 * @subpackage JPackage
 */
class JPackageXmlRpc extends JObject
{
    /**
     * @var     object XML-RPC Client
     */
    var $_client;
    
    /**
     * @var     array cached values
     */
    var $_cached;
    
    /**
     * @param   array $site
     */
    function __construct( $site )
    {
        $siteSplit = explode( '/', $site['url'], 2 );
        $this->_client = new xmlrpc_client( $siteSplit[1], $siteSplit[0], 80, 'http' );
        $this->_client->setCredentials( $site['username'], $site['password'] );
        $this->_cached = array();
        $this->_site = md5( $site['url'] );
        $cached_files = glob( JPACKAGE_CACHE_PATH . DS . '*.' . $this->_site . '.xmlrpc.cache' );
        $cached_files = ($cached_files === false) ? array() : $cached_files;
        foreach ($cached_files as $file) {
            // check if cached version is recent enough
            /* @todo fix to work with variable cache time */
            if ( filemtime($file) > ( time() - (60 * 5) )) {
                $base = basename( $file );
                $method = substr( $base, 0, strlen( $base ) - strlen( $this->_site . '.xmlrpc.cache' ) - 1 );
                $this->_cached[$method] = $file;
            }
        }
        //$this->setDebug( true );
    }
    
    /**
     * @return  object instance of JPackageXmlRpc
     */
    function &getInstance( $site = 1 )
    {
        static $instances = array();
        
        if (!isset($instances[$site])) {
            $sites = JPackageXmlRpc::getSites();
            $instances[$site] = new JPackageXmlRpc( $sites[$site] );
        }
        return $instances[$site];
    }
    
    /**
     * @return  array site urls
     */
    function getSites()
    {
        global $mainframe;
        static $sites = array();
        
        if (!$sites) {       
            $database =& $mainframe->getDBO();
            $query = 'SELECT site_id, name, url, username, password, method FROM #__jpackage_sites';
            $database->setQuery( $query );
            $sites = $database->loadAssocList( 'site_id' );
            if ($database->getErrorNum()) {
                echo $database->stderr();
                return false;
            }
        }
        return $sites;
    }
    
    /**
     * @param   boolean $debug
     */
    function setDebug( $debug )
    {
        $this->_client->setDebug( (int) $debug );
    }
    
    /**
     * @return  array method information
     */
    function listMethods()
    {
        // check if message response is cached
        //$this->setDebug( true );
        if (empty( $this->_cached['system.listMethods'] )) {
            $msg = new xmlrpcmsg( 'system.listMethods', array() );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $methods = php_xmlrpc_decode( $response->value() );
            }
            if ($methods !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'system.listMethods.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $methods = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['system.listMethods'] ) ) );
        }
        
        return $methods;
    }
    
    /**
     * @param   int $cur_version
     * @return  array core update information
     */
    function getCore( $cur_version )
    {
        // check if message response is cached
        //$this->setDebug( true );
        if (empty( $this->_cached['jpackage.getCore.' . $cur_version] )) {
            $msg = new xmlrpcmsg( 'jpackage.getCore', array( new xmlrpcval( $cur_version, 'int' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $core = php_xmlrpc_decode( $response->value() );
            }
            if ($core !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getCore.' . $cur_version . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $core = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getCore.' . $cur_version] ) ) );
        }
        
        return $core;
    }
    
    /**
     * @return  array category information
     */
    function getStructure()
    {
        // check if message response is cached
        //$this->setDebug( true );
        if (empty( $this->_cached['jpackage.getStructure'] )) {
            $msg = new xmlrpcmsg( 'jpackage.getStructure', array() );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $cats = php_xmlrpc_decode( $response->value() );
            }
            if ($cats !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getStructure.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $cats = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getStructure'] ) ) );
        }
        
        return $cats;
    }
    
    /**
     * @param   int $cat_id
     * @return  array link information
     */
    function getCategory( $cat_id )
    {
        // check if message response is cached
        //$this->setDebug( true );
        $links = array();
        if (empty( $this->_cached['jpackage.getCategory.' . $cat_id] )) {
            $msg = new xmlrpcmsg( 'jpackage.getCategory', array( new xmlrpcval( $cat_id, 'int' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $links = php_xmlrpc_decode( $response->value() );
            }
            if ($links !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getCategory.' . $cat_id . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $links = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getCategory.' . $cat_id] ) ) );
        }
        return $links;
    }
    
    /**
     * @param   int $link_id
     * @return  array package information
     */
    function getPackage( $link_id, $package_id = null )
    {
        // check if message response is cached
        //$this->setDebug( true );
        $package = array();
        if (empty( $this->_cached['jpackage.getPackage.' . $link_id] )) {
            $msg = new xmlrpcmsg( 'jpackage.getPackage', array( new xmlrpcval( $link_id, 'int' ), new xmlrpcval( $package_id, 'int' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $package = php_xmlrpc_decode( $response->value() );
            }
            if ($package !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getPackage.' . $link_id . '.' . $package_id . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $package = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getPackage.' . $link_id . '.' . $package_id] ) ) );
        }
        // check if package id was provided
        if ($package_id !== null) {
            return $package[$package_id];
        }
        return $package;
    }
    
    /**
     * @param   string $type
     * @param   string $name
     * @param   int $version
     * @return  array latest package
     */
    function getLatest( $type, $name, $version )
    {
        // check if message response is cached
        //$this->setDebug( true );
        $latest = array();
        if (empty( $this->_cached['jpackage.getLatest.' . md5( $type . $name . $version )] )) {
            $msg = new xmlrpcmsg( 'jpackage.getLatest', array( new xmlrpcval( $type, 'string' ),
                new xmlrpcval( $name, 'string' ), new xmlrpcval( $version, 'string' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $latest = php_xmlrpc_decode( $response->value() );
            }
            if ($latest !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getLatest.' . md5( $type . $name . $version ) . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $latest = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getLatest.' . md5( $type . $name . $version )] ) ) );
        }
        return $latest;
    }
    
    /**
     * @param   int $package_id
     * @return  array dependency information
     */
    function getDependencies( $package_id )
    {
        // check if message response is cached
        //$this->setDebug( true );
        $dependencies = array();
        if (empty( $this->_cached['jpackage.getDependencies.' . $package_id] )) {
            $msg = new xmlrpcmsg( 'jpackage.getDependencies', array( new xmlrpcval( $package_id, 'int' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $dependencies = php_xmlrpc_decode( $response->value() );
            }
            if ($dependencies !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.getDependencies.' . $package_id . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $dependencies = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.getDependencies.' . $package_id] ) ) );
        }
        return $dependencies;
    }
    
    /**
     * @param   string $searchString
     * @return  array package results
     */
    function searchPackages( $searchString )
    {
        // check if message response is cached
        //$this->setDebug( true );
        $results = array();
        if (empty( $this->_cached['jpackage.searchPackages.' . md5( $searchString )] )) {
            $msg = new xmlrpcmsg( 'jpackage.searchPackages', array( new xmlrpcval( $searchString, 'string' ) ) );
            $response = $this->_client->send( $msg );
            // get result
            if ($response->faultCode()) {
                echo $response->faultString();
            } else {
                $results = php_xmlrpc_decode( $response->value() );
            }
            if ($results !== false) {
                file_put_contents( JPACKAGE_CACHE_PATH . DS . 'jpackage.searchPackages.' . md5( $searchString ) . '.' . $this->_site . '.xmlrpc.cache', serialize( $response->value() ) );
            }
        } else {
            $results = php_xmlrpc_decode( unserialize( file_get_contents( $this->_cached['jpackage.searchPackages.' . md5( $searchString )] ) ) );
        }
        return $results;
    }
    
}