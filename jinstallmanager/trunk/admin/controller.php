<?php
/**
 * @version     $Id: controller.php 202 2006-08-20 07:05:55Z schmalls $
 * @package     JInstallManager
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/**
 * No direct access and basic intialisation.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.controller' );

/**
 * JPackage component controller
 * 
 * @author       Joshua Thompson
 * @package      Joomla
 * @subpackage   JPackage
 * @since        1.5
 */
class JPackageController extends JController
{
    /**
     * Shows the default page
     */
    function showDefault()
    {
        // create the view
        $this->setViewName( 'Default', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Default', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->display();
    }
    
    /**
     * Shows the core updates
     */
    function showCore()
    {
        // create the view
        $this->setViewName( 'Core', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Core', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->display();
    }
    
    /**
     * Updates the core
     */
    function updateCore()
    {
        // get/create the model
        $model = & $this->getModel( 'Core', 'JPackageModel' );
        // update
        $message = $model->updateCore();
        // set redirect
        $this->setRedirect( 'index.php?option=com_jpackage', $message );
    }
    
    /**
     * Shows the available updates
     */
    function showSites()
    {
        // create the view
        $this->setViewName( 'Sites', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->display();
    }
    
    /**
     * Shows the add new site page
     */
    function showAddSite()
    {
        // create the view
        $this->setViewName( 'Sites', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->displayEdit();
    }
    
    /**
     * Adds a new site
     */
    function addSite()
    {
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // update
        $message = $model->addSite();
        // set redirect
        $task = JRequest::getVar( 'return', '', 'default', 'STRING' );
        $this->setRedirect( 'index.php?option=com_jpackage&task=' . $task, $message );
    }
    
    /**
     * Shows the edit site page
     */
    function showEditSite()
    {
        // create the view
        $this->setViewName( 'Sites', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->displayEdit();
    }
    
    /**
     * Edits a site
     */
    function editSite()
    {
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // update
        $message = $model->editSite();
        // set redirect
        $this->setRedirect( 'index.php?option=com_jpackage&task=sites', $message );
    }
    
    /**
     * Deletes a site
     */
    function deleteSite()
    {
        // get/create the model
        $model = & $this->getModel( 'Sites', 'JPackageModel' );
        // update
        $message = $model->deleteSite();
        // set redirect
        $this->setRedirect( 'index.php?option=com_jpackage&task=sites', $message );
    }
    
    /**
     * Shows the available updates
     */
    function showUpdates()
    {
        // create the view
        $this->setViewName( 'Updates', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Updates', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );
        // display the view
        $view->display();
    }
    
    /**
     * Shows all the packages to be installed for the selected updates
     */
    function showUpdateInstall()
    {
        // create the view
        $this->setViewName( 'Updates', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Updates', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );
        // display the view
        $view->displayUpdates();
    }
    
    /**
     * Performs the update installation and sets redirect
     */
    function doUpdateInstall()
    {
        // get/create the model
        $model = & $this->getModel( 'Updates', 'JPackageModel' );
        // install
        $message = $model->doUpdateInstall( $model->getExtensions() );
        // set redirect
        $this->setRedirect( 'index.php?option=com_jpackage', $message );
    }
    
    /**
     * Shows the category
     */
    function showCategory()
    {
        // create the view
        $this->setViewName( 'Category', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Category', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );
        // display the view
        $view->display();
    }
    
    /**
     * Shows the package
     */
    function showPackage()
    {
        // create the view
        $this->setViewName( 'Package', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $packageModel = & $this->getModel( 'Package', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $packageModel, true );
        // get/create the model
        $categoryModel = & $this->getModel( 'Category', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $categoryModel );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );
        // display the view
        $view->display();
    }
    
    /**
     * Shows the latest package
     */
    function showLatest()
    {
        // create the view
        $this->setViewName( 'Latest', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Latest', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // display the view
        $view->display();
    }
    
    /**
     * Shows the search page
     */
    function showSearch()
    {
        // create the view
        $this->setViewName( 'Search', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Search', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );  
        // display the view
        $view->display();
    }
    
    /**
     * Shows the installation page
     */
    function showInstall()
    {
        // create the view
        $this->setViewName( 'Install', 'com_jpackage', 'JPackageView' );
        $view = & $this->getView();
        // get/create the model
        $model = & $this->getModel( 'Install', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $model, true );
        // get/create the model
        $categoryModel = & $this->getModel( 'Category', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $categoryModel );
        // get/create the model
        $sitesModel = & $this->getModel( 'Sites', 'JPackageModel' );
        // push the model into the view
        $view->setModel( $sitesModel );
        // get/create the model
        $updatesModel = & $this->getModel( 'Updates', 'JPackageModel' );
        // push the model into the view (as default)
        $view->setModel( $updatesModel );
        // display the view
        $view->display();
    }
    
    /**
     * Performs the installation and sets redirect
     */
    function doInstall()
    {
        // get/create the model
        $model = & $this->getModel( 'Install', 'JPackageModel' );
        // get/create the model
        $updatesModel = & $this->getModel( 'Updates', 'JPackageModel' );
        // install
        $message = $model->doInstall( $updatesModel->getExtensions() );
        // set redirect
        $this->setRedirect( 'index.php?option=com_jpackage', $message );
    }
    
}
