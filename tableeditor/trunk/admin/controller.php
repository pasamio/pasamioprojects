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
    
    function delete() {
    	$model 	= &$this->getModel( 'tableeditor' );
    	$instance = $model->getTableInfo();
    	if($model->delete()) {
    		$this->setMessage('Deleted Entry!');
    	} else {
    		$this->setMessage('Failed to delete entry!');
    	}
    	$this->setRedirect('index.php?option=com_tableeditor&table='.$instance->table);
    }

    function uninstall() {
    	$model = $this->getModel('tableeditor');
    	$table = JRequest::getWord('table','');
    	if(strlen($table)) {
    		if($model->uninstall($table)) {
    			$this->setMessage(JText::_('Uninstall Success'));
    		} else {
				$this->setMessage(JText::_('Uninstall Failure'));
    		}
    	}
    	$this->setRedirect('index.php?option=com_tableeditor');
    }
    
    function add() {
    	$model 	= &$this->getModel('tableeditor');
    	$view 	= &$this->getView('details');
    	$view->setModel( $model, true );
    	$view->display();
    }
    
    function remove() {
    	$model	= &$this->getModel('tableeditor');
    	$instance = $model->getTableInfo();
    	if($model->delete()) $this->setMessage('Item removed');
    		else $this->setMessage('Item failed to be removed');
    	 $this->setRedirect('index.php?option=com_tableeditor&task=listrows&table='. $instance->table);
    }

	function save() {
		$model = $this->getModel('tableeditor');
		
		$instance = $model->getTableInfo();
		$form = new JParameter('', JPATH_COMPONENT.DS.'tables'.DS. $instance->table . '.xml');
		$form->bind($_POST);
		$data = $form->toArray();
		$db =& JFactory::getDBO();
		$oldkey = JRequest::getVar('__oldkey',$data["params"][$instance->key]);
		$db->setQuery('SELECT count(*) FROM #__'. $instance->table .' WHERE '. $instance->key .' = "'. $oldkey .'"');
		$result = $db->loadResult();
		if($data['params'][$instance->key] != '0' && $result) {
			$set = Array();
			foreach($data['params'] as $key=>$value) {
				$set[] = $key .' = "'. $value .'"';
			}
			$db->setQuery('UPDATE #__'. $instance->table .' SET ' . implode(', ', $set) .
				' WHERE '. $instance->key .' = "'. $oldkey .'"');
		} else {
			$col = Array();
			$val = Array();
			foreach($data['params'] as $key=>$value) {
				$col[] 		= $key;
				$val[]		= $value; 
			}
			$db->setQuery('INSERT INTO #__'. $instance->table .' ( ' . implode(',', $col) . ')' .
				' VALUES("'. implode('","', $val) .'")');
		}
		//die($db->getQuery());
		if($db->Query()) {
			$this->setMessage(JText::_('Success!'));
		} else {
			$this->setMessage(JText::_('Update failed'), 'error');
		}
		$this->setRedirect('index.php?option=com_tableeditor&task=listrows&table='. $instance->table);
	}
}

?>
