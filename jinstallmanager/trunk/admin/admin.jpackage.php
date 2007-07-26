<?php
/***
* @version $Id: admin.extensionsdir.php 2006-05-27 11:40:00 WilcoJansen$
* @package Joomla
* @subpackage JPackage
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
// define path
define( 'JPATH_COM_JPACKAGE', dirname( __FILE__ ) );

// require the controller file
require_once( JPATH_COM_JPACKAGE . DS . 'controller.php' );

// load language
//$mainframe->getLanguage()->load( 'com_jpackage', JPATH_ADMINISTRATOR );

// import
jimport( 'joomla.application.extension.component' );
// initialize controller
$controller = new JPackageController( $mainframe, 'showDefault' );
// set paths
$controller->setViewPath( JPATH_COM_JPACKAGE . DS . 'views' );
$controller->setModelPath( JPATH_COM_JPACKAGE . DS . 'models' );
// register tasks
$controller->registerTask( 'core', 'showCore' );
$controller->registerTask( 'updateCore', 'updateCore' );
$controller->registerTask( 'updates', 'showSites' );
$controller->registerTask( 'showUpdates', 'showUpdates' );
$controller->registerTask( 'showUpdateInstall', 'showUpdateInstall' );
$controller->registerTask( 'doUpdateInstall', 'doUpdateInstall' );
$controller->registerTask( 'browse', 'showSites' );
$controller->registerTask( 'showAddSite', 'showEditSite' );
$controller->registerTask( 'addSite', 'addSite' );
$controller->registerTask( 'showEditSite', 'showEditSite' );
$controller->registerTask( 'editSite', 'editSite' );
$controller->registerTask( 'deleteSite', 'deleteSite' );
$controller->registerTask( 'category', 'showCategory' );
$controller->registerTask( 'package', 'showPackage' );
$controller->registerTask( 'latest', 'showLatest' );
$controller->registerTask( 'search', 'showSearch' );
$controller->registerTask( 'install', 'showInstall' );
$controller->registerTask( 'doInstall', 'doInstall' );
// execute task and redirect if needed
$controller->execute( $task );
$controller->redirect();