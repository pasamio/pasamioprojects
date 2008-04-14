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
 * @package JPackageMan
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

jimport( 'joomla.application.component.view');

 class jpackagemanViewdetails extends JView
{
    function display($tpl = null)
    {
    	JToolBarHelper::title( JText::_( 'JPackage Manager - Details' ), 'install.png' );
    	JToolBarHelper::trash('remove', 'Remove Manifest', false);
        JToolBarHelper::trash('uninstall','Uninstall',false);
        JToolBarHelper::customX('home','back.png', 'back_f2.png','Home',false);
        
    	
        $model =& $this->getModel();
        $package = $_GET['package'];
		$lib = $model->getDetails($package);
		$this->assignRef( 'package', $lib);
		
		JHTML::_('behavior.tooltip');
		
    	parent::display($tpl);
    }
}
?>
