<?php
/**
 * Users ETL Plugin
 * 
 * Users ETL Plugin for #__users
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
 * Users ETL plugin
 */
class Users_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('params');
	
	function getName() { return "Users ETL Plugin"; }
	function getAssociatedTable() { return 'users'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'params':
				$value = preg_replace('/editor=[A-Za-z0-9_.-]*/','editor=',$value); // Strip editor
				return $value;
				break;
			default:
				return $value;
				break;
		}
	}
}
