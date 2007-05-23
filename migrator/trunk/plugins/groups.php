<?php
/**
 * Groups ETL Plugin
 * 
 * Groups ETL Plugin for #__groups
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

class Groups_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $maplist = Array();
	
	function getName() { return "Groups ETL Plugin"; }
	function getAssociatedTable() { return 'groups'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
