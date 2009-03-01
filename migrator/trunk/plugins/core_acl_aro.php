<?php
/**
 * Core ACL ARO ETL Plugin
 * 
 * Core ACL ARO ETL Plugin for #__core_acl_aro
 * 
 * MySQL 4.0
 * PHP4
 *  
 * Created on 23/05/2007
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
 * Core ACL Access Role Object Migration
 */
class Core_ACL_ARO_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array();
	var $namesmap = Array('aro_id');
	
	function getName() { return "Core ACL ARO ETL Plugin"; }
	function getAssociatedTable() { return 'core_acl_aro'; }
	
	function mapNames($name) {
		switch($name) {
			case 'aro_id': return 'id'; break; // Rename the aro_id field
			default: return $name; break;
		}
	}
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}

