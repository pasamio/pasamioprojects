<?php
/**
 * Core ACL Groups ARO Map ETL Plugin
 * 
 * Core ACL Groups ARO Map ETL Plugin for #__core_acl_groups_aro_map
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
 * Core ACL Groups Access Role Object Mapping Table
 */
class Core_ACL_Groups_ARO_Map_ETL extends ETLPlugin {
	function getName() { return "Core ACL Groups ARO Map ETL Plugin"; }
	function getAssociatedTable() { return 'core_acl_groups_aro_map'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
