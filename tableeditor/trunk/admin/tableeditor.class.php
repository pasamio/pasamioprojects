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
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
if(!class_exists('TableInstance')) {
	class TableInstance extends JObject {
		
		var $name = '';
		var $key = '';
		var $table = '';
		var $columns = Array();
		var $params = Array();
		var $table_file = '';
		
		function __construct($xmlpath='') {
			if(strlen($xmlpath)) $this->loadManifestFromXML($xmlpath);
		}
		
		function loadManifestFromXML($xmlfile) {
			$this->table_file = JFile::stripExt(basename($xmlfile));
			$xml = JFactory::getXMLParser('Simple');
			if(!$xml->loadFile($xmlfile)) {
				$this->_errors[] = 'Failed to load XML File: ' . $xmlfile;
				return false;
			} else {
				$xml = $xml->document;
				$this->name = $xml->name[0]->data();
				$this->table = $xml->table[0]->data();
				$this->key = $xml->key[0]->data();
				if(isset($xml->list[0]->column) && count($xml->list[0]->column)) {
					foreach($xml->list[0]->column as $column) {
						$attr = $column->attributes();
						$this->columns[$attr['key']] =  $attr['name']; 
					}
				}
				if(isset($xml->params[0]->param) && count($xml->params[0]->param)) {
					foreach($xml->params[0]->param as $param) {
						$attr = $param->attributes();
						$this->params[$attr['name']] =  $attr['label']; 
					}
				}
				return true;
			}
		}
	}
}
?>
