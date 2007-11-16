<?php
/**
 * @version 	$Id: listproject.php 148 2006-08-08 21:45:44Z willebil $
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

/**
 * Model ListProject : overview of directory projects
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package
 * @since		1.5
 */
class JPackageDirModelListCategory extends JModel
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
	function getListCategory()
	{
		global $mainframe;
		$db	=& $this->getDBO();

		// Prepare the page by inserting the Javascript and style info needed 
		// to render here the left panel category/group selection option.
		$document =& $mainframe->getDocument();
		$document->addScript('/administrator/components/com_jpackagedir/assets/JSCookTree.js');
		$document->addStyleSheet('/administrator/components/com_jpackagedir/assets/ThemeXP/theme.css');
		$document->addScript('/administrator/components/com_jpackagedir/assets/ThemeXP/theme.js');

		// Retrieve all catagory information and build the javascript tree.  
		$array = Array();
		$this->_list['cattree'] = "\n<script language=\"javascript\">\n";
		$this->_list['cattree'] .= "var CategoryTree  = \n[\n";
		$this->_list['cattree'] .= "[null, '" . JText::_('Top Level') . "', '/administrator/index.php?option=com_jpackagedir&task=categories', null, null,\n"; 
		$this->_list['cattree'] .= JPackageDirGeneralHelper::getCategoryJSTree( 0, $array );
		$this->_list['cattree'] .= "]\n"; 
		$this->_list['cattree'] .= "]\n</script>\n";

		// Retrieve all records for selected category

		return $this->_list;
	}
	/**
	* Set the state of selected category item
	* @param	array	list of id's we want to set the state
	* @param	integer	value to set
	* @return	true/false for success/error
	*/
	function setState( $items, $state )
	{
		global $mainframe;
		$db	=& $this->getDBO();

		JTable::addTableDir(JPATH_COM_JPACKAGEDIR);
		$row = & JTable::getInstance('PackageDirCatagories', $db);

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
