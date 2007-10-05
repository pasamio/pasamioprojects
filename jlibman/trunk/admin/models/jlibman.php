<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
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
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.filesystem.folder');

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class JLibManModelJLibMan extends JModel
{
	
    /**
    * Gets the greeting
    * @return string The greeting to be displayed to the user
    */
    function getGreeting()
    {
           $db =& JFactory::getDBO();

		   $query = 'SELECT title FROM #__content';
		   $db->setQuery( $query );
		   $greeting = $db->loadResult();
		
		   return $greeting;
    }
    
    function _parseFile($xmlfile) {
    			$xml = JFactory::getXMLParser('Simple');
				//$xml = new JSimpleXML();
				if(!$xml->loadFile($xmlfile)) {
					$this->_errors[] = 'Failed to load XML File: ' . $xmlfile;
				} else {
					return clone($xml->document);
				}
    	return false;
    }
    
    function &listLibraries() {
    	define('MANIFEST_PATH',JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jlibman' . DS .'manifests');
		$files =  JFolder::files(MANIFEST_PATH);
		$retval = Array();
		$file = $files[0];
		
		foreach($files as $file) {
			if(strtolower(JFile::getExt($file)) == 'xml') {
				$tmp = $this->_parseFile(MANIFEST_PATH . DS . $file);
				if($tmp) $retval[] = $tmp;
			}
		}
		return $retval;
		
    }
    
    
}
?>