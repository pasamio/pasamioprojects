<?php
/**
 * Backlink Migration ETL
 * 
 * This plugin handles ETL for the menu system to handle
 * back link creation in 1.5 
 * 
 * PHP4
 *  
 * Created on May 22, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * Backlink Migration ETL Plugin
 */
class Backlink_Migration_ETL extends ETLPlugin {
	
	/**
	 * Returns the name of the plugin
	 */
	function getName() { return "Backlink Migration ETL Plugin"; }
	
	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() { return 'backlink_migration'; }
	
	/**
	 * Returns the table that this turns into
	 */
	function getTargetTable() { return 'migration_backlinks'; }
	
	/**
	 * Returns the amount of rows
	 */
	function getEntries() {
		$this->db->setQuery('SELECT count(*) FROM #__menu');
		return $this->db->loadResult();
	}
	
	/**
	 * Override the doTransformation to handle the menu system
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__menu LIMIT ' . $start . ',' . $amount);
		$retval = Array ();
		$results = $this->db->loadObjectList();
		if(!count($results)) return $retval;
		foreach ($results as $result) {
			$data = $this->mosGetMenuLink($result);
			$retval[] = 'INSERT INTO jos_' . $this->getTargetTable() . ' VALUES('.$result->id . 
					',"'. mysql_real_escape_string($result->name) .'","'.mysql_real_escape_string($data['link']).
					'","'.mysql_real_escape_string($data['sef']).'","");'."\n";
		}
		return $retval;
	}

	/**
	* Utility function for writing a menu link
	* Borrowed from modules/mod_mainmenu.php
	*/
	function mosGetMenuLink( $mitem ) {
		global $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_sef, $mainframe;
		// Grab the sef
		include_once($mosConfig_absolute_path.'/includes/sef.php');
		
		switch ($mitem->type) {
			case 'separator':
			case 'component_item_link':
				break;
				
			case 'url':
				if ( eregi( 'index.php\?', $mitem->link ) && !eregi( 'http', $mitem->link ) && !eregi( 'https', $mitem->link ) ) {
					if ( !eregi( 'Itemid=', $mitem->link ) ) {
						$mitem->link .= '&Itemid='. $mitem->id;
					}
				}
				break;
				
			case 'content_item_link':
			case 'content_typed':
				// load menu params
				//$menuparams = new mosParameters( $mitem->params, $mainframe->getPath( 'menu_xml', $mitem->type ), 'menu' );
				
				//$unique_itemid = $menuparams->get( 'unique_itemid', 1 );
				
//				if ( $unique_itemid ) {
					$mitem->link .= '&Itemid='. $mitem->id;
				/*} else {
					$temp = split('&task=view&id=', $mitem->link);
					
					if ( $mitem->type == 'content_typed' ) {
						$mitem->link .= '&Itemid='. $mainframe->getItemid($temp[1], 1, 0);
					} else {
						$mitem->link .= '&Itemid='. $mainframe->getItemid($temp[1], 0, 1);
					}
				}*/
				break;

			default:
				$mitem->link .= '&Itemid='. $mitem->id;
				break;
		}

		// replace & with amp; for xhtml compliance
		//$mitem->link = ampReplace( $mitem->link );
		//^^We're matching links here so we can ignore this

		// run through SEF convertor, kill off lives site and return
		$sef = ltrim(str_replace($mosConfig_live_site,'',sefRelToAbs( $mitem->link )),'/');
		return Array('link'=>$mitem->link, 'sef'=>$sef);
	}	
	
}

