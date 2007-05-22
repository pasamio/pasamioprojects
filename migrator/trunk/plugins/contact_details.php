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
class ContactDetails_ETL extends ETLPlugin {
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Contact Details ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'contactdetails'; }
	
	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() { 
		$this->db->setQuery('SELECT count(*) FROM #__contact_details');
		return $this->db->loadResult();	
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__contact_details LIMIT '. $start . ','. $amount);
		$retval = Array();
		$results = $this->db->loadAssocList();
		foreach($results as $result) {
			$retval[] = 'INSERT INTO #__contact_details (id, name, con_position, address, suburb, state, country, postcode, telephone, fax, misc, image, imagepos, email_to, default_con, published, checked_out, checked_out_time, ordering, params, user_id, catid, access) '. 
						'VALUES('.$result['id'].', "'.$result['name'].'", "'.$result['con_position'].'","'.$result['address'].'", "'.$result['suburb'].'", "'.$result['state'].'", "'.$result['country'].'", "'.$result['postcode'].'", "'.$result['telephone'].'", "'.
						$result['fax'].'", "'.$result['misc'].'","'.$result['image'].'", "'.$result['imagepos'].'", "'.$result['email_to'].'", '.$result['default_con'].', '.$result['published'].', '.$result['checked_out'].', "'.$result['checked_out_time'].'", '.
						$result['ordering'].', "'.$result['params'].'", '.$result['user_id'].', '.$result['catid'].', '.$result['access'].');'; 
		}
		return $retval;
	}
}
?>