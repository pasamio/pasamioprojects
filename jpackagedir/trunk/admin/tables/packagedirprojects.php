<?php
/**
 * @version		$Id: packagedirprojects.php 134 2006-07-28 13:21:16Z schmalls $
 * @package		JPackageDir
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

/**
 * JPackageDirPackages table
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JTablePackageDirProjects extends JTable
{
	var	$id = null;
	var	$name = null;
	var	$description = null;
	var	$website = null;
	var	$email = null;
	var	$licence = null;
	var	$developerurl = null;
	var	$projecturl = null;
	var	$docurl = null;
	var	$faqurl = null;
	var	$supporturl = null;
	var	$tutorialurl = null;
	var	$screenshoturl = null;
	var	$demourl = null;
	var	$published = null;
	var	$created = null;
	var	$created_by = null;
	var	$created_by_alias = null;
	var	$modified = null;
	var	$modified_by = null;
	var	$modified_by_alias = null;
	var	$checked_out = null;
	var	$checked_out_time = null;

	/**
	 * @param database database connector object
	 */
	function __construct( &$db ) {
		parent::__construct( '#__jpackagedir_projects', 'id', $db );
	}
} 
?>
