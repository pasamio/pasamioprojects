<?php

 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JUpdateManController extends JController {
	function step1() {
		JToolBarHelper::title( JText::_( 'UPDATEMANAGER_STEP1' ), 'install.png' );
		JToolBarHelper::preferences('com_jupdateman', '300');
		// Download and parse update XML file and provide select download option
		$model =& $this->getModel();
//		$res = $model->autoupdate();
		$view =& $this->getView('step1', 'html');
//		$view->setLayout(($res ? 'success' : 'failure'));
		$view->setModel($model, true); // set the model and make it default (true)
		$view->display();		
	}

	function step2() {
		JToolBarHelper::title( JText::_( 'UPDATEMANAGER_STEP2' ), 'install.png' );
		// Download selected file (progress dialog?) and Are You Sure?
		$model =& $this->getModel();
//		$res = $model->autoupdate();
		$view =& $this->getView('step2', 'html');
//		$view->setLayout(($res ? 'success' : 'failure'));
		$view->setModel($model, true); // set the model and make it default (true)
		$view->display();
	}

	function step3() {
		JToolBarHelper::title( JText::_( 'UPDATEMANAGER_STEP3' ), 'install.png' );
		// Install
		$model =& $this->getModel();
//		$res = $model->autoupdate();
		$view =& $this->getView('step3', 'html');
//		$view->setLayout(($res ? 'success' : 'failure'));
		$view->setModel($model, true); // set the model and make it default (true)
		$view->display();		
	}
	
	function autoupdate() {
		$model =& $this->getModel();
		$res = $model->autoupdate();
		$view =& $this->getView('results', 'html');
		$view->setLayout(($res ? 'success' : 'failure'));
		$view->setModel($model, true); // set the model and make it default (true)
		$view->display();
	}
	
	function diagnostics() {
		JToolBarHelper::title( JText::_( 'UPDATEMANAGER_DIAGNOSTICS' ), 'install.png' );
		JToolBarHelper::customX('default','back.png', 'back_f2.png','Back',false);
		JToolBarHelper::preferences('com_jupdateman', '300');
		$model =& $this->getModel();
		$view =& $this->getView('diagnostics', 'html');
		$view->setModel($model, true);
		$view->display();
	}
}
