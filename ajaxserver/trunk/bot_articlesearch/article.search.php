<?php
/**
 * AJAX Article Search
 * @package AJAX-Server
 * @author Samuel Moffatt <pasamio@pasamio.id.au>
 * @copyright Copyright (C) 2007 Samuel Moffatt. All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/jauthtools/ 
 */

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$_MAMBOTS->registerFunction( 'onGetAJAXServices', 'ajaxGetArticleSearch' );

function ajaxGetArticleSearch() {
	return array(
	array(
	'name' => 'search.articles',
	'method' => 'ajaxDoArticleSearch',
	'help' => 'Searches articles on the site',
	),
	);
}


// Search through articles (content)
// Each AJAX mambot determines what information it needs to take from the query string
function ajaxDoArticleSearch($query,$field='contentid') {
	global $database, $mosConfig_offset, $my, $mainframe;
	$retval = '';
	//If no search string is passed, then we can't search
	//Remove whitespace from beginning & end of passed search.
	$search = trim($query);
	//Query the DB and store the result in a variable
	$now = date( 'Y-m-d H:i:s', time()+$mosConfig_offset*60*60 );
	$noauth = !$mainframe->getCfg( 'shownoauth' );
	$nullDate = $database->getNullDate();

	$query = "SELECT id, title, introtext"
		."\n FROM #__content"
		."\n WHERE state != 0"
		."\n AND title LIKE '%".strtolower($search)."%'"
		."\n AND (publish_up = '$nullDate' OR publish_up <= '$now' ) "
	."\n AND (publish_down = '$nullDate' OR publish_down >= '$now' )"
	."\n ORDER BY title ASC";
	$database->setQuery($query);
	$results = $database->loadAssocList();
	if(count($results)) {
		//Bust the returned rows into an array for easy usage
		$retval .= '<select name="created_by" class="inputbox" onClick="if(this.options.length < 2) { eval(this.options[this.selectedIndex].getAttribute(\'action\')); }" onChange="eval(this.options[this.selectedIndex].getAttribute(\'action\'));">';
		foreach($results as $result) {
			if(trim($result['title']) != '' && in_array($result['title'],$result)) {
				$uid = $result['id'];
				$action = "document.getElementById('preview').innerHTML='".
					str_replace("\r\n",'',str_replace('"','',str_replace("'",'',$result['introtext'])))
					."'; document.getElementById('".$field."').value='".$uid."'; lastsearchstring='".$result['title']."'";
				$retval .= "<option action=\"". $action ."\" value=\"".$uid."\">".$result['title']."</option>";
			}
		}
		$retval .= '</select>';
	} else {
		$retval .= 'Not Found: '. $search;
	}
	// Output data to client
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("results","innerHTML", $retval);

	return $objResponse->getXML();
}

