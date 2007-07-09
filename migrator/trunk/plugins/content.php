<?php
/**
 * Content Table ETL
 * 
 * This plugin handles ETL for the Content Component 
 * 
 * PHP4
 *  
 * Created on May 22, 2007
 * 
 * @package JMigrator
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

/**
 * Content ETL Plugin
 */
class Content_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('introtext','fulltext');
	function getName() { return "Content ETL Plugin"; }	
	function getAssociatedTable() { return 'content'; }
	
	function mapvalues($key,$value) {

		switch($key) {
			case 'introtext':
			case 'fulltext':
				// Remove mosimage
				$value = str_replace('{mosimage}','',$value);		
				break;
			default:
				return $value;
				break;
		}
	}

}
?>