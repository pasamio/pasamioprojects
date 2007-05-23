<?php
/**
 * Category Table ETL
 * 
 * This plugin handles ETL for the category table 
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
 * Category ETL Plugin
 */
class Categories_ETL extends ETLPlugin {
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Categories ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'categories'; }
	
}
?>