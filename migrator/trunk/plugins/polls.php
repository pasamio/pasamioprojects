<?php
/**
 * Polls ETL Plugin
 * 
 * Polls ETL Plugin for #__polls
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

class Polls_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array();
	
	function getName() { return "Polls ETL Plugin"; }
	function getAssociatedTable() { return 'polls'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
