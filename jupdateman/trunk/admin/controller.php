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
	
	function extractPackage()
	{
		jimport('joomla.filesystem.file');
		juimport('pasamio.pfactory');
		
		$session =& JFactory::getSession();
		$file = $session->get('jupdateman_filename');
		
		if(!$file) { // jump again
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_jupdateman&task=step1'); // back to step one if invalid session 
		}
		
		$params = JComponentHelper::getParams('com_jupdateman');
		$extractor = $params->get('extractor', 0);
		
		define('JUPDATEMAN_EXTRACTOR_16', 		0);
		define('JUPDATEMAN_EXTRACTOR_15', 		1);
		define('JUPDATEMAN_EXTRACTOR_PEAR', 	2);
		
		
		@set_time_limit(0); // try to set this just in case - doesn't hurt either
		
		$config =& JFactory::getConfig();
		$tmp_path = $config->getValue('config.tmp_path');
		$filename = $tmp_path .DS. $file;
		
		switch($extractor)
		{
			case JUPDATEMAN_EXTRACTOR_16:
				juimport('joomla.filesystem.archive');
				if(!JArchive::extract($filename, JPATH_SITE . DS )) {
					HTML_jupgrader::showError(JText::_('FAILED_TO_EXTRACT'));
					return false;
				}
				break;
		
			case JUPDATEMAN_EXTRACTOR_15:
				jimport('joomla.filesystem.archive');
				if(!JArchive::extract($filename, JPATH_SITE . DS)) {
					HTML_jupgrader::showError(JText::_('FAILED_TO_EXTRACT'));
					return false;
				}
				break;
				
			case JUPDATEMAN_EXTRACTOR_PEAR:
				jimport('pear.archive_tar.Archive_Tar');
				$extractor = new Archive_Tar($filename);
				if(!$extractor->extract(JPATH_SITE . DS)) {
					HTML_jupgrader::showError(JText::_('FAILED_TO_EXTRACT'));
					return false;
				}		
				break;
		}
		
		$installation = JPATH_SITE .DS.'installation';
		
		if (is_dir( $installation )) {
			JFolder::delete($installation);
		}
		
		$cached_update = $params->get('cached_update', 0);
		
		// delete the left overs unless cached update
		if(!$cached_update) 
		{
			if (is_file( $filename ) ) {
				JFile::delete($filename);
			}
			
			$upgrade_xml = $tmp_path . DS . 'jupgrader.xml';
			if ( is_file( $upgrade_xml ) ) {
				JFile::delete($upgrade_xml);
			}
		}
		$this->setRedirect('index.php?option=com_jupdateman&task=step3');			
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
	
	function step4() {
		JToolBarHelper::title( JText::_( 'UPDATEMANAGER_STEP4' ), 'install.png' );
		// Install
		$model =& $this->getModel();
//		$res = $model->autoupdate();
		$view =& $this->getView('step4', 'html');
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
