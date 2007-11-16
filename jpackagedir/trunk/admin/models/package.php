<?php
/**
 * @version 	$Id: package.php 137 2006-07-30 15:44:37Z willebil $
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
 * Model Element : individual directory package model handler
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package
 * @since		2.0
 */
class JPackageDirModelPackage extends JModel
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
	var $_package = null;

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
		$this->_package	= null;
	}

	/**
	 * Method to get field information for given package
	 *
	 * @since 2.0
	 */
	function getPackage()
	{
		global $mainframe;
		$db	=& $this->getDBO();
		$user = & $mainframe->getUser();

		// Determine basic variables 
		$id	= JRequest::getVar( 'id', 0, '', 'int' );
		$option = JRequest::getVar('option', '', '', 'string');
		$version = JRequest::getVar('version', '', '', 'string');
		$type = JRequest::getVar('type', '', '', 'string');
		$name = JRequest::getVar('name', '', '', 'string');

		$this->_package['option'] = $option; 
		$this->_package['version'] = $version;
		$this->_package['stype'] = $type;
		$this->_package['sname'] = $name;

		$nullDate = $db->getNullDate();

		// Create and load the content table row
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirPackages', $db);
		$row->load($id);

		// Now we just modify some vars for nice and neath rendering (only with
		// existing records).
		if ($row->id > 0) {
			// Fail if checked out not by current user, if we pass the check, just 
			// flip the scheckoutflag.
			if ($row->checked_out && $row->checked_out <> $user->_id) {
				josRedirect( "index2.php?option=$option&task=showdirectory&version=$version&type=$type&name=$name",  JText::_('Record is currently edited by someone else'));
			} # End if

			$row->created = mosFormatDate( $row->created, '%Y-%m-%d %H:%M:%S' );
			$row->modified = mosFormatDate( $row->modified, '%Y-%m-%d %H:%M:%S' );

			// Retrieve project information
			$this->_package['prow'] = JPackageDirGeneralHelper::getProjectInfo ( $row->name );

			// Determine the dependencies that are valid for this package.
			#@todo	move to helper logic
			$query = "SELECT b.type as type, b.name as name, b.version as version"
			. "\nFROM #__jpackagedir_dependencies as a, #__jpackagedir_packages as b"
			. "\nWHERE a.dependid=b.id "
			. "\nAND a.packageid=" . $row->id;
			$db->setQuery($query);
			$this->_package['drows'] = $db->loadObjectList();

			// Determine the version dependencies.
			#@todo	move to helper logic
			$query = "SELECT a.dependid AS dependid, a.packageid AS packageid"
			. "\nFROM #__jpackagedir_dependencies as a, #__jpackagedir_packages as b"
			. "\nWHERE a.dependid=b.id"
			. "\nAND b.type='" . $row->type . "'"
			. "\nAND b.name='" . $row->name . "'"
			. "\nAND a.packageid <= " . $row->id
			. "\nORDER BY 1, 2";
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			$array = Array();
			for ($i=0; $i<count($rows);$i++) {
				$array[] = Array(JPackageDirGeneralHelper::getPackageVersion($rows[$i]->dependid), JPackageDirGeneralHelper::getPackageVersion($rows[$i]->packageid));
			}
			$this->_package['vdepend'] = $array;
		}

		// Define a checkbox for the hashtypes supported. Currently only md5 is
		// available.
		$hashtypes[] = mosHTML::makeOption('md5','md5');
		#$hashtypes[] = mosHTML::makeOption('crc32' ,'crc32');
		#$hashtypes[] = mosHTML::makeOption('sha1' ,'sha1');
		$this->_package['hashtype'] = mosHTML::selectList( $hashtypes, 'hashtype', 'class="inputbox" size="1"', 'value', 'text', $row->hashtype);

		$this->_package['row'] = $row;
		return $this->_package;
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
		return JPackageDirGeneralHelper::checkin('Packages');
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
		return JPackageDirGeneralHelper::checkout('Packages');
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
	function storedirectory()
	{
		global $mainframe;
		$db	=& $this->getDBO();
		$user = & $mainframe->getUser();

		// Determine basic variables 
		$id	= JRequest::getVar( 'id', 0, '', 'int' );
		$url = JRequest::getVar('url', '', 'string');

		jimport('joomla.installer.installer');

		// Try to read the url provided.
		$package = new JPackageDirGeneralHelper();
		if (!$package->retrievePackageInformation( $url )) {
			$this->setError(JText::_('ERR1000'));
			return false;
		} # End if
		$xmlvalues = $package->getXMLValues();

		// Add or modify record?
		$isNew = ( $id < 1);	
		if ($isNew) { 
			$task = "new";
		} else {
			$task = "edit";
		} # End if

		// Just check if we try to add a new package that already exists.
		if ($isNew && $package->getPackageID($xmlvalues['type'], $xmlvalues['name'], $xmlvalues['version'])) {
			$this->setError(JText::_('ERR1010'));
			return false;
		}

		// Dependency check.
		if (!$package->prePackageCheck ($xmlvalues)) {
			$this->setError(JText::_('ERR1020'));
			return false;
		}

		// Pre-checks have been done, let us proceed with the actual store, fill
		// all table fields.
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirPackages', $db);
		$row->load( $id );

		// The package name, version and type may not have been changed when
		// we have an existing package, if so we throw an error
		if (!$isNew) {
			if ($row->type != strtolower($xmlvalues['type']) || $row->name != strtolower($xmlvalues['name'])|| $row->version != $xmlvalues['version']) {
				$this->setError(JText::_('ERR1030'));
				return false;
			}
		}

		// Determine if we have an new record, and set some variables
		if ($isNew) {
			$row->created = $row->created ? mosFormatDate( $row->created, '%Y-%m-%d %H:%M:%S', - $mainframe->getCfg('offset') * 60 * 60 ) : date( "Y-m-d H:i:s" );
			$row->created_by = $user->get('id');
			$row->created_by_alias = $user->get('name');
		} else {
			$row->modified = date( "Y-m-d H:i:s" );
			$row->modified_by = $user->get('id');
			$row->modified_by_alias = $user->get('name');
		}

		// Do not use the bind method here, we obtain our information in another
		// manner then the $_POST variables!
		$row->type = strtolower ($xmlvalues['type']);
		$row->name = strtolower ($xmlvalues['name']);
		$row->description = $xmlvalues['description'];
		$row->directory = $xmlvalues['directory'];
		$row->version = $xmlvalues['version'];
		$row->checksum = $xmlvalues['checksum'];
		$row->filesize = $xmlvalues['filesize'];
		$row->url = $url;

		// Perform additional check (if defined in database class, is here for 
		// later use).
 		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}

		// Store record information
		if (!$row->store()) {
			$this->setError($row->getError());
			return false;
		}

		// Now we are gonna store the dependencies here. When we have a new
		// package we only insert the dependency records, else we first
		// delete *all* entries matching the package id we have here...
		if (!$isNew) { 
			$query = "DELETE FROM #__jpackagedir_dependencies WHERE packageid=" . $row->id;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		$array = $xmlvalues['dependencies'];
		for ($i=0; $i<count($array);$i++) {
			$id = $package->getPackageID($array[$i][0], $array[$i][1], $array[$i][2]);
			if ($id) {
				$query = "INSERT INTO #__jpackagedir_dependencies SET packageid=" . $row->id . ", dependid=$id";
				$db->setQuery($query);
				if (!$db->query()) {
					$this->setError($db->getErrorMsg());
					return false;
				}		
			}
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
	function deletedirectory()
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

		// Just verify if one of the selected packages is used in a dependency.
		$package = new JPackageDirGeneralHelper();
		if (!$package->prePackageDeleteCheck ( $cid )) {
			$this->setError(JText::_('ERR1050'));
			return false;
		}

		// Prepare query to delete records and make it so
		$query = "DELETE FROM #__jpackagedir_packages"
		. "\n WHERE id IN ( ". implode( ',', $cid ) ." )";
		$db->setQuery( $query );
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// And also remove all dependencies for this package.
		$query = "DELETE FROM #__jpackagedir_dependencies WHERE packageid IN ( ". implode( ',', $cid ) ." )";
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}
}
?>
