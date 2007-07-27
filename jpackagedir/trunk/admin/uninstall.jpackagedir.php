<?
/**
* @version $Id: uninstall.jpackagedir.php 52 2006-07-01 22:17:58Z schmalls $
* @package Joomla
* @subpackage extensionsdir
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
* The uninstallation option...does nothing actually :-)
*/
function com_uninstall() {
	echo "Component successfully uninstalled.";
} # End function com_uninstall
?>