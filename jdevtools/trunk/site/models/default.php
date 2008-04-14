<?php
/**
 * Default Model
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Sep 21, 2007
 * 
 * @package JDevTools
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * JDevTools Model
 *
 * @package    JDevTools
 */
class JDevToolsModelDefault extends JModel
{
    /**
    * Gets the tracker links
    * @return object rows from the DB
    */
    function getTrackerLinks()
    {
           $db =& JFactory::getDBO();
			$user =& JFactory::getUser();
		   $query = 'SELECT * FROM #__jdevtools_trackerlinks NATURAL JOIN #__jdevtools_userlinks WHERE uid = ' . $user->id ;
		   $db->setQuery($query);
		   $results = $db->loadObjectList();
		
		   return $results;
    }
    
    /**
     * Finds the Link ID for a given link
     * @param string $link URL of Link
     * @return int Link ID or 0
     */
    function findLink($link) {
		$db 	=&	JFactory::getDBO();
		$query = 'SELECT lid FROM #__jdevtools_trackerlinks WHERE url = "'.$link.'"';
		$db->setQuery($query);
		$result = $db->loadResult();
		if(!$result) $result = 0;
		return $result;
    }
    
    /**
     * Adds a link to the users link list and potentially tracker link table
     * @param string $link The name of the link
     * @return int ID of new link or 0
     */
    function addLinkForUser($link, $user) {
		$lid = 0;
		if(($lid = $this->findLink()) !== FALSE) {
			$lid = $this->addLink($link);
		}
		$user =& JFactory::getUser();
		$query = 'INSERT INTO #__jdevtools_userlinks VALUES('.$lid.','.$user->id.')';
		$db->setQuery();
		$db->Query();
		return $lid;
    }
    
    /**
     * Adds a link to the tracker link table
     * @param string $link The name of the link
     * @return int ID of new link or 0
     */
    function addLink($link) {
		$db 	=& 	JFactory::getDBO();
		$query 	= 	'INSERT INTO #__jdevtools_trackerlinks VALUES(0, "'.$db->escape($link).'", NOW(), "Undefined")';
		$db->setQuery($query);
		$db->Query();
		$id 	= 	$db->insertid;
		return $id;
    }
}
?>
