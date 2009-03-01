<?php
/**
 * Core ACL ARO Groups ETL Plugin
 * 
 * Core ACL ARO Groups ETL Plugin for #__core_acl_aro_groups
 * 
 * MySQL 4.0
 * PHP4
 *  
 * Created on 01/03/2009
 * 
 * @package Migrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * Core ACL Groups Access Role Object Mapping Table
 */
class Core_ACL_ARO_Groups_ETL extends ETLPlugin {
	
	var $namesmap = Array('group_id'); // 1.5 renamed group_id to id for some reason...
	var $newfieldlist = Array('value'); // add a value field
	var $valuesmap = Array('value'); // and map it since it'll be blank
	
	function getName() { return "Core ACL ARO Groups ETL Plugin"; }
	function getAssociatedTable() { return 'core_acl_aro_groups'; }
	
	function getSQLPrologue() {
		// We need to clean the table out because it comes pre-populated
		return "TRUNCATE TABLE #__core_acl_aro_groups;\n";
	}
	
	function getSelectionPreference() {
		// don't select this plugin by default
		return false;
	}
	
	function mapNames($key) {
		switch($key) {
			case 'group_id': // group_id was renamed to id
				return 'id';
				break;
			default:
				return $key;
				break;
		}
	}
	
	function mapValues($key, $input) {
		switch($key) {
			case 'value':
				return $this->_currentRecord['name'];
				break;
			default:
				return $input;
				break;
		}
	}
}
