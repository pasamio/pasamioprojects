<?php
/**
 * @version		$Id: admin.jpackagedir.php 166 2006-08-12 22:22:34Z willebil $
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
define( 'JPATH_COM_JPACKAGEDIR', dirname( __FILE__ ) );

/**
 * Require the com_content helper library. 
 */
require_once (JPATH_COM_JPACKAGEDIR.'/controller.php');
require_once (JPATH_COM_JPACKAGEDIR.DS.'helpers'.DS.'jpackagedir.helper.php');

/**
 * Component Helper 
 */
jimport('joomla.application.extension.component');

/**
 * Create the controller 
 */
$controller = & new JPackageDirController( $mainframe, 'display' );

/**
 * Set the default model/view paths
 */
$controller->setViewPath( JPATH_COM_JPACKAGEDIR.DS.'views' );
$controller->setModelPath( JPATH_COM_JPACKAGEDIR.DS.'models' );

/**
 * Register all possible tasks.
 */
$controller->registerTask( '',					'showdirectory' );
$controller->registerTask( 'showdirectory',		'showdirectory' );
$controller->registerTask( 'cancel',			'canceldirectory' );
$controller->registerTask( 'cancelproject',		'cancelproject' );
$controller->registerTask( 'edit', 				'editdirectory' );
$controller->registerTask( 'new', 				'editdirectory' );
$controller->registerTask( 'save', 				'savedirectory' );
$controller->registerTask( 'remove', 			'deletedirectory' );
$controller->registerTask( 'projects', 			'showprojects' );
$controller->registerTask( 'editproject',		'editproject' );
$controller->registerTask( 'newproject',		'editproject' );
$controller->registerTask( 'deleteproject',		'deleteproject' );
$controller->registerTask( 'saveproject',		'saveproject' );
$controller->registerTask( 'publish',			'publish' );
$controller->registerTask( 'unpublish',			'unpublish' );
$controller->registerTask( 'publishproject',	'publishproject' );
$controller->registerTask( 'unpublishproject',	'unpublishproject' );
$controller->registerTask( 'categories',		'showcategories' );

// @todo	remove after test
$controller->registerTask( 'jext', 			'convert' );

/**
 * Perform the Request task and redirect if set by the controller.
 */
$controller->execute( $task );
$controller->redirect();
?>
