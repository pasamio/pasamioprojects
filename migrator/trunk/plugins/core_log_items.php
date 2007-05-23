<?php
/**
 * Core Log Items ETL Plugin
 * 
 * Core Log Items ETL Plugin for Tablename
 * 
 * MySQL 4.0
 * PHP4
 *  
 * Created on 23/05/2007
 * 
 * @package JMigrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

class Core_Log_Items_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $maplist = Array();
	
	function getName() { return "Core Log Items ETL Plugin"; }
	function getAssociatedTable() { return 'core_log_items'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
