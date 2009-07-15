<?php

 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JUpdateManController extends JController {
	function step1() {
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 1' ), 'install.png' );
		JToolBarHelper::preferences('com_jupdateman', '550');
		// Download and parse update XML file and provide select download option
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step1.php' );
	}

	function step2() {
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 2' ), 'install.png' );
		// Download selected file (progress dialog?) and Are You Sure?
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step2.php' );
	}

	function step3() {
		JToolBarHelper::title( JText::_( 'Joomla! Update Manager - Step 3' ), 'install.png' );
		// Install
		require_once( JPATH_ADMINISTRATOR . '/components/com_jupdateman/step3.php' );
	}
}
