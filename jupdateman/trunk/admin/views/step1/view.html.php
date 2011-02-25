<?php
defined('_JEXEC') or die('sleepy');

jimport('joomla.application.component.view');

class JUpdateManViewStep1 extends JView
{
	function display($tpl=null)
	{
		$v = new JVersion();
		$version = $v->getShortVersion();
		
		$url = "http://jsitepoint.com/update/packages/joomla/update.xml";
		$config =& JFactory::getConfig();
		$tmp_path = $config->getValue('config.tmp_path');
		$params =& JComponentHelper::getParams('com_jupdateman');
		
		$target = $tmp_path . DS . 'jupgrader.xml';
		$cached_update = $params->get('cached_update', 0);
		
		if($cached_update) {
			if(!file_exists($target)) {
				HTML_jupgrader::showError( JText::sprintf('MISSING_UPDATE', $url, $target));
				return false;
			}
		} else {
			$result = downloadFile($url,$target);
			if(is_object( $result )) {
				HTML_jupgrader::showError( JText::sprintf('DOWNLOAD_FAILED', $result->message, $result->number) );
				return false;
			}
		}
		
		if(!file_exists($target)) {
			HTML_jupgrader::showError( JText::sprintf('UPDATEFILE_NOTEXISTS', $target));
		}
		
		// Yay! file downloaded! Processing time :(
		$xmlDoc = new JSimpleXML();
		
		if (!$xmlDoc->loadFile( $target )) {
			HTML_jupgrader::showError( JText::_('PARSING_XML_FAILED' ));
			return false;
		}
		
		//$root = &$xmlDoc->documentElement;
		$root = &$xmlDoc->document;
		
		if ($root->name() != 'update') {
			HTML_jupgrader::showError( JText::_('PARSING_XML_FAILED_NOTUPDATE') );
			return false;
		}
		$rootattributes = $root->attributes();
		$latest = $rootattributes['release'];
		
		$fulldownload = '';
		$patchdownload = '';
		
		// Get the full package
		$fullpackage  				= $root->getElementByPath( 'fullpackage', 1 );
		$fullpackageattr 			= $fullpackage->attributes();
		$fulldetails 				= new stdClass();
		$fulldetails->url 			= $fullpackageattr['url'];
		$fulldetails->filename 		= $fullpackageattr['filename'];
		$fulldetails->filesize 		= $fullpackageattr['filesize'];
		$fulldetails->md5			= $fullpackageattr['md5'];		
		
		$patchdetails = new stdClass;
		
		// Find the patch package
		$patches_root = $root->getElementByPath( 'patches', 1 );
		if (!is_null( $patches_root ) ) {
			// Patches :D
			$patches = $patches_root->children();
			if(count($patches)) {
				// Many patches! :D
				foreach($patches as $patch) {
					$patchattr = $patch->attributes();
					if ($patchattr['version'] == $version) {
						$patchdetails->url = $patchattr['url'];
						$patchdetails->filename = $patchattr['filename'];
						$patchdetails->filesize = $patchattr['filesize'];
						$patchdetails->md5		= $patchattr['md5'];
						break;
					}
				}
		
			}
		}

		$session =& JFactory::getSession();
		$session->set('jupdateman_fullpackage', $fulldetails);
		$session->set('jupdateman_patchpackage', $patchdetails);
		
		
				
		
		if($latest == $version) {
			echo '<p>'. JText::_('NOUPDATESFOUND') .'</p>';
			echo '</div>';
			return true;
		} elseif(version_compare($latest, $version, '<')) {
			echo '<p>'. JText::_('GREATERVERSION') .'</p><br /><br />';
			echo '<p>'. JText::_('RELEASEINFO') .'</p>';
			echo '</div>';
			return true;
		}
		
		$updater = $root->getElementByPath('updater', 1);
		if(!$updater) {
			HTML_jupgrader::showError( JText::_('FAILED_TO_GET_UPDATER_ELEMENT' ) );
			return false;
		}
		
		$session->set('jupdateman_updateurl', $updater->data());		
		
		$updater_attributes = $updater->attributes();
		
		if(version_compare($updater_attributes['minimumversion'], getComponentVersion(), '>')) 
		{
			echo '<p>Current updater version is lower than minimum version for this update.</p>';
			echo '<p>Please update this extension. This can be attempted automatically or you can download the update and install it yourself.</p>';
			echo '<ul>';
			echo '<li><a href="index.php?option=com_jupdateman&task=autoupdate">Automatically update &gt;&gt;&gt;</a></li>';
			echo '<li><a target="_blank" href="'. $updater->data() .'">Download package and install manually (new window) &gt;&gt;&gt;</a></li>';
			echo '</ul>';
			return false;
		}
		
		if(version_compare($updater_attributes['currentversion'], getComponentVersion(), '>')) 
		{
			echo '<p>An update ('. $updater_attributes['currentversion'] .') is available for this extension. You can <a href="index.php?option=com_jupdateman&task=autoupdate">update automatically</a> or <a target="_blank" href="'. $updater->data() .'">manually download</a> and install the update.</p>';
		}
		
		echo '<p>'. JText::sprintf('CURRENT_RUNNING_LATEST', $version, $latest) .'</p>';

		
		$message_element = $root->getElementByPath('message');
		if($message_element) {
			$message = $message_element->data();
			if(strlen($message)) {
				echo '<p style="background: lightblue; padding: 5px; spacing: 5px; border: 1px solid black;"><b>'. JText::_('UPDATE_MESSAGE') .'</b><br /> '. $message.'</p>';
			}
		}

		$this->assignRef('fulldetails', $fulldetails);
		$this->assignRef('patchdetails', $patchdetails);
		$this->assignRef('cached_update', $cached_update);
		$this->assignRef('tmp_path', $tmp_path);
		parent::display($tpl);
	}
}