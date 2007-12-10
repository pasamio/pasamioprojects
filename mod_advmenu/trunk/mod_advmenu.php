<?php
/**
* @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
* @package		Advanced-Menu
* @copyright		Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( dirname(__FILE__).DS.'helper.php' );

JHTML::stylesheet('menu.css','administrator/modules/mod_advmenu/');
JHTML::script('init.js', 'administrator/modules/mod_advmenu/');
$hide	= JRequest::getInt('hidemainmenu');

if ( $hide ) {
	modMenuXHelper::buildDisabledMenu();
} else {
	modMenuXHelper::buildMenu();
}

?>
