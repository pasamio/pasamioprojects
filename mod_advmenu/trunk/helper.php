<?php
/**
* @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
* @package		Joomla
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
JHTML::script('init.js', 'administrator/modules/mod_xmenu/');
		$menu->addChild(new JMenuXNode(JText::_('Advanced')), true);
		$menu->addChild(new JMenuXNode(JText::_('Updated Manager'), 'index.php', 'class:cpanel'));
		$menu->addChild(new JMenuXNode(JText::_('Library Management'), 'index.php?option=com_media', 'class:media'));
		$menu->addSeparator();
		if ($canConfig) {
			$menu->addChild(new JMenuXNode(JText::_('Configure Advanced Settings'), 'index.php?option=com_config', 'class:config'));
			$menu->addSeparator();
		}

		$menu->getParent();
		}


		/*
		 * Components SubMenu
		 */
/*
		if ($editAllComponents) 
		{
			$menu->addChild(new JMenuXNode(JText::_('Advanced Tools')), true);

			$query = 'SELECT *' .
				' FROM #__components' .
				' WHERE '.$db->NameQuote( 'option' ).' <> "com_frontpage"' .
				' AND '.$db->NameQuote( 'option' ).' <> "com_media"' .
				' AND enabled = 1' .
				' ORDER BY ordering, name';
			$db->setQuery($query);
			$comps = $db->loadObjectList(); // component list
			$subs = array(); // sub menus
			$langs = array(); // additional language files to load

			// first pass to collect sub-menu items
			foreach ($comps as $row)
			{
				if ($row->parent)
				{
					if (!array_key_exists($row->parent, $subs)) {
						$subs[$row->parent] = array ();
					}
					$subs[$row->parent][] = $row;
					$langs[$row->option.'.menu'] = true;
				} elseif (trim($row->admin_menu_link)) {
					$langs[$row->option.'.menu'] = true;
				}
			}

			// Load additional language files
			if (array_key_exists('.menu', $langs)) {
				unset($langs['.menu']);
			}
			foreach ($langs as $lang_name => $nothing) {
				$lang->load($lang_name);
			}

			foreach ($comps as $row)
			{
				if ($editAllComponents | $user->authorize('administration', 'edit', 'components', $row->option))
				{
					if ($row->parent == 0 && (trim($row->admin_menu_link) || array_key_exists($row->id, $subs)))
					{
						$text = $lang->hasKey($row->option) ? JText::_($row->option) : $row->name;
						$link = $row->admin_menu_link ? "index.php?$row->admin_menu_link" : "index.php?option=$row->option";
						if (array_key_exists($row->id, $subs)) {
							$menu->addChild(new JMenuXNode($text, $link, $row->admin_menu_img), true);
							foreach ($subs[$row->id] as $sub) {
								$key  = $row->option.'.'.$sub->name;
								$text = $lang->hasKey($key) ? JText::_($key) : $sub->name;
								$link = $sub->admin_menu_link ? "index.php?$sub->admin_menu_link" : null;
								$menu->addChild(new JMenuXNode($text, $link, $sub->admin_menu_img));
							}
							$menu->getParent();
						} else {
							$menu->addChild(new JMenuXNode($text, $link, $row->admin_menu_img));
						}
					}
				}
			}
			$menu->getParent();
		}
*/

		$menu->renderMenu('advmenu', '');
	}

	/**
	 * Show an disbaled version of the menu, used in edit pages
	 *
	 * @param string The current user type
	 */
	function buildDisabledMenu()
	{
		$lang	 =& JFactory::getLanguage();
		$user	 =& JFactory::getUser();
		$usertype = $user->get('usertype');

		$canConfig			= $user->authorize('com_config', 'manage');
		$installModules		= $user->authorize('com_installer', 'module');
		$editAllModules		= $user->authorize('com_modules', 'manage');
		$installPlugins		= $user->authorize('com_installer', 'plugin');
		$editAllPlugins		= $user->authorize('com_plugins', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');
		$canMassMail			= $user->authorize('com_massmail', 'manage');
		$canManageUsers		= $user->authorize('com_users', 'manage');
		JHTML::script('init.js', 'administrator/modules/mod_xmenu/');
		$text = JText::_('Menu inactive for this Page', true);

		// Get the menu object
		$menu = new JAdminCSSMenuX();

		// Site SubMenu
		$menu->addChild(new JMenuXNode(JText::_('Site'), null, 'disabled'));

		// Menus SubMenu
		$menu->addChild(new JMenuXNode(JText::_('Menus'), null, 'disabled'));

		// Content SubMenu
		$menu->addChild(new JMenuXNode(JText::_('Content'), null, 'disabled'));

		// Components SubMenu
		if ($installComponents) {
			$menu->addChild(new JMenuXNode(JText::_('Components'), null, 'disabled'));
		}

		// Extensions SubMenu
		if ($installModules) {
			$menu->addChild(new JMenuXNode(JText::_('Extensions'), null, 'disabled'));
		}

		// System SubMenu
		if ($canConfig) {
			$menu->addChild(new JMenuXNode(JText::_('Tools'),  null, 'disabled'));
		}

		// Help SubMenu
		$menu->addChild(new JMenuXNode(JText::_('Help'),  null, 'disabled'));

		$menu->renderMenu('advmenu', 'disabled');
	}
}

?>
