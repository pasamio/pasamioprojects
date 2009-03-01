<?php
/**
 * Newsfeeds ETL Plugin
 * 
 * Newsfeeds ETL Plugin for #__newsfeeds
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
 * Newsfeed ETL plugin
 */
class Newsfeeds_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	
	var $valuesmap = Array('alias');
	
	var $newfieldlist = Array('alias');	
	
	function getName() { return "Newsfeeds ETL Plugin"; }
	function getAssociatedTable() { return 'newsfeeds'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'alias':
				if(!strlen(trim($value))) {
					return stringURLSafe($this->_currentRecord['name']);
				}
				return $value;
				break; // could really let this drop down here but anyway
			default:
				return $value;
				break;
		}
	}
}

