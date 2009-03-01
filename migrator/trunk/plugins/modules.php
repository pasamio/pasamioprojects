<?php
/**
 * Modules ETL Plugin
 * 
 * Modules ETL Plugin for #__modules
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
 * Modules Data Table
 */
class Modules_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array('groups');
	var $valuesmap = Array('params');
	
	function getName() { return "Modules ETL Plugin"; }
	function getAssociatedTable() { return 'modules'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
