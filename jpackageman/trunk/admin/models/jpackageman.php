<?php
/**
 * JPackageMan Default Model
 * 
 * This is the default list view model 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
 * 
 * @package JPackageMan
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.filesystem.folder');

/**
 * JPackageMan Default Model (List)
 *
 * @package    JPackageMan
 * @subpackage Models
 */
class jpackagemanModeljpackageman extends JModel
{
	
    function &listLibraries() {
		$files =  JFolder::files(MANIFEST_PATH);
		$retval = Array();
		$file = $files[0];
		
		foreach($files as $file) {
			if(strtolower(JFile::getExt($file)) == 'xml') {
				$retval[] = new JLibraryManifest(MANIFEST_PATH . DS . $file);
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
