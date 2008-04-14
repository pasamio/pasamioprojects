<?php
/**
 * J!Repository Administrator
 * 
 * Joomla! Package Repository
 * 
 * PHP4/5
 *  
 * Created on Oct 25, 2007
 * 
 * @package JRepository
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
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
define('MANIFEST_PATH',JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jlibman' . DS .'manifests');

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
$classname    = 'JLibManController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();


