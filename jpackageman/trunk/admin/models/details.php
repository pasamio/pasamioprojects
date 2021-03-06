<?php
/**
 * JPackageMan Details Model
 * 
 * This model displays the details of the package 
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package JPackageMan
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
 * JPackageMan Details Model
 *
 * @package    JPackageMan
 * @subpackage Models
 */
class jpackagemanModelDetails extends JModel
{
	
    function &getDetails($file) {		
		$package = new JPackageManifest();
		$retval = false;
		$package->manifest_filename = $file;
		if($package->loadManifestFromXML(PACKAGE_MANIFEST_PATH . DS . $file . '.xml')) 
			return $package;
		else 
			return $retval;
    }
    
    
}
?> 
