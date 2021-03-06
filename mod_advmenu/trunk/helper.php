<?php
/**
* @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
* @package		Advanced-Menu
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'menu.php');

class modMenuXHelper
{
	/**
	 * Show the menu
	 * @param string The current user type
	 */
	function buildMenu()
	{
		global $mainframe;

		$lang		= & JFactory::getLanguage();
		$user		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$caching	= $mainframe->getCfg('caching');
		$usertype	= $user->get('usertype');

		// cache some acl checks
		$canCheckin			= $user->authorize('com_checkin', 'manage');
		$canConfig			= $user->authorize('com_config', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');

		// Menu Types
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
		$menuTypes 	= MenusHelper::getMenuTypelist();

		/*
		 * Get the menu object
		 */
		$menu = new JAdminCSSMenuX();

		/*
		 * Site SubMenu
		 */
		if($editAllComponents) {
			$menu->addChild(new JMenuXNode(JText::_('Advanced')), true);
			$dbo =& JFactory::getDBO();
			$dbo->setQuery("SELECT * FROM #__advancedtools_menu WHERE published = 1");
			$results = $dbo->loadObjectList();
			$css = Array();
			foreach($results as $result) {
				$menu->addChild(new JMenuXNode(JText::_($result->name), $result->link, $result->class));
				if(strlen($result->csspath) && strlen($result->cssfile)) 	$css[$result->csspath] = $result->cssfile;
			}
			$menu->getParent();
		}
		
		// include other css documents
		foreach($css as $key->$value) {
			JHTML::stylesheet($key,$value);
		}

		$menu->renderMenu('advmenu', '');
	}

	/**
	 * Show an disbaled version of the menu, used in edit pages
	 *
	 * @param string The current user type
	 */
	function buildDisabledMenu()
	{
		global $mainframe;

		$lang		= & JFactory::getLanguage();
		$user		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$caching	= $mainframe->getCfg('caching');
		$usertype	= $user->get('usertype');

		// cache some acl checks
		$canCheckin			= $user->authorize('com_checkin', 'manage');
		$canConfig			= $user->authorize('com_config', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');

		$text = JText::_('Menu inactive for this Page', true);
		
		// Get the menu object
		$menu = new JAdminCSSMenuX();

		// Advanced SubMenu
		if($editAllComponents) {
			$menu->addChild(new JMenuXNode(JText::_('Advanced'),  null, 'disabled'));
		}

		$menu->renderMenu('advmenu', 'disabled');
	}
}

?>
