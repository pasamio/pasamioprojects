<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package package_name
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

jimport( 'joomla.application.component.view');

 class jpackagemanViewdetails extends JView
{
    function display($tpl = null)
    {
    	JToolBarHelper::title( JText::_( 'JLibrary Manager - Details' ), 'install.png' );
        JToolBarHelper::trash('uninstall','Uninstall',false);
        JToolBarHelper::customX('home','back.png', 'back_f2.png','Home',false);
    	
        $model =& $this->getModel();
        $library = $_GET['package'];
		$lib = $model->getDetails($library);
		$this->assignRef( 'library', $lib);
		
    	parent::display($tpl);
    }
}
?>