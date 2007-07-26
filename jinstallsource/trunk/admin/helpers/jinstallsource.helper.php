<?php
/**
 * @version $Id: jpackagedir.helper.php 166 2006-08-12 22:22:34Z willebil $
 * @package Joomla
 * @subpackage Example
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Component Element Helper
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirGeneralHelper extends JApplication
{
	/**
	 * The xml values array
	 *
	 * @var	array
	 * @access protected
	 */
	var $_xmlvalues = Array();

	var $_tree = '';

	/*
	 * Get username from given id
	 * @param	integer	user id
	 * @return	string	the name of the user
	 */
	function getUser ( &$id ) {
		$db	=& $this->getDBO();

		$query = "SELECT name FROM #__users WHERE id=$id";
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row[0]->name;
	}

	/*
	 * Get project information for given name
	 * @param	string	name of project we want the information to be retrieved
	 * @return	object	a table object containing values or null when not found
	 */
	function getProjectInfo ( &$name )
	{
		$db	=& $this->getDBO();

		$query = "SELECT * FROM #__jpackagedir_projects WHERE name LIKE '" . $name . "'";
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row[0];
	}

	/*
	 * Retrieve the package id for given key, returns false if not present.
	 * @param	string	package type (component/module/plugin/template/language)
	 * @param	string	name of the package
	 * @param	string	version we want the package id to be determined
	 * @return	double	key value of package is
	 * @return	boolean	false when package version not found
	 */
	function getPackageID( &$type, &$name, &$version )
	{
		$db	=& $this->getDBO();

		$query = "SELECT id FROM #__jpackagedir_packages"
		. "\nWHERE type='$type'"
		. "\n  AND name='$name'"
		. "\n  AND version='$version'";
		$db->setQuery($query);
		$id = $db->loadResult();

		if (empty($id)) {
			return false;
		} else {
			return $id;
		}
	}

	/*
	 * Retrieve the package version for given id, returns false if not present.
	 * @param	double	the unique ket value for package
	 * @return	string	package version
	 * @return	boolean	false when package not found
	 */
	function getPackageVersion( $id )
	{
		$db	=& $this->getDBO();

		$query = "SELECT version FROM #__jpackagedir_packages"
		. "\nWHERE id=$id";
		$db->setQuery($query);
		$version = $db->loadResult();

		if (empty($version)) {
			return false;
		} else {
			return $version;
		}
	}

	/*
	 * Determine size of given itemin Kb, Mb of Gb
	 *
	 * @param	long    Size in bytes we want to calculate values for
	 * @return	string  Return value
	 */
	function getSize ($size)
	{
		if($size >= 1073741824) {
			$size = round($size / 1073741824 * 100) / 100 . " Gb";
		} elseif($size >= 1048576) {
			$size = round($size / 1048576 * 100) / 100 . " Mb";
		} elseif($size >= 1024) {
			$size = round($size / 1024 * 100) / 100 . " Kb";
		} else $size = $size . " Bytes";
		if($size==0) $size="-";

		return $size;
	} 

	/*
	 * Return an array with xml values read from installer package.
	 * @return	array	array with xml values read from installer package
	 */
	function getXMLValues()
	{
		return $this->_xmlvalues;
	}

	/*
	 * This function receives a number of key values for packages that needs to be
	 * deleted. Just check if the package may be deleted because another package can
	 * depend on it. Returns true when selection may be deleted, false when not.
	 * @param	array	array with key values of packages that need to be checked
	 * @return	boolean	false if entries may not be deleted
	 *                  true when entries may be deleted
	 * @todo	just take a closer look, very ugly there is a query in a for
	 *          loop, just works but optimization using one query needs to be
	 *          possible.
	 */
	function prePackageDeleteCheck ( $cid )
	{
		$db	=& $this->getDBO();

		for ($i=0; $i<count($cid); $i++) {
			$query = "SELECT id FROM #__jpackagedir_dependencies"
			. "\nWHERE dependid='" . $cid[$i] . "'";
			$db->setQuery( $query );
			$id = $db->loadResult();
			if (!empty($id)) {
				return false;
			}
		}
		return true;
	}

	/*
	 * Check if package can be added or modified into the directory. For this we
	 * try to determine if a dependency exists, if so it cannot be removed.
	 * @return	boolean	false is package may not be deleted/added
	 *                  true when package may be deleted/added
	 */
	function prePackageCheck ()
	{
		$array = $this->_xmlvalues['dependencies'];
		for ($i=0; $i< count($array); $i++) {
			if (!$array[$i][3]) {
				return false;
			} # End if
		} # End for
		return true;
	}
	/**
	* Return an array holding all package information from given url.
	* Returns false if package could not be downloaded, or unpacked.
	* @param	string	direct url of package we want to determine the
	*                   information of.
	* @return	boolean false when something is wron with package
	*                   true when all info is retrieved
	* @todo raise warning when error situation occurs
	*/
	function retrievePackageInformation ( &$url )
	{
		$db	=& $this->getDBO();

		// First we need to download the package, for this we use the 
		// downloadPackage method that comes with the installer class. Return
		// error if file cannot be read.
		if (!$download = JInstallerHelper::downloadPackage($url)) {
			// @todo	raise warning "Package cannot be downloaded""
			return false;
		}

		// Ok, we have downloaded the package, we first gonna check some values
		// here, and if they are ok, we just store this record. Whatch and see
		// how this is done...
		$file = JPATH_SITE.DS.'tmp'.DS.$download;
		$package = JInstallerHelper::unpack($file);

		$this->_xmlvalues = Array();
		$this->_xmlvalues['type'] = $package['type'];

		// Start reading the package xml file, and try to find out the values
		// we seek.
		$installer = & JInstaller::getInstance($db, $package['type']);
		if (!$installer->preInstallCheck($package['extractdir'], $package['type'])) {
			return false;
		}
		$installfile = $installer->_installFile . "<br>";

		$xmlDoc = &JFactory::getXMLParser();
		$xmlDoc->resolveErrors( true );

		if (@!$xmlDoc->loadXML( $installer->_installFile, false, true )) {
			// @todo	raise warning "Package downloaded, but cannot be processed (error in xml file?)"
			return false;
		}

		if (!$xmlDoc->documentElement->hasChildNodes()) {
			// @todo	raise warning "Doctype has no child nodes"
			return false;
		}
		$this->_xmlvalues['checksum'] = md5_file ($file);
		$this->_xmlvalues['filesize'] = filesize ($file);

		$childnodes =&$xmlDoc->documentElement->childNodes;

		// Pre-check look ok, now walk thru the nodelist. The master 
		// map-pack xml file is quite simple, so no recusion of
		// node walk-thru is used, pretty straight forward...
		$j=1;
		for ($i = 0; $i < $xmlDoc->documentElement->childCount; $i++) {
			$node = $childnodes[$i];
			$nodename = strtolower ($node->nodeName);
			switch ($nodename) {
				case "name":
				case "description":
				case "directory":
				case "version":
					$this->_xmlvalues[$nodename] = $node->getText();
					break;
	
				case "dependencies":
					// Okidoki, we now need to walk the subnodes for
					// this dependency values.
					$subnode = $xmlDoc->getElementsByPath( $nodename, $j );
					if ($subnode->hasChildNodes()) {
						$values = $subnode->childNodes;
						foreach ($values as $value) {
							$type = $value->getAttribute("type");
							$name = $value->getAttribute("name");
							$version = $value->getAttribute("version");
							$packageid = $this->getPackageID($type, $name, $version);
							$this->_xmlvalues['dependencies'][] = Array ($type, $name, $version, $packageid);
						}
					}
					$j++;
					break;
			}
		} 

		JInstallerHelper::cleanupInstall($file, $package['extractdir']);
		unset ($xmlDoc);
		return true;
	}
	/*
	 * Function replacing the mosCommonHTML::PublishedProcessing adding a suffix
	 * option to the task refering to. 
	 *
	 * @param	object		row object
	 * @param	interger	the index we want to refer to
	 * @param	string		image to show (yes value)
	 * @param	string		image to show (no value)
	 * @param	string		task suffix
	 * @return	boolean		html tag for image, linkable
	 * @todo 	convert this addition to the 1.5 framework?  
	 */
	function PublishedProcessing( &$row, $i, $imgY='tick.png', $imgX='publish_x.png', $suffix = '' )
	{
		$img = $row->published ? $imgY : $imgX;
		$task = $row->published ? 'unpublish' : 'publish';
		$task .= $suffix;
		$alt = $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
		$action = $row->published ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	/*
	 * Return a full HTML reference to an package image. 
	 * @param	string	type of package component|module|language|template|plugin
	 * @param	string	url to refer to
	 */
	function imgPackageType ( $type, $url)
	{
		$img = 'ext_' . $type . ".png";
		$href = "<a href=\"$url\"><img src=\"/administrator/components/com_jpackagedir/images/" . $img . "\" border=\"0\" /></a>";
		return $href;	
	}

	/**
	 * Check out an entity
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout( $table )
	{
		// Skip new entries for checkout
		$id = JRequest::getVar( 'id', 0, '', 'int' );
		if ( $id == 0) {
			return true;
		}

		// Get table object and checkout
		global $mainframe;
		$db	= & $mainframe->getDBO();
		$user = & $mainframe->getUser();

		// Create and load the project row
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDir' . $table, $db);

		$row->load($id);
		$row->checkout($user->_id);

		return true;
	}

	/**
	 * Check in an entity
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin( $table )
	{
		// Skip new entries for checkout
		$id = JRequest::getVar( 'id', 0, '', 'int' );
		if ( $id == 0) {
			return true;
		}
 
		// Get table object and checkin
		global $mainframe;
		$db	= & $mainframe->getDBO();

		// Create and load the project row
		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDir' . $table, $db);

		$row->load($id);
		$row->checkin();
		return true;
	}

	/**
	 * Function that returns a full sorted array holding all categories. This is
	 * a recursive function.
	 * 
	 * @param	integer		parent id key to process
	 * @param	array		contains the result
	 * @param	integer		level
	 * @return	array		complete array holding all values
	 * @todo	deprecated, remove after finalisation
	 */
	function getCategoriesArray( $parentid, &$array, $level=0 )
	{
		global $mainframe;
		$db	=& $mainframe->getDBO();

		// Just one level up (node depth)
		$level++;

		// Query all categories for given parent within node
		$query = "SELECT id, parent_id, name"
		. "\nFROM #__jpackagedir_categories"
		. "\nWHERE parent_id = $parentid"
		. "\nORDER BY 3";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		// Just store the number of sublevels in the array, use pop and push
		// to not disturb the array flow here...(yes, this was a headbreaker)
		if (count($array) > 0) {
			$a1 = array_pop ($array);
			$a1[4] = count($rows);
			array_push ($array, $a1);
		}

		// If we have returned records, this means we have child nodes for the
		// category, and then we need to process them.
		foreach ($rows as $row) {
			$array[] = Array ($level, $row->id, $row->parent_id, $row->name, 0);
			JPackageDirGeneralHelper::getcategoriesarray( $row->id, $array, $level );
		}

		return $array;
	}

	/**
	 * Function to retrieve the complete HTML tags for a category for a selected
	 * project.
	 * @param	integer		project id
	 * @param	string		name of select tag
	 * @param	boolean		multiple selections allowed true/false
	 * @return	string		full html for select tag
	 * @todo	deprecated, remove after finalisation 
	 */
	function getCategoryHTML( $projectid=0, $name='category', $multiple=true, $javascript='', $default=0 )
	{
		global $mainframe;
		$db	=& $mainframe->getDBO();

		// First retrieve all category information
		$array = Array();
		$cats = JPackageDirGeneralHelper::getcategoriesarray( 0, $array );

		if ( $projectid == 0 ) {
			array_unshift ($cats, Array (1, 0, 0, JText::_('Select Category', 0)));
		}

		// Determine the categories assigned to the selected project if project
		// id is given, else we suppose to have a new project selected.
		$array = Array();
		if ($projectid !=0 ) {
			$query = "SELECT category_id FROM #__jpackagedir_relations"
			. "\nWHERE project_id=$projectid";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// Just process the returned values into the array. 
			foreach ($rows as $row) {
				$array[] = $row->category_id;
			}
		}

		$html =  "<select name=\"$name\" ";
		if ($multiple) $html .= "multiple";
		$html .= " $javascript>\n";

		// We now all ingredients, just let compile the HTML
		$level = NULL;
		foreach ($cats as $cat) {
			// Closing tag?
			if ($level != NULL && $cat[0] != $level && $cat[0] == 1) {
				$html .= "</optgroup>\n";
			}

			if ($cat[0] > 1 || $cat[4] == 0) {
				$html .= "<option label=\"" . $cat[1] . "\" value=\"" . $cat[1] . "\"";
				if (in_array($cat[1], $array) || $cat[1] == $default) {
					$html .= "selected=\"selected\"";
				} 
				$html .= ">" . $cat[3] . "</option>\n";
			} else {
				$html .= "<optgroup label=\"" . $cat[3] . "\">\n";
			}
			
			$level = $cat[0];
		}
		
		$html .= "</select>\n";
		return $html;
	}

	/**
	 * Function that returns a full sorted array holding all categories. This is
	 * a recursive function.
	 * 
	 * @param	integer		parent id key to process
	 * @param	array		contains the result
	 * @param	integer		level
	 * @return	array		complete array holding all values
	 * @todo	phpdocumenter tags avove.... and comment
	 */
	function getCategoryJSTree( $parentid, &$array, $level=0 )
	{
		global $mainframe;
		$db	=& $mainframe->getDBO();

		// Just one level up (node depth)
		$level++;

		// Query all categories for given parent within node
		$query = "SELECT id, parent_id, name"
		. "\nFROM #__jpackagedir_categories"
		. "\nWHERE parent_id = $parentid"
		. "\nORDER BY 3";

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		// Just store the number of sublevels in the array, use pop and push
		// to not disturb the array flow here...(yes, this was a headbreaker)
		if (count($array) > 0) {
			$a1 = array_pop ($array);
			$a1[4] = count($rows);
			array_push ($array, $a1);
		}

		// If we have returned records, this means we have child nodes for the
		// category, and then we need to process them.
		if (count($rows) > 0 && $level > 1) {
			$this->_tree .= ",\n";
		}
		
		foreach ($rows as $row) {
			$icon = 'null';
			$title = $row->name;
			$target = 'null';
			$url = 'null';
			$description = 'null';

			$this->_tree .= str_pad('', $level, "\t", STR_PAD_LEFT) . "[$icon, '$title', $target, $url, $description";
			$array[] = Array ($level, $row->id, $row->parent_id, $row->name, 0);
			JPackageDirGeneralHelper::getCategoryJSTree( $row->id, $array, $level );
			$this->_tree .= str_pad('', $level, "\t", STR_PAD_LEFT) . "],\n";
		}

		// Return the tree if we are at end of the node walk-thru
		if ($level == 1) {
			return $this->_tree;
		} 

		// Or just keep on recursing :-)
		return $array;
	}
}
?>