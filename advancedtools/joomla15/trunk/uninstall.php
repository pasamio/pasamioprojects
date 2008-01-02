<?php
/**
 * Advanced Tools Uninstaller
 * 
 * Removes module as well
 * 
 * PHP4/5
 *  
 * Created on Dec 11, 2007
 * 
 * @package Advanced-Tools
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

/**
 * Get the extension id
 * Grabbed this from the JPackageMan installer class with modification
 */
function _getExtensionID($type, $id, $client, $group) {
	$db		=& JFactory::getDBO();
	$result = $id;
	switch($type) {
		case 'plugin':
			$db->setQuery("SELECT id FROM #__plugins WHERE folder = '$group' AND element = '$id'");
			$result = $db->loadResult();
			break;
		case 'component':
			$db->setQuery("SELECT id FROM #__components WHERE parent = 0 AND `option` = '$id'");
			$result = $db->loadResult();
			break;
		case 'module':
			$db->setQuery("SELECT id FROM #__modules WHERE module = '$id' and client_id = '$client'");
			$result = $db->loadResult();
			break;
		case 'language':
			// A language is a complex beast
			// its actually a path!
			$clientInfo =& JApplicationHelper::getClientInfo($this->_state->get('filter.client'));
			$client = $clientInfo->name;
			$langBDir = JLanguage::getLanguagePath($clientInfo->path);
			$result = $langBDir . DS . $id;
			break;
	}
	// note: for templates, libraries and packages their unique name is their key
	// this means they come out the same way they came in
	return $result;
}

// We don't do much here, the user has to uninstall the package manager and package themselves.
// However we do uninstall the module manager
$tmpinstaller = new JInstaller();
$result = $tmpinstaller->uninstall('module', _getExtensionID('module','mod_advmenu', 'administrator','menu'), 1 );
echo JText::_("Please note that this component will attempt to uninstall the Advanced Menu module as well.");
if(!$result) echo JText::_("Automated uninstallation of the module failed."._getExtensionID('module','mod_advmenu', 'administrator','menu')); 
