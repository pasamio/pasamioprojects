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
class JLibManModelJLibMan extends JModel
{
	
    function &listLibraries() {
		$files =  JFolder::files(LIBRARY_MANIFEST_PATH);
		$retval = Array();
		$file = $files[0];
		
		foreach($files as $file) {
			if(strtolower(JFile::getExt($file)) == 'xml') {
				$retval[] = new JLibraryManifest(LIBRARY_MANIFEST_PATH . DS . $file);
			}
		}
		return $retval;
		
    }
    
    
    function uninstall($libid) {
    	// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = & JInstaller::getInstance();
		return $installer->uninstall('library', $libid, 0 );
    }
}
?>