<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Feb 11, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// check we're in the right place...
defined('_JEXEC') or die('bad karma dude!');
JToolBarHelper::title( JText::_( 'Migration Assistant' ), 'config.png' );

if(isset($_GET['migrate']) && $_GET['migrate']) {
	$db =& JFactory::getDBO();
	$db->setQuery("SELECT `key`,`value` FROM #__migration_configuration");
	$results = $db->loadAssocList();
	if(!is_array($results)) { 
		echo $db->getErrorMsg();
		return;
	}
	$cfg = JFactory::getConfig();
	foreach($results as $result) {
		$cfg->setValue('config.'.$result['key'], $result['value']);
	}
	echo '<p>Updating your configuration file...</p>';
	//echo '<pre>'.print_r(htmlspecialchars($cfg->toString('PHP', 'config', array('class' => 'JConfig'))),1).'</pre>';
	
	jimport('joomla.filesystem.file');
	$fname = JPATH_CONFIGURATION.DS.'configuration.php';
	if (JFile::write($fname, $cfg->toString('PHP', 'config', array('class' => 'JConfig')))) {
		$msg = JText::_('The Configuration Details have been updated');
	} else {
		$msg = JText::_('ERRORCONFIGFILE');
	}
	echo '<p>'. $msg .'</p>';
} else {
	echo '<p>Are you sure you want to migrate settings? <a href="index.php?option=com_migrationassistant&migrate=true">Migrate Now</a></p>';
}
?>