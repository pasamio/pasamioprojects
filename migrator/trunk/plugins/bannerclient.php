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
 * @package JMigrator
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

/**
 * Banner Client ETL Plugin
 */
class BannerClient_ETL extends ETLPlugin {
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Banner Client ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'bannerclient'; }
	
	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() { 
		$this->db->setQuery('SELECT count(*) FROM #__bannerclient');
		return $this->db->loadResult();	
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__bannerclient LIMIT '. $start . ','. $amount);
		$retval = Array();
		$results = $this->db->loadAssocList();
		foreach($results as $result) {
			$retval[] = 'INSERT INTO #__bannerclient (cid,name,contact,email,extrainfo,checked_out, checked_out_time, editor) '.
						'VALUES('. $result['cid'] . ', "'.$result['name'].'", "'.$result['contact'].'","'.$result['email'].'","'.$result['extrainfo'].'",'.
						$result['checked_out'].',"'.$result['checked_out_time'].'","'.$result['editor'].'");'; 
		}
		return $retval;
	}
}
?>