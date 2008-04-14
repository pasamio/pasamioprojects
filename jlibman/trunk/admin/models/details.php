<?php
/**
 * JLibMan Details Model
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package JLibMan
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
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
 * @package    JLibMan
 */
class JLibManModelDetails extends JModel
{
	
    function &getDetails($file) {		
		$library = new JLibraryManifest();
		$retval = false;
		$library->manifest_filename = $file;
		if($library->loadManifestFromXML(LIBRARY_MANIFEST_PATH . DS . $file . '.xml')) 
			return $library;
		else 
			return $retval;
    }
    
    
}
?> 
