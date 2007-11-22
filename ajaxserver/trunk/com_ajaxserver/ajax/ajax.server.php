<?php
/**
 * Simplest possible usage of HTML_AJAX_Server
 *
 * The server responds to ajax calls and also serves the js client libraries, so they can be used directly from the PEAR data dir
 * 304 not modified headers are used when server client libraries so they will be cached on the browser reducing overhead
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTML_AJAX
 */

/**
 * Modified to run in Joomla! 1.x by Samuel Moffatt
 * @author Samuel Moffatt <pasamio@pasamio.id.au>
 */

 // Joomla Init
 /** Set flag that this is a parent file */
define( "_VALID_MOS", 1 );
                                                                                                    
include_once ('../../../../globals.php');
require_once ('../../../../configuration.php');
/*if (!isset($mosConfig_ajax_server) || !$mosConfig_ajax_server) {
        die( '<p>AJAX Not Enabled</p>' );
}*/

$ajax_includes = '/administrator/components/com_ajaxserver/ajax';

require_once ($mosConfig_absolute_path . $ajax_includes . '/includes/xajax.common.php');
require_once( $mosConfig_absolute_path . '/includes/mambo.php' );
                                                                                                    
$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
$acl = new gacl_api();
                                                                                                    
$mainframe = new mosMainFrame( $database, '','.' );
$mainframe->initSession();
                                                                                                    
/** get the information about the current user from the sessions table */
$GLOBALS['my'] = $mainframe->getUser();
                                                                                                    
// load all available remote calls
ajax_generateServer($server);

// Process Request
$server->processRequests();
?>
