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
 * @package JPackageMan
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * jpackageman Component Controller
 *
 * @package    jpackageman
 */
class jpackagemanController extends JController
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
    	$model = $this->getModel('jpackageman');
    	$lib = JRequest::getWord('library','');
    	if(strlen($lib)) echo $model->uninstall($lib);
    	parent::display();
    }
}

?>
