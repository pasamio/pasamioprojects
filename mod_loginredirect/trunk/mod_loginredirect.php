<?php
/**
 * Redirects a user on a page if criteria are met
 * 
 * 
 * PHP4/5
 *  
 * Created on Sep 24, 2008
 * 
 * @package mod_loginredirect
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Sam Moffatt
 * @version SVN: $Id:$    
 */
 

$user =& JFactory::getUser();
if($user->id) {
	if($itemid = $params->get('login',0)) {
		$menu =& JSite::getMenu();
		$tmp = $menu->getItem($itemid);
		switch ($tmp->type)
		{
			case 'url' :
				if ((strpos($tmp->link, 'index.php?') === 0) && (strpos($tmp->link, 'Itemid=') === false)) {
					$url = $tmp->link.'&amp;Itemid='.$tmp->id;
				} else {
					$url = $tmp->link;
				}
				break;

			default :
				$router = JSite::getRouter();
				$url = $router->getMode() == JROUTER_MODE_SEF ? 'index.php?Itemid='.$tmp->id : $tmp->link.'&Itemid='.$tmp->id;
				break;
		}
	} else {
		$url = 'index.php';
	}
	$url = str_replace('&amp;','&', $url); // get rid of any ampersands
	$url = JRoute::_($url);
	$url = str_replace('&amp;','&', $url); // get rid of any ampersands again
	$app =& JFactory::getApplication();
	//echo $url;
	$app->redirect($url);
}