<?php
/**
 * @version 	$Id: list.php 137 2006-07-30 15:44:37Z willebil $
 * @package 	JPackageDir
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
 * Model List : overview of directory elements handler
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package
 * @since		1.5
 */
class JPackageDirModelList extends JModel
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
	 * Method to get list data from the database.
	 *
	 * @since	1.5
	 * @access	public
	 * @return	array	contains all field values that will be rendered in the view
	 * @return  boolean	false if there was an error
	 */
	function getList()
	{
		global $mainframe;
		$db	=& $this->getDBO();

		// Determine paging variables
		$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
		$limitstart = $mainframe->getUserStateFromRequest( "viewlimitstart", 'limitstart', 0 );

		// Determine basic variables 
		$option = JRequest::getVar('option', '', '', 'string');
		$version = JRequest::getVar('version', '', '', 'string');
		$type = JRequest::getVar('type', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');
		$this->_list['option'] = JRequest::getVar('option', '', '', 'string'); 
		$this->_list['version'] = $version;
		$this->_list['stype'] = $type;
		$this->_list['sname'] = $name;

		// Create option field for servertype
		$types[] = Array();
		$array  = Array (0 => Array ('component', JText::_( 'Component')),
	    	             1 => Array ('module', JText::_( 'Module')),
		                 2 => Array ('plugin', JText::_( 'Plugin')),
	                 	 3 => Array ('template', 'Template'),
	                 	 4 => Array ('language', JText::_( 'Language'))
                         );

		$types[] = mosHTML::makeOption( '', JText::_('Select Type'));
		for ($i=0; $i<count($array); $i++) {
			$types[] = mosHTML::makeOption( $array[$i][0], $array[$i][1] );
		} # End for
		$this->_list['type'] = mosHTML::selectList( $types, 'type', 'class="inputbox" size="1"' . 'onchange="document.adminForm.submit();"', 'value', 'text', $type);

		// Create option field for package names.
		$query = "SELECT name FROM #__jpackagedir_packages GROUP BY 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$names[] = mosHTML::makeOption( '', JText::_('Select Package'));
		foreach ($rows as $row) {
			$names[] = mosHTML::makeOption( $row->name, $row->name );
		} # End for
		$this->_list['name'] = mosHTML::selectList( $names, 'name', 'class="inputbox" size="1"' . 'onchange="document.adminForm.submit();"', 'value', 'text', $name);

		// Determine where clause
		$this->_list['rows'] = Array();
		$where = Array();
		if (!empty($type)) { $where[] = "type = '$type'"; }
		if (!empty($name)) { $where[] = "name = '$name'"; }
		if (!empty($version)) { $where[] = "version = '$version'"; }
		$where = (count($where) ? "\n WHERE ".implode(' AND ', $where) : '');

		// Get the total number of records, this is used for paging option
		// in form.
		$query = "SELECT COUNT(*) FROM #__jpackagedir_packages" . $where;
		$db->setQuery( $query );
		$total = $db->loadResult();

		// Now read the current dataset, and pass it on...
		$query = "SELECT * FROM #__jpackagedir_packages $where ORDER BY version";
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
	* Set the state of selected package items
	* @param	array	list of id's we want to set the state
	* @param	integer	value to set
	* @return	true/false for success/error
	*/
	function setState( $items, $state )
	{
		global $mainframe;
		$db	=& $this->getDBO();

		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirPackages', $db);

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
