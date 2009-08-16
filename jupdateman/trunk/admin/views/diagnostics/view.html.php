<?php

defined('_JEXEC') or die('Doctor Who?');

jimport('joomla.application.component.view');

class JUpdateManViewDiagnostics extends JView
{
	function display($tpl = null)
	{
		$model =& $this->getModel();
		$diagnostics =& $model->getDiagnostics();
		$this->assignRef('messages', $diagnostics);
		parent::display($tpl);
	}
}