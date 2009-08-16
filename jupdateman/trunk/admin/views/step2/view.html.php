<?php

defined('_JEXEC') or die('MVC COMPONENTRY, PERMISSION DENIED YOU WILL BE');

jimport('joomla.application.component.view');

class JUpdateManViewStep2 extends JView
{
	function display($tpl=null)
	{
		$session =& JFactory::getSession();
		switch(JRequest::getVar('target', 'patch')) {
			default:
			case 'patch':
				$details = $session->get('jupdateman_patchpackage');
				break;
			case 'full':
				$details = $session->get('jupdateman_fullpackage');
				break;
		}
		if(!is_object($details)) {
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_jupdateman&task=step1'); // back to step one if invalid session 
		}
		$url  = $details->url;
		$file = $details->filename;
		$size = $details->filesize;
		$md5  = $details->md5;
		
		@set_time_limit(0); // Make sure we don't timeout while downloading
		$config =& JFactory::getConfig();
		$tmp_path = $config->getValue('config.tmp_path');
		$file_path = $tmp_path.DS.$file;
		 
		$params =& JComponentHelper::getParams('com_jupdateman');
		
		
		
		if(!$params->get('cached_update', 0)) {
			$result = downloadFile($url,$file_path);
			if(is_object( $result )) {
				HTML_jupgrader::showError( JText::sprintf('DOWNLOAD_FAILED', $result->message, $result->number) );
				return false;
			}
		} else {
			if(!file_exists($file_path)) {
				HTML_jupgrader::showError( JText::sprintf( 'MISSING_FILE_CACHEDMODE', $details->url ) );
				return false;
			}
		}
		
		if(strlen($md5)) {
			if(md5_file($file_path) != $md5) {
				HTML_jupgrader::showError( JText::_( 'MD5_ERROR' ) );
				return false;
			}
		} else {
			echo '<p>'. JText::_('NO_MD5_HASH') .'</p>';
		}
		
		$session->set('jupdateman_filename', $file);
		
		?>
		<p><?php echo JText::sprintf('FILE_DOWNLOADED', $file, $url, $url) ?></p>
		<p><?php echo JText::_('PROCEED_WITH_INSTALL')?></p>
		<?php 
		parent::display($tpl);
	}
}