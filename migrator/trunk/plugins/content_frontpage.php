<?php
/**
 * Frontpage Content ETL Plugin
 * 
 * Frontpage Content ETL Plugin for #__content_frontpage
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

class Content_Frontpage_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array();
	
	function getName() { return "Frontpage Content ETL Plugin"; }
	function getAssociatedTable() { return 'content_frontpage'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			default:
				return $value;
				break;
		}
	}
}
?>