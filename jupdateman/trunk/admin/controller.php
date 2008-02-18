<?php

 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JUpdateManController extends JController {
	function jupgrader() {
		parent::mosAbstractTasker( 'intro' ); 
	}

	function intro() {
		HTML_jupgrader::intro();
	}

	function step1() {
		global $mosConfig_absolute_path, $mosConfig_live_site;
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 1' ), 'install.png' );
		// Download and parse update XML file and provide select download option
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step1.php' );
	}

	function step2() {
		global $mosConfig_absolute_path, $mosConfig_live_site;
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 2' ), 'install.png' );
		// Download selected file (progress dialog?) and Are You Sure?
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step2.php' );
	}

	function step3() {
		global $mosConfig_absolute_path, $mosConfig_live_site;
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 3' ), 'install.png' );
		// Install
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step3.php' );
	}					
}
?>