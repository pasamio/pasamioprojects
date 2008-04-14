<?php
/**
 * Advanced Tools Manager
 * 
 * The Advanced Tools Manager installs JPackageMan, JLibMan and the advanced menu 
 * 
 * PHP4/5
 *  
 * Created on Sep 21, 2007
 * 
 * @package Advanced Tools
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/tccprojects
 */

// no direct access
defined('_JEXEC') or die('No direct access allowed ;)');

/*
 * Make sure the user is authorized to view this page
 * We are the same security as the installer subsystem
 */
$user = & JFactory::getUser();
if (!$user->authorize('com_installer', 'installer')) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

//require_once (JApplicationHelper::getPath('admin_html'));
//require_once (JApplicationHelper::getPath('class'));

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Create the controller
$classname    = 'advancedtoolsController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
