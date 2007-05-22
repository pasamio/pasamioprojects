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
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Content ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'content'; }
	
	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() { 
		$this->db->setQuery('SELECT count(*) FROM #__content');
		return $this->db->loadResult();	
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__content LIMIT '. $start . ','. $amount);
		$retval = Array();
		$results = $this->db->loadAssocList();
		foreach($results as $result) {
			// TODO: Need to encode the content!
			$retval[] = 'INSERT INTO #__content (id, title, title_alias, introtext, fulltext, state,sectionid, mask, catid, created, created_by, created_by_alias, modified, modified_by, checked_out, checked_out_time, publish_up, publish_down, images, urls, attribs, version, parentid, ordering, metakey, metadesc, access, hits) '. 
						'VALUES('.$result['id'].', "'.$result['title'].'", "'.$result['title_alias'].'", "'.$result['introtext'].'", "'.$result['fulltext'].'", '.$result['state'].','.$result['sectionid'].', '.$result['mask'].', '.$result['catid'].', "'.$result['created'].'", '.$result['created_by'].', "'.
						$result['created_by_alias'].'", "'.$result['modified'].'", '.$result['modified_by'].', '.$result['checked_out'].', "'.$result['checked_out_time'].'", "'.$result['publish_up'].'", "'.$result['publish_down'].'", "'.$result['images'].'", "'.$result['urls'].'", "'.$result['attribs'].'", '.
						$result['version'].', '.$result['parentid'].', '.$result['ordering'].', "'.$result['metakey'].'", "'.$result['metadesc'].'", '.$result['access'].', '.$result['hits'].');'; 
		}
		return $retval;
	}
}
?>