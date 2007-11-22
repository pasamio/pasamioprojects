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
 * @package JPackageMan
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