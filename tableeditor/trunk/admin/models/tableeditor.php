<?php
/**
 * JLibMan Main Model
 * 
 * Main display and listing model 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
 * 
 * @package JLibMan
 * @author Sam Moffatt <s.@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.filesystem.folder');

/**
 * Main Model
 *
 * @package    JLibMan
 */
class TableEditorModelTableEditor extends JModel
{
	
    function &listTables() {
		$files =  JFolder::files(TABLE_STORE_PATH);
		$retval = Array();
		$file = $files[0];
		
		foreach($files as $file) {
			if(strtolower(JFile::getExt($file)) == 'xml') {
				$retval[] = new TableInstance(TABLE_STORE_PATH . DS . $file);
			}
		}
		return $retval;
    }
    
	var $_instance = false;
	var $_data = Array();
	
	function loadDataFile() {
		if(!$this->_instance) { 
			$table = JRequest::getWord('table','');
			if($table) {
				$filename = TABLE_STORE_PATH . DS . $table .  '.xml';
				if(file_exists($filename)) {
					$this->_instance = new TableInstance($filename);
					return true;
				}
			}
		} else return true; // already have the data
		return false;	
	} 	
	
    function &loadData() {
    			
		$key = JRequest::getVar('key','');
		$retval = false;
		if($key) {
			$this->loadDataFile() or die('Failed to load data');
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__". $this->_instance->table .
				' WHERE '. $this->_instance->key .
				' = "'. $db->getEscaped($key) .'"');
			$this->_data = $db->loadAssoc();
		}
		return $this->_data;
    }
	
	function getRows() {
		$this->loadDataFile() or die('Failed to load data');
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT * FROM #__'. $this->_instance->table);
		return $db->loadObjectList();
	}
	
	function getTableInfo() {
		$this->loadDataFile() or die('Failed to load data');
		return $this->_instance;
	}    
    
    function uninstall($tableid) {
    	// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = & JInstaller::getInstance();
		return $installer->uninstall('table', $tableid, 0 );
    }
}
?>