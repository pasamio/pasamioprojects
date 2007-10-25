<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
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
defined('_JEXEC') or die('Tidak cinta');

jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.filesystem.folder');

/**
 * 
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jpackagemanModelDetails extends JModel
{
	
    function &getDetails($file) {		
		$library = new JLibraryManifest();
		$retval = false;
		$library->manifest_filename = $file;
		if($library->loadManifestFromXML(MANIFEST_PATH . DS . $file . '.xml')) 
			return $library;
		else 
			return $retval;
    }
    
    
}
?> 