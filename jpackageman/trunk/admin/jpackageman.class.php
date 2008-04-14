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
 * @package JPackageMan
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
if(!class_exists('JPackageManifest')) {
	class JPackageManifest extends JObject {
		
		var $name = '';		
		var $packagename = '';
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
				$this->packagename = $xml->packagename[0]->data();
				$this->update = $xml->update[0]->data();
				$this->url = $xml->url[0]->data();
				$this->description = $xml->description[0]->data();
				$this->packager = $xml->packager[0]->data();
				$this->packagerurl = $xml->packagerurl[0]->data();
				$this->version = $xml->version[0]->data();
				if(isset($xml->files[0]->file) && count($xml->files[0]->file)) {
					foreach($xml->files[0]->file as $file) {
						$this->filelist[] = new JExtension($file);
					}
				}
				return true;
			}
		}
	}
	
	class JExtension extends JObject {
		
		var $filename = '';
		var $type = '';
		var $id = '';
		var $client = 'site'; // valid for modules, templates and languages; set by default
		var $group =  ''; // valid for plugins
		
		function __construct($element=null) {
			if($element && is_a($element, 'JSimpleXMLElement')) {
				$this->type = $element->attributes('type');
				$this->id = $element->attributes('id');
				switch($this->type) {
					case 'module':
					case 'template':
					case 'language':
						$this->client = $element->attributes('client');
						$this->client_id = JApplicationHelper::getClientInfo($this->client,1);
						$this->client_id = $this->client_id->id;
						break;
					case 'plugin':
						$this->group = $element->attributes('group');
						break;
				}
				$this->filename = $element->data();
			}
		}
	}

}
?>
