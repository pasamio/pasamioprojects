<?php
/**
 * Banner Client Table ETL
 * 
 * This plugin handles ETL for the banner plugin 
 * 
 * PHP4
 *  
 * Created on May 22, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

/**
 * Banner Client ETL Plugin
 */
class BannerClient_ETL extends ETLPlugin {
	
	function getName() { return "Banner Client ETL Plugin"; }
	
	function getAssociatedTable() { return 'bannerclient'; }

}
?>
