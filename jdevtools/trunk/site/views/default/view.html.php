<?php
/**
 * Default View - HTML Renderer
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Sep 21, 2007
 * 
 * @package JDevTools
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    JDevTools
 */

class JDevToolsViewdefault extends JView
{
    function display($tpl = null)
    {
        $model =& $this->getModel();
		$links = $model->getTrackerLinks();
        $this->assignRef( 'links', $links );

        parent::display($tpl);
    }
}
?>
