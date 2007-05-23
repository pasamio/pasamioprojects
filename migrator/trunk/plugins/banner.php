<?php
/**
 * Banner Table ETL
 * 
 * This plugin handles ETL for the banner plugin 
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
 * Banner ETL Plugin
 */
class Banner_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $maplist = Array('');
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Banner ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'banner'; }
	
}
?>