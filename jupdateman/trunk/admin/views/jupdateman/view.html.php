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
 * @package JUpdateMan
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2009 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    JUpdateMan
 */

class JUpdateManViewJUpdateMan extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager' ), 'install.png' );
		JToolBarHelper::preferences('com_jupdateman', '300');
		$model =& $this->getModel();
		parent::display($tpl);
	}
}

