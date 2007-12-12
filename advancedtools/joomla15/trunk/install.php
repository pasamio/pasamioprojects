<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Dec 11, 2007
 * 
 * @package package_name
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

// Install Package Manager
jimport('joomla.installer.helper');
$basedir = dirname(__FILE__);
$file = $basedir . DS . 'packages' . DS . 'com_jpackageman.zip';
$package = JInstallerHelper::unpack();
$tmpInstaller = new JInstaller();
if(!$tmpInstaller->install($package['dir'])) {
	$this->parent->abort(JText::_('Package').' '.JText::_('Install').': '.JText::_('There was an error installing an extension:') . basename($file));
}
JFolder::detete($package['dir']);

// Install Tools package
$file = $basedir . DS . 'packages' . DS . 'pkg_advtools.zip';
$package = JInstallerHelper::unpack($file);
$tmpInstaller = new JInstaller();
if(!$tmpInstaller->install($package['dir'])) {
	$this->parent->abort(JText::_('Package').' '.JText::_('Install').': '.JText::_('There was an error installing an extension:') . basename($file));
}
JFolder::delete($package['dir']);

?>