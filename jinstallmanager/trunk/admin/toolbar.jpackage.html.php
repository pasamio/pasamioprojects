<?php/** * @version		$Id: toolbar.jpackage.html.php 202 2006-08-20 07:05:55Z schmalls $ * @package 	Joomla * @subpackage	JPackage * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved. * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php * Joomla! is free software. This version may have been modified pursuant to the * GNU General Public License, and as distributed it includes or is derivative * of works licensed under the GNU General Public License or other free or open * source software licenses. See COPYRIGHT.php for copyright notices and * details. */defined( '_JEXEC' ) or die( 'Restricted access' );
/** * @package 	Joomla * @subpackage	JPackage * @since 		1.5 */class TOOLBAR_jpackage{    	/**     * Default	 */    function _DEFAULT()    {    	JMenuBar::title( JText::_( 'Find and Install' ), 'searchtext.png' );        JMenuBar::custom( null, 'forward', 'forward', JText::_( 'Continue' ), true, false );    }        /**     * Updates sites menu items     */    function _UPDATES()    {        JMenuBar::title( JText::_( 'Find and Install Choose Site' ), 'searchtext.png' );        JMenuBar::custom( 'showUpdates', 'forward', 'forward', JText::_( 'Continue' ), true, false );        JMenuBar::divider();        JMenuBar::addNew( 'showAddSite', JText::_( 'Add Site' ) );        JMenuBar::editList( 'showEditSite', JText::_( 'Edit Site' ) );        JMenuBar::deleteList( '', 'deleteSite', JText::_( 'Delete Site' ) );        JMenuBar::divider();        JMenuBar::back();    }        /**     * Shows available updates     */    function _SHOW_UPDATES()    {        JMenuBar::title( JText::_( 'Find and Install Available Updates' ), 'searchtext.png' );        JMenuBar::custom( 'showUpdateInstall', 'forward', 'forward', JText::_( 'Continue' ), true, false );        JMenuBar::divider();        JMenuBar::back();    }        /**     * Update install menu items     */    function _UPDATE_INSTALL()    {        JMenuBar::title( JText::_( 'Find and Install Update Extensions' ), 'searchtext.png' );        JMenuBar::custom( 'doUpdateInstall', 'forward', 'forward', JText::_( 'Continue' ), true, false );        JMenuBar::divider();        JMenuBar::back();    }        /**     * Browse sites menu items     */    function _BROWSE()    {        JMenuBar::title( JText::_( 'Find and Install Choose Site' ), 'searchtext.png' );        JMenuBar::custom( 'category', 'forward', 'forward', JText::_( 'Continue' ), true, false );        JMenuBar::divider();        JMenuBar::addNew( 'showAddSite', JText::_( 'Add Site' ) );        JMenuBar::editList( 'showEditSite', JText::_( 'Edit Site' ) );        JMenuBar::deleteList( '', 'deleteSite', JText::_( 'Delete Site' ) );        JMenuBar::divider();        JMenuBar::back();    }        /**     * Edit site menu     */    function _EDIT_SITE()    {        JMenuBar::title( JText::_( 'Find and Install Edit Site' ), 'searchtext.png' );        JMenuBar::save( 'editSite', JText::_( 'Save Site' ) );        JMenuBar::cancel( 'sites', JText::_( 'Cancel' ) );    }        /**     * Add site menu     */    function _ADD_SITE()    {        JMenuBar::title( JText::_( 'Find and Install Add Site' ), 'searchtext.png' );        JMenuBar::save( 'addSite', JText::_( 'Save Site' ) );        JMenuBar::cancel( 'sites', JText::_( 'Cancel' ) );    }        /**     * Category menu items     */    function _CATEGORY()    {        JMenuBar::title( JText::_( 'Find and Install Browse For Extensions' ), 'searchtext.png' );        JMenuBar::back();    }        /**     * Package menu items     */    function _PACKAGE()    {        JMenuBar::title( JText::_( 'Find and Install Extension Details' ), 'searchtext.png' );        JMenuBar::back();    }        /**     * Install menu items     */    function _INSTALL()    {        JMenuBar::title( JText::_( 'Find and Install Install Extensions' ), 'searchtext.png' );        JMenuBar::custom( 'doInstall', 'forward', 'forward', JText::_( 'Continue' ), true, false );        JMenuBar::divider();        JMenuBar::back();    }}