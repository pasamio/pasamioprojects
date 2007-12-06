<?php
/**
* Selected Newsflash Admin Component - Logic
* @package Selected-Newsflash
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$task 	= mosGetParam( $_REQUEST, 'task', '' );
$cid 	= mosGetParam( $_POST, 'cid', array(0) );
$id 	= mosGetParam( $_GET, 'id', 0 );
if (!is_array( $cid )) {
	$cid = array(0);
}

switch ($task) {

	case 'new':
		newNewsflash( $option );
		break;

	case 'save':
		saveNewsflash( $option );
		break;

	case 'publish':
		publishNewsflash( $cid, 1, $option );
		break;

	case 'unpublish':
		publishNewsflash( $cid, 0, $option );
		break;

	case 'remove':
		removeNewsflash( $cid, $option );
		break;

	default:
		showSelection( $option );
		break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showSelection( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$catid = $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );

	// get the total number of records
	
	$query = "SELECT COUNT(*) FROM #__selectednewsflash";
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// get the subset (based on limits) of required records
	$query = "SELECT b.id, b.contentid, a.title, b.published"
		."\n FROM #__content AS a"
		."\n INNER JOIN #__selectednewsflash AS b ON b.contentid = a.id"
		."\n WHERE state != 0"
		."\n ORDER BY a.ordering"
		. "\n LIMIT $pageNav->limitstart, $pageNav->limit";

	
	$database->setQuery( $query );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}


	HTML_selectednewsflash::showSelection( $rows, $lists, $pageNav, $option );
}

/**
* Creates a new record
*/
function newNewsflash( $option ) {
	global $database, $mainframe, $my, $mosConfig_absolute_path, $mosConfig_offset;
		
	// Sam Moffatt's AJAX Server Framework (based on XAJAX)
	include $mosConfig_absolute_path . '/administrator/components/com_ajaxserver/ajax/includes/xajax.common.php';
	ajax_generateServer($server);
	$server->printJavaScript();
	HTML_selectednewsflash::newNewsflash( $option );
	
}

/**
* Saves the record from an edit form submit
* @param string The current GET/POST option
*/
function saveNewsflash( $option ) {
	global $database, $my;	

	$row = new pasSelectedNewsflash( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if($row->published == 'on') {
		$row->published = 1;
	} else {
		$row->published = 0;
	}
	
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	mosRedirect( 'index2.php?option='. $option );
}

/**
* Publishes or Unpublishes one or more modules
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current GET/POST option
*/
function publishNewsflash( $cid, $publish, $option ) {
	global $database, $my;

	if (count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select a module to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__selectednewsflash"
	. "\n SET published = ". intval( $publish )
	. "\n WHERE id IN ( $cids )"	
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	mosRedirect( 'index2.php?option='. $option );
}

/**
* Removes records
* @param array An array of id keys to remove
* @param string The current GET/POST option
*/
function removeNewsflash( &$cid, $option ) {
	global $database;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__selectednewsflash"
		. "\n WHERE id IN ( $cids )"		
		;
		
		$database->setQuery( $query );		
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( 'index2.php?option='. $option );
}

?>
