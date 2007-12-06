<?php
/**
* Selected Newsflash Module aka Chaser Gold
* @package Selected-Newsflash
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'front_html', 'com_content') );

global $my, $mosConfig_shownoauth, $mosConfig_offset, $acl;

// Disable edit ability icon
$access = new stdClass();
$access->canEdit 	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;

$now = date( 'Y-m-d H:i:s', time()+$mosConfig_offset*60*60 );

$params->set( 'intro_only', 1 );
$params->set( 'hide_author', 1 );
$params->set( 'hide_createdate', 1 );
$params->set( 'hide_modifydate', 1 );
$params->set( 'readmore', 1 );
$params->set( 'item_title', 1 );
$params->set( 'pageclass_sfx', $params->get( 'moduleclass_sfx' ) );

$noauth = !$mainframe->getCfg( 'shownoauth' );
$nullDate = $database->getNullDate();

// query to determine article count
$query = "SELECT a.id"
."\n FROM #__content AS a"
."\n INNER JOIN #__selectednewsflash AS b ON b.contentid = a.id"
."\n WHERE state != 0"
. ( $noauth ? "\n AND a.access <= $my->gid" : '' )
."\n AND (b.published = 1)"
."\n AND (a.publish_up = '$nullDate' OR a.publish_up <= '$now' ) "
."\n AND (a.publish_down = '$nullDate' OR a.publish_down >= '$now' )"
."\n ORDER BY a.ordering";

$database->setQuery( $query );
$rows = $database->loadResultArray();
$numrows = count( $rows );
$row = new mosContent( $database );
$readmore = 1;
echo '<table width="100%">';
$existing = Array();
$flashnum = 0;
for($i = 0; $i < $params->get( 'items', 1 ); $i++) {
	echo '<tr><td width="100%">';
	do {
		$existing[] = $flashnum;
		if ($numrows > 0) {
			srand ((double) microtime() * 1000000);
			$flashnum = $rows[rand( 0, $numrows-1 )];
		} else {
			$flashnum = 0;
		}
		
	} while( in_array( $flashnum, $existing ) ); // I kid you not, this should be done better...programmers block
	$row->load( $flashnum );
	$row->text = $row->introtext;
	$row->groups = '';
	$row->readmore = 1;
	HTML_content::show( $row, $params, $access, 0, 'com_content' );
	echo '</td></tr>';
}
echo '</table>';
?>
