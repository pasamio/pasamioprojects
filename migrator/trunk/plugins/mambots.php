<?php
/**
 * Mambot to Plugin ETL Plugin
 * 
 * Mambot to Plugin ETL Plugin for #__mambots to #__plugins
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

class Mambots_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('params');
	
	function getName() { return "Mambot to Plugin ETL Plugin"; }
	function getAssociatedTable() { return 'mambots'; }
	function getTargetTable() { return 'plugins'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'params':
				return $value;
				break;
			default:
				return $value;
				break;
		}
	}
}
?>
