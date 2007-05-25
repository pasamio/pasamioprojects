<?php
/**
 * Sections ETL Plugin
 * 
 * Sections ETL Plugin for #__sections
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

class Sections_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array();
	
	function getName() { return "Sections ETL Plugin"; }
	function getAssociatedTable() { return 'sections'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
