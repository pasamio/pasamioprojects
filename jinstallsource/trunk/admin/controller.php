<?php
/**
 * @version 	$Id: controller.php 162 2006-08-11 23:45:36Z willebil $
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

// No direct access and basic intialisation.
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

/**
* J!Package Directory component controller.
*
* @author		Wilco Jansen
* @package 		Joomla
* @subpackage	J!Package Directory
* @since 		1.5
*/
class JPackageDirController extends JController
{
	/**
	* Method to render an overview of all selected directory entries, supports
	* paging etc. This is the "access" page to the directory.
	* 
	* @access	public
	* @since	1.5
	*/
	function showdirectory()
	{
		// Create the view
		$this->setViewName( 'List', 'com_jpackagedir', 'JPackageDirView' );
		$view = & $this->getView();

		// Get/Create the model
		$model = & $this->getModel('List', 'JPackageDirModel');

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Display the view
		$view->display();
	}

	/**
	* Method to render an overview of all selected projectentries, supports
	* paging etc. This is the "access" page to the projects.
	* 
	* @access	public
	* @since	1.5
	*/
	function showprojects()
	{
		// Create the view
		$this->setViewName( 'ListProject', 'com_jpackagedir', 'JPackageDirView' );
		$view = & $this->getView();

		// Get/Create the model
		$model = & $this->getModel('ListProject', 'JPackageDirModel');

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Display the view
		$view->display();
	}

	/**
	* Method to render an overview of all selected categories, supports
	* paging etc. This is the "access" page to the categories.
	* 
	* @access	public
	* @since	1.5
	*/
	function showcategories()
	{
		// Create the view
		$this->setViewName( 'ListCategory', 'com_jpackagedir', 'JPackageDirView' );
		$view = & $this->getView();

		// Get/Create the model
		$model = & $this->getModel('ListCategory', 'JPackageDirModel');

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Display the view
		$view->display();
	}

	/**
	* Edits an Element
	*
	* @access	public
	* @since	1.5
	*/
	function editdirectory()
	{
		// Create the view
		$this->setViewName( 'Package', 'com_jpackagedir', 'JPackageDirView' );
		$view = & $this->getView();

		// Get/Create the model
		$model = & $this->getModel('Package', 'JPackageDirModel');

		// Make sure the model is checked out so that we don't have concurrency
		// issues
		$model->checkout();

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Display the edit screen for the view
		$view->editdirectory();
	}

	/**
	* Saves the entity from edit form submit for given directory entry
	*
	* @access	public
	* @since	1.5
	*/
	function savedirectory () {
		// Get/Create the model
		$model = & $this->getModel('Package', 'JPackageDirModel');

		// Let us not forget to check this record in...
		$model->checkin();

		// Error handling, throw warning when we encountered an error
		if ($model->storedirectory()) {
			$msg = JText::_( 'Directory Entry Saved' );
		} else {
			$msg = JText::_( 'Error Saving Directory Entry - ' . $model->getError() );
		}

		$option = JRequest::getVar('option', '', '', 'string');
		$version = JRequest::getVar('version', '', '', 'string');
		$type = JRequest::getVar('type', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=showdirectory&version=$version&type=&type&name=$name" , $msg );
	}
	/**
	* Delete the package(s)
	*
	* @access	public
	* @since	1.5
	*/
	function deletedirectory () {
		// Get/Create the model
		$model = & $this->getModel('Package', 'JPackageDirModel');

		if ($model->deletedirectory()) {
			$msg = JText::_( 'Directory Entry Deleted' );
		} else {
			$msg = JText::_( 'Error Deleting Directory Entry - ' . $model->getError() );
		}

		$option = JRequest::getVar('option', '', '', 'string');
		$version = JRequest::getVar('version', '', '', 'string');
		$type = JRequest::getVar('type', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=showdirectory&version=$version&type=&type&name=$name" , $msg );
	}

	/**
	* Cancels an edit JPackage! directory operation
	*
	* @access	public
	* @since	1.5
	*/
	function canceldirectory()
	{
		// Get/Create the model
		$model = & $this->getModel('Package', 'JPackageDirModel');

		// Check the entity back in and finish
		$model->checkin();
		
		$option = JRequest::getVar('option', '', '', 'string');
		$version = JRequest::getVar('version', '', '', 'string');
		$type = JRequest::getVar('type', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=showdirectory&version=$version&type=&type&name=$name" , '' );
	}


	/**
	* Cancels an edit project operation
	*
	* @access	public
	* @since	1.5
	*/
	function cancelproject()
	{
		// Get/Create the model
		$model = & $this->getModel('Project', 'JPackageDirModel');

		// Check the entity back in and finish
		$model->checkin();
		
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$sname = JRequest::getVar('sname', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=projects&project=$project&category=$category&owner=$owner&sname=$sname" , '' );
	}
	
	/**
	 * Edit an project entry (new or existing)
	 * 
	 * @access	public
	 * @since	1.5
	 */
	function editproject()
	{
		// Create the view
		$this->setViewName( 'Project', 'com_jpackagedir', 'JPackageDirView' );
		$view = & $this->getView();

		// Get/Create the model
		$model = & $this->getModel('Project', 'JPackageDirModel');

		// Make sure the model is checked out so that we don't have concurrency
		// issues
		$model->checkout();

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Display the edit screen for the view
		$view->editproject();
	} 

	/**
	 * Delete one or more project entries
	 * 
	 * @access	public
	 * @since	1.5
	 * @todo	all logic, removal is dummy atm
	 * @todo	check this method on proper redirection, missing owner info for sure
	 */
	function deleteproject()
	{
		// Get/Create the model
		$model = & $this->getModel('Project', 'JPackageDirModel');

		if ($model->deleteproject()) {
			$msg = JText::_( 'Project Deleted' );
		} else {
			$msg = JText::_( 'Error Deleting Project(s) - ' . $model->getError() );
		}

		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$sname = JRequest::getVar('sname', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=projects&project=$project&category=$category&owner=$owner&sname=$sname" , $msg );
	}	

	/**
	 * Save all project data that is submitted
	 * 
	 * @access	public
	 * @since	1.5
	 * @todo	check this method on proper redirection, missing owner info for sure
	 */
	function saveproject()
	{
		// Get/Create the model
		$model = & $this->getModel('Project', 'JPackageDirModel');

		// Let us not forget to check this record in...
		$model->checkin();

		// Error handling, throw warning when we encountered an error
		if ($model->storeproject()) {
			$msg = JText::_( 'Project Entry Saved' );
		} else {
			$msg = JText::_( 'Error Saving Project Entry - ' . $model->getError() );
		}

		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=projects&project=$project&name=$name&owner=$owner&category=$category" , $msg );
	}
	/**
	* Publish the package selected.
	*/
	function publish()
	{
		// Get some variables from the request	
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// And perform the action thru the model method setState
		$model = & $this->getModel('List', 'JPackageDirModel');
		if ($model->setState($cid, 1)) {
			$msg = sprintf( JText::_( 'Package Published' ), count( $cid ) );
		} else {
			$msg = $model->getError();
		}

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=showdirectory&project=$project&name=$name&owner=$owner&category=$category" , $msg );
	}

	/**
	* Unpublish the package selected
	*/
	function unpublish()
	{
		// Get some variables from the request	
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// And perform the action thru the model method setState
		$model = & $this->getModel('List', 'JPackageDirModel');
		if ($model->setState($cid, 0)) {
			$msg = sprintf( JText::_( 'Package Un-Published' ), count( $cid ) );
		} else {
			$msg = $model->getError();
		}

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=showdirectory&project=$project&name=$name&owner=$owner&category=$category" , $msg );
	}

	/**
	* Publish the projects selected.
	*/
	function publishproject()
	{
		// Get some variables from the request	
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// And perform the action thru the model method setState
		$model = & $this->getModel('ListProject', 'JPackageDirModel');
		if ($model->setState($cid, 1)) {
			$msg = sprintf( JText::_( 'Project Published' ), count( $cid ) );
		} else {
			$msg = $model->getError();
		}

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=projects&project=$project&name=$name&owner=$owner&category=$category" , $msg );
	}

	/**
	* Unpublish the projects selected
	*/
	function unpublishproject()
	{
		// Get some variables from the request	
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getVar('option', '', '', 'string');
		$project = JRequest::getVar('project', '', '', 'string');
		$category = JRequest::getVar('category', '', '', 'string');
		$owner = JRequest::getVar('owner', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		// And perform the action thru the model method setState
		$model = & $this->getModel('ListProject', 'JPackageDirModel');
		if ($model->setState($cid, 0)) {
			$msg = sprintf( JText::_( 'Project Un-Published' ), count( $cid ) );
		} else {
			$msg = $model->getError();
		}

		// Jump to proper page
		$this->setRedirect( "index.php?option=$option&task=projects&project=$project&name=$name&owner=$owner&category=$category" , $msg );
	}

	/*
	 * @todo: remove this when ready
	 */
	function convert()
	{
		global $mainframe;
		$db	=& $mainframe->getDBO();

		// Ttruncate all tables
		$query = "TRUNCATE TABLE #__jpackagedir_projects";
		$db->setQuery($query);
		if (!$db->query()) {
			die($db->getErrorMsg());
		}

		$query = "TRUNCATE TABLE #__jpackagedir_categories";
		$db->setQuery($query);
		if (!$db->query()) {
			die($db->getErrorMsg());
		}

		$query = "TRUNCATE TABLE #__jpackagedir_relations";
		$db->setQuery($query);
		if (!$db->query()) {
			die($db->getErrorMsg());
		}

		// Retrieve project information
		$query = "SELECT a.link_id, a.link_name, a.link_desc, b.id, b.username, link_published, a.email, a.link_created "
		       . "FROM #__mt_links AS a, #__users AS b "
		       . "WHERE a.user_id = b.id";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$query = "INSERT INTO #__jpackagedir_projects SET "
			              . "id=" . $row->link_id . ", "
			              . "name='" . htmlspecialchars ($row->link_name, ENT_QUOTES) . "', "
			              . "description='" . htmlspecialchars ($row->link_desc, ENT_QUOTES ) . "', "
			              . "email='" . $row->email . "', "
			              . "published='" . $row->link_published . "', "
			              . "created='" . $row->link_created . "', "
			              . "created_by='" . $row->id . "', "
			              . "created_by_alias='" . $row->username  . "'";
			$db->setQuery($query);
			if (!$db->query()) {
				die($db->getErrorMsg());
			}
		}
		
		echo "Converted " . count ($rows) . " project record<br>";
		
		// Retrieve all categories
		$query = "SELECT cat_id, cat_name, cat_desc, cat_parent, cat_published, cat_created FROM jos_mt_cats";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$query = "INSERT INTO #__jpackagedir_categories SET "
			              . "id=" . $row->cat_id . ", "
			              . "parent_id=" . $row->cat_parent . ", "
			              . "name='" . $row->cat_name .  "', "
			              . "description='" . $row->cat_desc . "', "
			              . "published='" . $row->cat_published . "', "
			              . "created='" . $row->cat_created . "'";
			$db->setQuery($query);
			if (!$db->query()) {
				die($db->getErrorMsg());
			}
		}

		echo "Converted " . count ($rows) . " categories<br>";

		// Retrieve all relations
		$query = "SELECT link_id, cat_id FROM jos_mt_cl";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$query = "INSERT INTO #__jpackagedir_relations SET "
			              . "category_id=" . $row->cat_id . ", "
			              . "project_id=" . $row->link_id;
			$db->setQuery($query);
			if (!$db->query()) {
				die($db->getErrorMsg());
			}
		}

		echo "Converted " . count ($rows) . " dependencies<br>";
	}
}
?>