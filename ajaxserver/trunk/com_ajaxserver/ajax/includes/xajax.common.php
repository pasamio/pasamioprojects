<?php
/**
 * XAJAX Common Initialization Code for Joomla
 * @author Samuel Moffatt <pasamio@pasamio.id.au>
 * @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
require_once $mosConfig_absolute_path . '/administrator/components/com_ajaxserver/ajax/includes/xajax.inc.php';


function ajax_generateServer(&$server) {
	global $_MAMBOTS, $mosConfig_live_site;

	// load all available remote calls
	$_MAMBOTS->loadBotGroup( 'ajax' );
	$classes = $_MAMBOTS->trigger( 'onGetAJAXServices', null );
                                                                                                    
	$server = new xajax($mosConfig_live_site . '/administrator/components/com_ajaxserver/ajax/ajax.server.php');
                                                                                                    
	foreach($classes as $class) {
		foreach($class as $func) {
		        $server->registerFunction($func['method']);
		}
	}
                                                                                                    
	return $server;
}


?>
