<?php
/**
 * JLibMan Customer Installer
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package JLibMan
 * @subpackage Install
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_JEXEC') or die('Saya tidak suka kamu ;)');

 	$url = "http://sammoffatt.com.au/os/index.php/joomla-15-products/1-JLibMan/6-installation_jlibman"; 
//function com_install() {
	$src = JPATH_SITE . DS . 'administrator'. DS . 'components'. DS .'com_jlibman' . DS . 'include' . DS . 'library.php';
	$dest = JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'installer' . DS . 'adapters' . DS .'library.php';
	if(JFile::copy($src, $dest)) echo "Congratulations you successfully installed the library manager!";
	else echo "Failed to install Library adapter, please try copying manually! Please see the <a href='$url'>installation notes</a> for more information.";
//}
?>