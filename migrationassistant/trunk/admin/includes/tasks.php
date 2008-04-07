<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Apr 7, 2008
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

defined('_JEXEC') or die('guten tag :)');
function migrateSettings(){ 
	$db =& JFactory::getDBO();
	$config =& JFactory::getConfig();
	$tables = $db->getTableList();
	if(in_array($config->get('dbprefix').'migration_configuration',$tables)) {
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
	} else {
		$msg = JText::_('Error') .': '. JText::_('Migration Configuration table not found').'. '. JText::_('Was this site migrated with Migrator RC7 or greater').'?';
	}
	echo '<p>'. $msg .'</p>';
}


function dumpLoad() {
	$model	= new JInstallationModel();
	$model->dumpLoad();
	echo '<p>File loaded</p>';
}

function postmigrate() {
	$model = new JInstallationModel();
	if($model->postMigrate()) {
		// errors!
	}
}

function fullMigrate() {
	$model = new JInstallationModel();
	if(!$model->checkUpload()) {
		handleError($model);
	}
	include(MIGBASE.DS.'includes'.DS.'migpage.php');
}

function handleError(&$incoming) {
	$msg = '';
	if(is_object($incoming)) $msg = $incoming->getError();
	else if(is_string($incoming)) $msg = $incoming;
	else $msg = print_r($incoming,1);
	echo '<p>'. JText::_('Error'). ': '. $msg .'</p>';
}