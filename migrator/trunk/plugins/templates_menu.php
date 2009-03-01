<?php
/**
 * Templates Menu ETL Plugin
 * 
 * Templates Menu ETL Plugin for #__templates_menu
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
 * Templates Menu ETL Plugin
 */
class Templates_Menu_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $namesmap = Array();
	var $valuesmap = Array();
	
	function getName() { return "Templates Menu ETL Plugin"; }
	function getAssociatedTable() { return 'templates_menu'; }
	function getSQLPrologue() { return "TRUNCATE TABLE jos_templates_menu;\n"; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
