<?php
/**
 * Contact Details Table ETL
 * 
 * This plugin handles ETL for the Contact component 
 * 
 * PHP4
 *  
 * Created on May 22, 2007
 * 
 * @package JMigrator
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

/**
 * Contact ETL Plugin
 */
class Contact_Details_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	
	var $valuesmap = Array('alias');
	
	var $newfieldlist = Array('alias');
	
	function getName() { return "Contact Details ETL Plugin"; }
	
	function getAssociatedTable() { return 'contact_details'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'alias':
				if(!strlen(trim($value))) {
					return stringURLSafe($this->_currentRecord['name']);
				}
				return $value;
				break; // could really let this drop down here but anyway
			default:
				return $value;
				break;
		}
	}
}
?>