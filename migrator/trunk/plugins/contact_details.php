<?php
/**
 * Contact Details Table ETL
 * 
 * This plugin handles ETL for the Contact component 
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
 * Contact ETL Plugin
 */
class Contact_Details_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	
	function getName() { return "Contact Details ETL Plugin"; }
	
	function getAssociatedTable() { return 'contact_details'; }
	
}
?>