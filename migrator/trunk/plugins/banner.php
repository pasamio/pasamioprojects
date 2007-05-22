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
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Banner ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'banner'; }
	
	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() { 
		$this->db->setQuery('SELECT count(*) FROM #__banner');
		return $this->db->loadResult();	
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__banner LIMIT '. $start . ','. $amount);
		$retval = Array();
		$results = $this->db->loadAssocList();
		foreach($results as $result) {
			$retval[] = 'INSERT INTO #__banner (bid,cid,type,name,imptotal,impmade,clicks,imageurl,clickurl,date,showBanner,checked_out,checked_out_time,editor,custombannercode) '. 
						'VALUES('. $result['bid'] . ', '. $result['cid'] .', "'.$result['type'].'","'.$result['name'].'", '.$result['imptotal'].','.$result['impmade'].','.$result['clicks'].',"'.
						$result['imageurl'].'","'.$result['clickurl'].'","'.$result['date'].'",'.$result['showBanner'].','.$result['checked_out'].',"'.$result['checked_out_time'].'","'.
						$result['editor'].'","'.$result['custombannercode'].'");'; 
		}
		return $retval;
	}
}
?>