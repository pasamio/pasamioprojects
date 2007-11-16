<?php
/**
* @version $Id: jpackage.php 202 2006-08-20 07:05:55Z schmalls $
* @package JPackageDir
* @subpackage JPackage
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onGetWebServices', 'wsGetJPackageWebServices' );

/**
 * @return array An array of associative arrays defining the available methods
 */
function wsGetJPackageWebServices() {
    return array(
        'jpackage.getCore' => array( 'function' => 'jPackageGetCore',
            'docstring' => 'This will send all core updates available for the specified version.'
        ),
        'jpackage.getStructure' => array( 'function' => 'jPackageGetStructure',
            'docstring' => 'This will send all categories that currently resides in the extension directory together with any parent-child relationship.'
        ),
        'jpackage.getCategory' => array( 'function' => 'jPackageGetCategory',
            'docstring' => 'Sends all available extensions that resides in a specific category.'/*,
            /*'signature' => array( 'int' ) // cat_id*/
        ),
        'jpackage.getPackage' => array( 'function' => 'jPackageGetPackage',
            'docstring' => 'Sends all package\'s information with data on dependencies, versions, ...',
            /*'signature' => array( 'int' ) // link_id*/
        ),
        'jpackage.getLatest' => array( 'function' => 'jPackageGetLatest',
            'docstring' => 'Sends latest version of specified package.'/*,
            /*'signature' => array( array( 'string', 'string', 'string' ) ) // type, name, version*/
        ),
        'jpackage.getDependencies' => array( 'function' => 'jPackageGetDependencies',
            'docstring' => 'Sends dependencies of specified package.'/*,
            /*'signature' => array( array( 'int' ) ) // package_id*/
        ),
        'jpackage.searchPackages' => array( 'function' => 'jPackageSearchPackages',
            'docstring' => 'Adds ability to search packages.'/*,
            /*'signature' => array( 'string' ) // search_string*/
        ),
    );
}

/**
 * @return      mixed XML-RPC response
 */
function jPackageGetCore( $cur_version ) 
{
    global $mainframe;
    
    $database =& $mainframe->getDBO();  
    // get the core update information  
    $query = 'SELECT name, version, update_url, update_checksum, update_filesize, xml_url, xml_checksum, xml_filesize FROM #__jpackagedir_core WHERE depends = '
        . $cur_version . ' ORDER BY version DESC';
    $database->setQuery( $query );
    $core = $database->loadAssocList( 'id' );
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $core ) );
}

/**
 * @return      mixed XML-RPC response
 */
function jPackageGetStructure() 
{
    global $mainframe;
    
    $database =& $mainframe->getDBO();  
    // get the cat information  
    $query = 'SELECT C.id AS id, C.name AS name, C.description AS description, C.parent_id AS parent_id, ' .
        '(SELECT COUNT(*) FROM #__jpackagedir_categories AS CC WHERE CC.parent_id = C.id) AS cats, ' .
        '(SELECT COUNT(*) FROM #__jpackagedir_relations AS R WHERE R.category_id = C.id) AS projects ' .
        'FROM #__jpackagedir_categories AS C WHERE published = 1 AND id > -1';
    $database->setQuery( $query );
    $cats = $database->loadAssocList( 'id' );
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $cats ) );
}

/**
 * @param       int $cat_id
 * @return      mixed XML-RPC response
 */
function jPackageGetCategory( $cat_id )
{
    global $mainframe;
    
    $database =& $mainframe->getDBO();
    // get the extensions
    $query = 'SELECT P.id AS id, P.name AS name, P.description AS description FROM #__jpackagedir_projects AS P, #__jpackagedir_relations AS R WHERE R.category_id = ' .
        $cat_id . ' AND R.project_id = P.id';
    $database->setQuery($query);
    $links = $database->loadAssocList( 'id' );
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $links ) );
}

/**
 * @param       int $link_id
 * @return      mixed XML-RPC response
 */
function jPackageGetPackage( $link_id )
{
    global $mainframe;
    
    $database   =& $mainframe->getDBO();
    // get the package information
    $query = 'SELECT id, link_id, type, name, description, filesize, version, url, hashtype, checksum, directory FROM #__jpackagedir_packages WHERE link_id = ' .
        $link_id . ' ORDER BY version DESC';
    $database->setQuery( $query );
    $package = $database->loadAssocList( 'id' );
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $package ) );
}

/**
 * @param       string $type
 * @param       string $name
 * @param       string $version
 * @return      mixed XML-RPC response
 */
function jPackageGetLatest( $type, $name, $version )
{
    global $mainframe;
    
    $database   =& $mainframe->getDBO();
    // get latest package information
    $query = 'SELECT p.id, p.link_id, p.type, p.name, p.description, p.filesize, p.version, p.url, p.hashtype, p.checksum, p.directory FROM #__jpackagedir_packages as p, #__jpackagedir_dependencies as d WHERE (p.type = \'' .
        $type . '\') AND (p.name = \'' . $name .
        '\') AND (p.id = d.packageid) AND (d.dependid = (SELECT id FROM #__jpackagedir_packages WHERE type = \'' .
        $type . '\' AND name = \'' . $name . '\' AND version = \'' . $version . '\')) ORDER BY p.version DESC';
    //$query = "SELECT p.id, p.link_id, p.type, p.name, p.description, p.filesize, p.version, p.url, p.hashtype, p.checksum, p.directory FROM jos_jpackagedir_packages as p, jos_jpackagedir_dependencies as d WHERE (p.type = 'component') AND (p.name = 'com_test') AND (p.id = d.packageid) AND (d.dependid = (SELECT id FROM jos_jpackagedir_packages WHERE type = 'component' AND name = 'com_test' AND version = '1.0')) ORDER BY p.version DESC";
    $database->setQuery( $query );
    $latest = $database->loadAssocList();
    if (($database->getErrorNum()) || (empty( $latest ))) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $latest[0] /*$query*/ ) );
}

/**
 * @param       int $package_id
 * @return      mixed XML-RPC response
 */
function jPackageGetDependencies( $package_id )
{
    global $mainframe;
    
    $database =& $mainframe->getDBO();
    // get dependency information
    $query = 'SELECT P.id, P.link_id, P.type, P.name, P.description, P.filesize, P.version, P.url, P.hashtype, P.checksum, P.directory FROM #__jpackagedir_packages AS P, #__jpackagedir_dependencies AS D WHERE (P.id = D.dependid) AND (D.packageid = ' .
        $package_id . ')';
    $database->setQuery( $query );
    $dependencies = $database->loadAssocList('name');
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    // get package information
    $query = 'SELECT id, link_id, type, name, description, filesize, version, url, hashtype, checksum, directory FROM #__jpackagedir_packages WHERE (id = ' .
        $package_id . ')';
    $database->setQuery( $query );
    $packages = $database->loadAssocList();
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    $package = $packages[0];
    if (((isset( $dependencies[$package['name']] )) && ($dependencies[$package['name']]['version'] < $package['version'])) || (!isset( $dependencies[$package['name']] ))) {
        $dependencies[$package['name']] = $package;
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $dependencies ) );
}

/**
 * @param       string $search the extension for which to search
 * @return      mixed XML-RPC response
 */
function jPackageSearchPackages( $search )
{
    global $mainframe;
    
    $database   = & $mainframe->getDBO();
    $tokens     = _jPackageParseSearchString( $search );
    $query      = 'SELECT id, name, description FROM #__jpackagedir_projects';
    $query     .= empty( $tokens['OR'] ) ? '' : (" WHERE name LIKE '%" . implode( "%' OR name LIKE '%", $tokens['OR'] ) . "%'");
    $query     .= empty( $tokens['AND'] ) ? '' : (empty( $tokens['OR'] ) ? ' WHERE name LIKE ' : ' AND name LIKE ') . ("'%" . implode( "%' AND name LIKE '%", $tokens['AND'] ) . "%'");
    $query     .= empty( $tokens['NOT'] ) ? '' : ((empty( $tokens['OR'] ) && empty( $tokens['AND'] )) ? ' WHERE name NOT LIKE ' : ' AND name NOT LIKE ') . ("'%" . implode( "%' AND name NOT LIKE '%", $tokens['NOT'] ) . "%'");
    $database->setQuery( $query );
    $results = $database->loadAssocList( 'id' );
    if ($database->getErrorNum()) {
        //echo $database->stderr();
        return new xmlrpcresp( php_xmlrpc_encode( false ) );
    }
    
    return new xmlrpcresp( php_xmlrpc_encode( $results ) );
}

/**
 * Parse search string
 * 
 * @param       string $search_string
 * @return      array
 */
function _jPackageParseSearchString( $search_string )
{
    // pre-parse
    $search_string = str_replace( '- ', '-', $search_string );
    $search_string = str_replace( '+ ', '+', $search_string );
    // get tokens
    $tokens = array( 'OR' => array(), 'AND' => array(), 'NOT' => array() );
    $current = '';
    $quotes = false;
    $type = 'OR';
    $search_array = explode( "\n", chunk_split( $search_string, 1, "\n" ) );
    foreach ($search_array as $char) {
        // check if character is alpha numeric
        if (ctype_alnum( $char )) {
            $current .= $char;
        } else {
            // check what type it is
            switch ($char) {
                
            case ' ' :
                if ($quotes) {
                    $current .= $char;
                } else {
                    $tokens[$type][] = $current;
                    $current = '';
                }
                break;
            
            case '+' :
                if (empty( $current )) {
                    $type = 'AND';
                } else {
                    $current .= $char;
                }
                break;
            
            case '-' :
                if (empty( $current )) {
                    $type = 'NOT';
                } else {
                    $current .= $char;
                }
                break;
            
            case '"' :
                // check if this is opening or closing quote
                if ($quotes) {
                    $tokens[$type][] = $current;
                    $current = '';
                } else {
                    $quotes = true;
                    $current = '';
                }
                break;
            
            case '*' :
                $current .= '%';
                break;
                
            default :
                $current .= $char;
                break;
                
            }
        }
    }
    
    if (strlen( $current ) > 0) {
        $tokens[$type][] = $current;
    }
    
    return $tokens;
}
