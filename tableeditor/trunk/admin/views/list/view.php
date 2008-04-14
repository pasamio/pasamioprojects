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

class TableEditorViewList extends JView
{
    function display($tpl = null)
    {
    	
        //JToolBarHelper::addNewX(); // Use install manager
    	
        $model =& $this->getModel();
		$rows = $model->getRows();
		$instance = $model->getTableInfo();
		$this->assignRef( 'items', $rows);
		$this->assignRef( 'table', $instance);
		JToolBarHelper::title( JText::_( 'Table Editor' ) .': '.$instance->name, 'config.png' );
		JToolBarHelper::addNew();		
		JToolBarHelper::customX('default','back.png', 'back_f2.png','All Tables',false);
        parent::display($tpl);
    }
}