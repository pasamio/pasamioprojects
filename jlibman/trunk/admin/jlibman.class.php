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
 * @package JLibMan
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
if(!class_exists('JLibraryManifest')) {
	class JLibraryManifest extends JObject {
		
		var $name = '';
		var $libraryname = '';
		var $url = '';
		var $description = '';
		var $packager = '';
		var $packagerurl = '';
		var $update = '';
		var $version = '';
		var $filelist = Array();
		var $manifest_file = '';
		
		function __construct($xmlpath='') {
			if(strlen($xmlpath)) $this->loadManifestFromXML($xmlpath);
		}
		
		function loadManifestFromXML($xmlfile) {
			$this->manifest_file = JFile::stripExt(basename($xmlfile));
			$xml = JFactory::getXMLParser('Simple');
			if(!$xml->loadFile($xmlfile)) {
				$this->_errors[] = 'Failed to load XML File: ' . $xmlfile;
				return false;
			} else {
				$xml = $xml->document;
				$this->name = $xml->name[0]->data();
				$this->libraryname = $xml->libraryname[0]->data();
				$this->update = $xml->update[0]->data();
				$this->url = $xml->url[0]->data();
				$this->description = $xml->description[0]->data();
				$this->packager = $xml->packager[0]->data();
				$this->packagerurl = $xml->packagerurl[0]->data();
				$this->version = $xml->version[0]->data();
				if(isset($xml->files[0]->file) && count($xml->files[0]->file)) {
					foreach($xml->files[0]->file as $file) {
						$this->filelist[] = $file->data();
					}
				}
				return true;
			}
		}
	}
}
?>
