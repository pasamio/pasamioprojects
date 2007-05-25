<?php
/**
 * Weblinks ETL Plugin
 * 
 * Weblinks ETL Plugin for #__weblinks
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

class Weblinks_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array();
	
	function getName() { return "Weblinks ETL Plugin"; }
	function getAssociatedTable() { return 'weblinks'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
