<?php
/**
 * @version 	$Id: listproject.php 162 2006-08-11 23:45:36Z willebil $
 * @package 	Joomla
 * @subpackage	J!Package Directory
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.model');
require_once (JPATH_COM_JPACKAGEDIR.DS.'helpers'.DS.'jpackagedir.helper.php');

/**
 * Model ListProject : overview of directory projects
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package
 * @since		1.5
 */
class JPackageDirModelListProject extends JModel
{
	/**
	 * Entity ID
	 * @var int
	 */
	var $_id = 0;

	/**
	 * Entity Data
	 * @var object
	 */
	var $_list = null;

	/**
	 * Constructor
	 *
	 * @since	1.5
	 * @param	object A JDatabase object
	 * @access	protected
	 */
	function __construct( &$dbo ) {
		$this->_db = &$dbo;
	}

	/**
	 * Method to get list data from the database and other specific values.
	 *
	 * @since	1.5
	 * @access	public
	 * @return	array	contains all field values that will be rendered in the view
	 * @return  boolean	false if there was an error
	 */
	function getListProject()
	{
		global $mainframe;
		$db	=& $this->getDBO();

		// Determine paging variables
		$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
		$limitstart = $mainframe->getUserStateFromRequest( "viewlimitstart", 'limitstart', 0 );

		// Determine basic variables 
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$sname = JRequest::getVar('sname', '', '', 'string');
		$this->_list['option'] = $option; 
		$this->_list['sproject'] = $project;
		$this->_list['sname'] = $sname;
		$this->_list['scategory'] = $category;
		$this->_list['sowner'] = $owner;

		// Determine full list of possible project categories
		$this->_list['category'] = JPackageDirGeneralHelper::getCategoryHTML ( 0, 'category', false, 'onchange="document.adminForm.submit();"', $category );

		// Determine full list of possible project owners
		$owners[] = mosHTML::makeOption( '', JText::_('Select Owner'));

		$query = "SELECT a.id, a.username"
		. "\nFROM #__users AS a, #__jpackagedir_projects AS b"
		. "\nWHERE a.id = b.created_by"
		. "\nGROUP BY 2"
		. "\nORDER BY 2";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$owners[] = mosHTML::makeOption( $row->id, $row->username );
		} # End for
		$this->_list['owner'] = mosHTML::selectList( $owners, 'owner', 'class="inputbox" size="1"' . 'onchange="document.adminForm.submit();"', 'value', 'text', $owner);

		// Create option field for package names.
		$query = "SELECT name FROM #__jpackagedir_packages GROUP BY 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$names[] = mosHTML::makeOption( '', JText::_('Select Package'));
		foreach ($rows as $row) {
			$names[] = mosHTML::makeOption( $row->name, $row->name );
		} # End for
		$this->_list['pname'] = mosHTML::selectList( $names, 'sname', 'class="inputbox" size="1"' . 'onchange="document.adminForm.submit();"', 'value', 'text', $sname);

		// Determine where clause for current selection
		$this->_list['rows'] = Array();
		$where = Array();
		if (!empty($project)) { $where[] = "name LIKE '$project'"; }
		if (!empty($owner)) { $where[] = "created_by = '$owner'"; }
		if (!empty($category) && $category !=0 ) { $where[] = "b.category_id = $category"; }
		if (!empty($sname)) { $where[] = "name = '$sname'"; }
		$where = (count($where) ? "\n WHERE ".implode(' AND ', $where) : '');

		// Get the total number of records, this is used for paging option
		// in form.
		$query = "SELECT COUNT(*)"
		. "\nFROM jos_jpackagedir_projects AS a"
		. "\nLEFT JOIN jos_jpackagedir_relations AS b"
		. "\nON a.id = b.project_id"
		. "\n$where";

		$db->setQuery( $query );
		$total = $db->loadResult();

		// Now read the current dataset, and pass it on...
		$query = "SELECT a.id, a.name, a.created_by, a.created_by_alias, a.published, a.checked_out, a.checked_out_time"
		. "\nFROM jos_jpackagedir_projects AS a"
		. "\nLEFT JOIN jos_jpackagedir_relations AS b"
		. "\nON a.id = b.project_id"
		. "\n$where"
		. "\nGROUP BY 1"
		. "\nORDER BY a.name";

		$db->setQuery( $query, $limitstart, $limit );
		$this->_list['rows'] = $this->_db->loadObjectList();

		if ($db->getErrorNum()) {
			return false;
		} # End if

		// Initialize the paging, offer the total number of records, the first record
		// for current page and the number of records to render.
		include_once(JPATH_BASE.DS."includes".DS."pageNavigation.php");
		$this->_list['pagenav'] = new mosPageNav( $total, $limitstart, $limit  );

		return $this->_list;
	}
	/**
	* Set the state of selected project items
	* @param	array	list of id's we want to set the state
	* @param	integer	value to set
	* @return	true/false for success/error
	*/
	function setState( $items, $state )
	{
		global $mainframe;
		$db	=& $this->getDBO();

		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirProjects', $db);

		foreach ($items as $id) {
			$row->load( $id );
			$row->published = $state;

			if (!$row->check()) {
				$this->setError($row->getError());
				return false;
			}
			if (!$row->store()) {
				$this->setError($row->getError());
				return false;
			}
		}
		return true;
	}
}
?>