<?php
/**
 * Uninstall Procedure
 * 
 * PHP4/5
 *  
 * Created on Oct 11, 2007
 * 
 * @package JPackageMan
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

defined('_JEXEC') or die('Saya tidak suka kamu ;)');

//function com_uninstall() {
	$dest = JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'installer' . DS . 'adapters' . DS .'package.php';
	JFile::delete($dest);
//} 
 
?>
