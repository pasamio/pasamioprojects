<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
 * 
 * @package JLibMan
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * JLibMan Component Controller
 *
 * @package    JLibMan
 */
class TableEditorController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        parent::display();
    }
    
    function listrows() {
    	$model	= &$this->getModel( 'tableeditor' );
		$view	= &$this->getView( 'list');
		$view->setModel( $model, true );
		$view->display();
    }
    
    function details() {
    	$model	= &$this->getModel( 'tableeditor' );
		$view	= &$this->getView( 'details');
		$view->setModel( $model, true );
		$view->display();
    }

    function uninstall() {
    	$model = $this->getModel('tableeditor');
    	$lib = JRequest::getWord('library','');
    	if(strlen($lib)) {
    		if($model->uninstall($lib)) {
    			$mainframe->enqueueMessage(JText::_('Uninstall Success'));
    		} else {
				$mainframe->enqueueMessage(JText::_('Uninstall Failure'));
    		}
    	}
    	parent::display();
    }

	function save() {
		$form = new JParameter('', JPATH_COMPONENT.DS.'tables'.DS. $instance->table . '.xml');
		$form->
		$this->setRedirect('index.php?option=com_tableeditor&task=listrows&table=advancedtools_menu','Write save code!');
	}
}

?>
