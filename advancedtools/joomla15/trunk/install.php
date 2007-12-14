<?php
/**
 * Advanced Tools Installer
 * 
 * This file handles installing various components and packages 
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


// Install Package Manager
jimport('joomla.installer.helper');
$basedir = dirname(__FILE__);
$file = $basedir . DS . 'packages' . DS . 'com_jpackageman.zip';
echo "Installing " . $file . '<br />';
$package = JInstallerHelper::unpack($file);
$tmpInstaller = new JInstaller();
if(!$tmpInstaller->install($package['dir'])) {
	JError::raiseWarning(100,JText::_('Automated').' '.JText::_('Install').': '.JText::_('There was an error installing an extension:') . basename($file));
}
//JFolder::delete($package['dir']);

// Install Tools package
$file = $basedir . DS . 'packages' . DS . 'pkg_advtools.zip';
echo "Installing " . $file . '<br />';
$package = JInstallerHelper::unpack($file);
$tmpInstaller = new JInstaller();
if(!$tmpInstaller->install($package['dir'])) {
	JError::raiseWarning(100,JText::_('Automated').' '.JText::_('Install').': '.JText::_('There was an error installing an extension:') . basename($file));
}
//JFolder::delete($package['dir']);

echo "Updating modules table...<br />";
$dbo =& JFactory::getDBO();
$dbo->setQuery('INSERT INTO jos_modules VALUES(0, "Advanced Admin Menu", "", 5, "menu", 0, "0000-00-00 00:00:00", 1, "mod_advmenu", 0, 2, 1, "", 0, 1, "")');
$dbo->Query();

?>