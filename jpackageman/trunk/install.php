<?php
/**
 * jpackageman Customer Installer
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package JPackageMan
 * @subpackage Install
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_JEXEC') or die('Saya tidak suka kamu ;)');

//function com_install() {
	$url = 'http://sammoffatt.com.au/os/index.php/joomla-15-products/2-JPackageMan/5-jpackageman-installation';
	$src = JPATH_SITE . DS . 'administrator'. DS . 'components'. DS .'com_jpackageman' . DS . 'include' . DS . 'package.php';
	$dest = JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'installer' . DS . 'adapters' . DS .'package.php';
	if(JFile::copy($src, $dest)) echo "Congratulations you successfully installed the package manager!";
	else echo "Failed to install Package adapter, please try copying manually! Please see the <a href='$url'>installation notes</a> for more information.";
//}
?>
