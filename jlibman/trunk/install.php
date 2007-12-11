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
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_JEXEC') or die('Saya tidak suka kamu ;)');

//function com_install() {
	$src = JPATH_SITE . DS . 'administrator'. DS . 'components'. DS .'com_jlibman' . DS . 'include' . DS . 'library.php';
	$dest = JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'installer' . DS . 'adapters' . DS .'library.php';
	if(JFile::copy($src, $dest)) echo "Congratulations you successfully installed the library manager!";
	else echo "Failed to install Library adapter, please try copying manually! Please see the <a href='http://sammoffatt.com.au/content/view/16/7/'>installation notes</a> for more information.";
//}
?>