<?php
/**
 * @version 	$Id: project.php 162 2006-08-11 23:45:36Z willebil $
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
// No direct access and basic intialisation.
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.model');
require_once (JPATH_COM_JPACKAGEDIR.DS.'helpers'.DS.'jpackagedir.helper.php');

/**
 * Model Element : project handler
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package
 * @since		2.0
 */
class JPackageDirModelProject extends JModel
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
	var $_project = null;

	/**
	 * Constructor
	 *
	 * @param object A JDatabase object
	 * @since 2.0
	 */
	function __construct( &$dbo ) {
		$this->_db = &$dbo;
		$mParams = JComponentHelper::getMenuParams();
		$id = JRequest::getVar('id', $mParams->get( 'id', 0 ), '', 'int');
		$this->setId($id);
	}

	/**
	 * Method to set the Element id
	 *
	 * @access	public
	 * @param	int	Entity ID number
	 */
	function setId($id)
	{
		// Set new article ID and wipe data
		$this->_id		= $id;
		$this->_project	= null;
	}

	/**
	 * Method to get field information for given package
	 *
	 * @since 2.0
	 */
	function getProject()
	{
		global $mainframe;
		$db	=& $this->getDBO();
		$user = & $mainframe->getUser();

		// Determine basic variables 
		$id	= JRequest::getVar( 'id', 0, '', 'int' );
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');
		$this->_project['option'] = $option; 
		$this->_project['sproject'] = $project;
		$this->_project['sname'] = $name;
		$this->_project['scategory'] = $category;
		$this->_project['sowner'] = $owner;

		$nullDate = $db->getNullDate();

		// Create and load the project row
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirProjects', $db);
		$row->load($id);

		// Now we just modify some vars for nice and neath rendering (only with
		// existing records).
		if ($row->id > 0) {
			// Fail if checked out not by current user, if we pass the check,
			// just flip the scheckoutflag.
			if ($row->checked_out && $row->checked_out <> $user->_id) {
				josRedirect( "index2.php?option=$option&task=showdirectory&version=$version&type=$type&name=$name",  JText::_('Record is currently edited by someone else'));
			} # End if

			$row->created = mosFormatDate( $row->created, '%Y-%m-%d %H:%M:%S' );
			$row->modified = mosFormatDate( $row->modified, '%Y-%m-%d %H:%M:%S' );
			$owner = $row->created_by;
		} else {
			$owner = $user->get('id');
		}

		$this->_project['row'] = $row;

		// Determine all possible owners
		$owners[] = mosHTML::makeOption( '', JText::_('Select Owner'));

		$query = "SELECT id, username"
		. "\nFROM #__users"
		. "\nORDER BY 2";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$owners[] = mosHTML::makeOption( $row->id, $row->username . "\n" );
		} # End for
		$this->_project['owners'] = mosHTML::selectList( $owners, 'ownerid', 'class="inputbox" size="10"', 'value', 'text', $owner);
	
		// Retrieve list of categories, and set the proper values for the
		// select box (multiple items can be selected)
		$this->_project['categories'] = JPackageDirGeneralHelper::getCategoryHTML ( $id, 'categoryselection[]' ); 

		return $this->_project;
	}

	/**
	 * Check in a project entity
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		return JPackageDirGeneralHelper::checkin('Projects');
	}

	/**
	 * Check out a project entity
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout()
	{
		return JPackageDirGeneralHelper::checkout('Projects');
	}

	/**
	 * Method to store the package. All pre-checks will be done here. General
	 * verification methods are taken from the package helper. This method needs
	 * to be used in back- and front-end. In general we need to posted variables
	 * here ($id and $url), the handling of the status is done thru the result
	 * value returned (true/false) and error message stored. In general this is
	 * *the* method to store *any* package into the directory.
	 *
	 * @access	public
	 * @return	array	$values	Array of values to store
	 * @return	boolean True on success
	 * @since	2.0
	 */
	function storeproject()
	{
		global $mainframe;
		$db	=& $this->getDBO();
		$user = & $mainframe->getUser();

		// Determine basic variables 
		$id	= JRequest::getVar( 'id', 0, '', 'int' );
		$ownerid = JRequest::getVar( 'ownerid', 0, '', 'int' );
		$categoryselection = JRequest::getVar( 'categoryselection', 0, '', 'array' );

		// @todo	add check if project is related to at-least one category
		
		// Add or modify record?
		$isNew = ( $id < 1);	
		if ($isNew) { 
			$task = "new";
		} else {
			$task = "edit";
		} # End if

		// Store all fields in row.
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirProjects', $db);
		$row->load( $id );

		// Determine if we have an new record, and set some variables
		if ($isNew) {
			$row->created = $row->created ? mosFormatDate( $row->created, '%Y-%m-%d %H:%M:%S', - $mainframe->getCfg('offset') * 60 * 60 ) : date( "Y-m-d H:i:s" );
			$row->created_by = $ownerid;
			$row->created_by_alias = JPackageDirGeneralHelper::getUser($ownerid);
		} else {
			$row->modified = date( "Y-m-d H:i:s" );
			$row->modified_by = $user->get('id');
			$row->modified_by_alias = $user->get('name');

			// Change ownership when owner has changed
			if ($row->created_by != $ownerid) {
				$row->created_by = $ownerid;
				$row->created_by_alias = JPackageDirGeneralHelper::getUser($ownerid);
			}
		}

		// Bind all POSTed variables and store result
		if (!$row->bind( $_POST )) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->store()) {
			if ($row->getErrorNum() == 1062) {
				$this->setError(JText::_('Project already exists!'));
			} else {
				$this->setError($row->getError());				
			}
			return false;
		}

		// Now process all selected categories, first we delete all records
		// in the relations table, and one by one the posted values will be
		// inserted.
		$query = "DELETE FROM #__jpackagedir_relations"
		. "\nWHERE project_id=$id";
		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError( 500 , $db->stderr());
		} # End if

		foreach ($categoryselection as $cat) {
			$query = "INSERT INTO #__jpackagedir_relations"
			. "\nSET project_id=$id,"
			. "\ncategory_id=$cat";
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseError( 500 , $db->stderr());
			} # End if
		}
		return true;
	}
	
	/**
	 * Method to delete a package (or list of packages). All pre-checks will be
	 * done here. General verification methods are taken from the package
	 * helper. This method needs to be used in back- and front-end. In general
	 * we need to posted variables here ($id or $cid).In general this is
	 * *the* method to delete *any* package from the directory.
	 *
	 * @access	public
	 * @return	boolean True on success, false if not
	 * @since	2.0
	 */
	function deleteproject()
	{
		// Determine proper variable. We handle all packages we want to delete
		// from the $cid[] variable. The bahaviour of this method is that we
		// first check if we only have an id, or if we deal with multiple
		// deletions here.
		$cid = JRequest::getVar( 'id', 0, '', 'array' );
		if (empty($cid)) {
			$cid = JRequest::getVar( 'cid', 0, '', 'array' );
		}

		if (empty($cid)) {
				$this->setError(JText::_('ERR1040'));
				return false;
		}

		$db	=& $this->getDBO();

		// Check if there are packages available for category, throw error when
		// there are registered packaged for this project.	
		$query = "SELECT COUNT(*) FROM jos_jpackagedir_projects as a, jos_jpackagedir_packages as b"
		. "\nWHERE a.name=b.name"
		. "\n AND a.id IN ( ". implode( ',', $cid ) ." )";

		$db->setQuery( $query );
		$total = $db->loadResult();
		if ($total != 0) {
			$this->setError(JText::_('linked to packages!'));
			return false;
		}

		// Delete selected or selections of to delete, and make it so :-)
		$query = "DELETE FROM #__jpackagedir_projects"
		. "\n WHERE id IN ( ". implode( ',', $cid ) ." )";
		$db->setQuery( $query );
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Remove all projects records from relations table (categories the
		// projects belong to)
		$query = "DELETE FROM #__jpackagedir_relations WHERE project_id IN ( ". implode( ',', $cid ) ." )";
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}
}
?>
