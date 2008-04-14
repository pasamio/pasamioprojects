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
 * @author Your Name <author@toowoombarc.qld.gov.au>
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

jimport('joomla.application.component.controller');

/**
 * JLibMan Component Controller
 *
 * @package    JLibMan
 */
class JLibManController extends JController
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

    function uninstall() {
    	$model = $this->getModel('jlibman');
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
}

?>
