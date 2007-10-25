<?php
/**
 * Joomla! Library Manager
 * 
 * The Joomla! Library Manager installs libraries 
 * 
 * PHP4/5
 *  
 * Created on Sep 21, 2007
 * 
 * @package jpackageman
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
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
require_once (JApplicationHelper::getPath('class'));

// Manifest Path; manifest stores information about installed extensions
define('MANIFEST_PATH',JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jpackageman' . DS .'manifests');

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
$classname    = 'jpackagemanController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
?>