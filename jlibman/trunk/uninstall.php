<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Oct 11, 2007
 * 
 * @package JLibMan
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

defined('_JEXEC') or die('Saya tidak suka kamu ;)');

//function com_uninstall() {
	$dest = JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'installer' . DS . 'adapters' . DS .'library.php';
	JFile::delete($dest);
//} 
 
?>
