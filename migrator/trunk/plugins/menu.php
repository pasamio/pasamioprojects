<?php
/**
 * Menu ETL Plugin
 * 
 * Menu ETL Plugin for Tablename
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

class Menu_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $maplist = Array();
	
	function getName() { return "Menu ETL Plugin"; }
	function getAssociatedTable() { return 'menu'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>
