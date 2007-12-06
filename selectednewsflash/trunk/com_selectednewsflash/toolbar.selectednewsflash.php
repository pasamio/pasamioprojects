<?php
/**
* Selected Newsflash Toolbar -  Logic
* @package Selected-Newsflash
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ($task) {
	case 'new':
		TOOLBAR_selectednewsflash::_NEW();
		break;

	default:
		TOOLBAR_selectednewsflash::_DEFAULT();
		break;
}
?>