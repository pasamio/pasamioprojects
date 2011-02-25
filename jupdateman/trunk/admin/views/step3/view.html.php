<?php
defined('_JEXEC') or die('step4');

jimport('joomla.application.component.view');

class JUpdateManViewStep3 extends JView
{
	function display($tpl=null) {
		echo '<p>'. JText::_('CONGRATULATIONS') .'</p>';
		
		$params = JComponentHelper::getParams('com_jupdateman');
		if($params->get('cached_update', 0)) {
			echo '<p>'. JText::_('CONGRATULATIONS_CACHED_UPDATE' ) .'</p>';
		}
				
		parent::display($tpl);
	}
}
