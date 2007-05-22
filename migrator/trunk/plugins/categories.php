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
class Category_ETL extends ETLPlugin {
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Category ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'categories'; }
	
	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() { 
		$this->db->setQuery('SELECT count(*) FROM #__categories');
		return $this->db->loadResult();	
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__categories LIMIT '. $start . ','. $amount);
		$retval = Array();
		$results = $this->db->loadAssocList();
		foreach($results as $result) {
			// TODO: Do some sort of param shuffling here perhaps
			$retval[] = 'INSERT INTO #__category (id, parent_id, title, name, alias, image, section, image_position, description, published, checked_out, checked_out_time, editor, ordering, access, count, params) '. 
						'VALUES('.$result['id'].', '.$result['parent_id'].', "'.$result['title'].'", "'.$result['name'].'","'.$result['alias'].'","'.$result['image'].'","'.$result['section'].'","'.$result['image_position'].'","'.
						$result['description'].'", '.$result['published'].', '.$result['checked_out'].', "'.$result['checked_out_time'].'", "'.$result['editor'].'", '.$result['ordering'].', '.$result['access'].', '.$result['count'].', '.$result['params'].');'; 
		}
		return $retval;
	}
}
?>