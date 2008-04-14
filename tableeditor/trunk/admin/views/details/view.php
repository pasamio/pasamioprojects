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
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    JLibMan
 */

class TableEditorViewDetails extends JView
{
    function display($tpl = null)
    {
    	
        $model =& $this->getModel();
		$cols = $model->loadData();
		$this->assignRef( 'items', $cols);
		$instance = $model->getTableInfo();
		$form = new JParameter('', JPATH_COMPONENT.DS.'tables'.DS. $instance->table . '.xml');
		
		foreach($cols as $key=>$value) {
			$form->set($key, $value);
		}
		
		$this->assignRef( 'oldkey', $cols[$instance->key]);
		$this->assignRef( 'form', $form );
		$this->assignRef( 'table', $instance );

    	JToolBarHelper::title( JText::_( 'Table Editor - Row Editor' ) .': '. $instance->name, 'config.png' );
    	JToolBarHelper::save();
    	JToolBarHelper::trash('remove','Delete Row',false);
        JToolBarHelper::cancel('listrows');
		
		
        parent::display($tpl);
    }
}
