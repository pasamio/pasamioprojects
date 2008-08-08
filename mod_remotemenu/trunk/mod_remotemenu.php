<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Aug 8, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Developer Name 
 * @version SVN: $Id:$
 * @see http://joomlacode.org/gf/project/   JoomlaCode Project:    
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

// step 1: ensure we have the language 
$lang =& JFactory::getLanguage();
$lang->load('mod_mainmenu'); 

// step 2: 'replace' the dbo >:D
$dbo =& JFactory::getDBO();
$original_dbo = $dbo;
$options	= array ( 	'driver' => $params->get('driver','mysql'), 
					'host' => $params->get('host','localhost'), 
					'user' => $params->get('user',''), 
					'password' => $params->get('password',''), 
					'database' => $params->get('database',''), 
					'prefix' => $params->get('prefix',''),
				);
$remote_dbo =& JDatabase::getInstance($options);
$dbo = $remote_dbo;

// step 3: hijack the rest of the system :D
// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
$params->def('class_sfx', 			'');
$params->def('menu_images', 		0);
$params->def('menu_images_align', 	0);
$params->def('expand_menu', 		0);
$params->def('activate_parent', 	0);
$params->def('indent_image', 		0);
$params->def('indent_image1', 		'indent1.png');
$params->def('indent_image2', 		'indent2.png');
$params->def('indent_image3', 		'indent3.png');
$params->def('indent_image4', 		'indent4.png');
$params->def('indent_image5', 		'indent5.png');
$params->def('indent_image6', 		'indent.png');
$params->def('spacer', 				'');
$params->def('end_spacer', 			'');
$params->def('full_active_id', 		0);

// Added in 1.5
$params->def('startLevel', 			0);
$params->def('endLevel', 			0);
$params->def('showAllChildren', 	0);

require(JModuleHelper::getLayoutPath('mod_remotemenu'));

// step 4: return the old values
$dbo = $original_dbo;
unset($remote_dbo);