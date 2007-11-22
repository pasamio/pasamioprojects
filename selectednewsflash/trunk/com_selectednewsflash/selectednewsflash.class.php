<?php
/**
* Selected Newsflash Class
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class pasSelectedNewsflash extends mosDBTable {
/** @var int Primary key */
	var $id					= null;
/** @var int */
	var $contentid				= null;
/** @var int */
	var $published				= null;

/**
* @param database A database connector object
*/
	function pasSelectedNewsflash( &$db ) {
		$this->mosDBTable( '#__selectednewsflash', 'id', $db );
	}
}
?>