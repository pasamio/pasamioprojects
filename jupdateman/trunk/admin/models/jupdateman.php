<?php
/**
 * JUpdateMan Main Model
 *
 * Main display and listing model
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
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.filesystem.folder');
jimport( 'joomla.installer.helper');
jimport( 'joomla.installer.installer');
jimport( 'joomla.client.helper');
jimport( 'joomla.client.ftp');

/**
 * Main Model
 *
 * @package    JUpdateMan
 */
class JUpdateManModelJUpdateMan extends JModel
{

	function autoupdate() {
		$update = $this->parseXMLFile();
		$config =& JFactory::getConfig();
		$tmp_dest = $config->getValue('config.tmp_path');
		if(!is_object($update)) {
			// parseXMLFile will have set a message hopefully
			//$this->setState('message', 'XML Parse failed');
			return false;
		} else {
			$destination = $tmp_dest.DS.'com_jupdateman_auto.tgz';
			$download = downloadFile($update->updaterurl, $destination);
			if($download !== true) {
				$this->setState('message', $download->error_message);
				return false;
			} else {
				$package = JInstallerHelper::unpack($destination);
				if(!$package) {
					$this->setState('message', 'Unable to find install package');
					return false;
				}
				$installer =& JInstaller::getInstance();

				// Install the package
				if (!$installer->install($package['dir'])) {
					// There was an error installing the package
					$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
					$result = false;
				} else {
					// Package installed sucessfully
					$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
					$result = true;
				}

				// Grab the application
				$mainframe =& JFactory::getApplication();
				// Set some model state values
				$mainframe->enqueueMessage($msg);
				$this->setState('name', $installer->get('name'));
				$this->setState('result', $result);
				$this->setState('message', $installer->message);
				$this->setState('extension.message', $installer->get('extension.message'));

				// Cleanup the install files
				if (!is_file($package['packagefile'])) {
					$config =& JFactory::getConfig();
					$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
				}

				JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

				return $result;
			}
		}
	}

	function getUpdatePackage() {
		$update = $this->parseXMLFile();
		return $update->updaterurl;
	}

	function parseXMLFile($filename=null) {
		static $updates = Array();

		if(array_key_exists($filename, $updates)) {
			return $updates[$filename];
		}

		$v = new JVersion();
		$version = $v->getShortVersion();


		$update = new stdClass();

		// Yay! file downloaded! Processing time :(
		$xmlDoc = new JSimpleXML();

		$config =& JFactory::getConfig();
		$tmp_path = $config->getValue('config.tmp_path');

		if(is_null($filename)) {
			$filename = $tmp_path.DS.'jupgrader.xml';
		}

		if (!$xmlDoc->loadFile( $filename )) {
			$this->setState('message', 'File load failed: '. $filename);
			return false;
		}

		//$root = &$xmlDoc->documentElement;
		$root = &$xmlDoc->document;

		if ($root->name() != 'update') {
			$this->setState('message', 'Parsing XML Document Failed: Not an update definition file!');
			return false;
		}

		$rootattributes = $root->attributes();

		$update->release = $rootattributes['release'];

		$updater = $root->getElementByPath('updater', 1);
		if(!$updater) {
			$this->setState('message', 'Failed to get updater element. Possible invalid update!');
			return false;
		}

		$updater_attributes = $updater->attributes();

		$update->updaterurl = $updater->data();
		$update->minver = $updater_attributes['minimumversion'];
		$update->curver = $updater_attributes['currentversion'];

		// Get the full package
		$fullpackage  				= $root->getElementByPath( 'fullpackage', 1 );
		$fullpackageattr 			= $fullpackage->attributes();
		$fulldetails 				= new stdClass();
		$fulldetails->url 			= $fullpackageattr['url'];
		$fulldetails->filename 		= $fullpackageattr['filename'];
		$fulldetails->filesize 		= $fullpackageattr['filesize'];

		$update->fullpackage = clone($fulldetails);

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
						break;
					}
				}
			}
		}
		
		$update->patchpackage = clone($patchdetails);
		$updates[$filename] = clone($update); // keep an original copy
		return $update;
	}
	
	function getDiagnostics() {
		$diagnostics = Array();
		$config =& JFactory::getConfig();
		$params =& JComponentHelper::getParams('com_jupdateman');
		
		// Check to see if FTP mode is enabled and configured correctly
		$FTPOptions = JClientHelper::getCredentials('ftp');
		if($FTPOptions['enabled'] == 1)
		{
			if($FTPOptions['root'][0] != '/')
			{
				// So the item isn't prefixed properly, this will cause an error
				$message 	  = JText::_('INCORRECT_FTP_PATH_MESSAGE');
				$description  = JText::_('INCORRECT_FTP_PATH_DESC');
				$ftp = new JFTP();
				if($ftp->connect($FTPOptions['host'], $FTPOptions['port']))
				{
					if($ftp->login($FTPOptions['user'], $FTPOptions['pass'])) {
						$pwd = $ftp->pwd();
						if(strlen($pwd) > 1) {
							$suggested_pwd = $pwd.'/'.$FTPOptions['root'];
						} else {
							$suggested_pwd = '/'. $FTPOptions['root'];
						}
						$description .= '<br />'.JText::sprintf('INCORRECT_FTP_PATH_CURRENT', $FTPOptions['root']);
						$description .= '<br />'.JText::sprintf('INCORRECT_FTP_PATH_SUGGESTED', $suggested_pwd); 
					} else {
						$description .= '<br />'.JText::_('INCORRECT_FTP_PATH_LOGIN_FAILURE');
					}
				} else {
					$description .= '<br />'.JText::_('INCORRECT_FTP_PATH_CONNECT_FAILURE');
				}
				
				$diagnostics[] = Array('message'=>$message, 'description'=>$description);
			}
		}
		
		// Temporary Path Check
		$config_tmp_path = rtrim($config->getValue('config.tmp_path'), '/');
		$calculated_tmp_path = JPATH_ROOT . DS . 'tmp';
		if($calculated_tmp_path != $config_tmp_path)
		{
			$message = JText::_('INVALID_TEMP_PATH_MESSAGE');
			$description  = JText::_('INVALID_TEMP_PATH_DESC');
			$description .= '<br />'.JText::sprintf('INVALID_TEMP_PATH_CONFIGURED_PATH', $config_tmp_path); 
			$description .= '<br />'.JText::sprintf('INVALID_TEMP_PATH_SUGGESTED_PATH', $calculated_tmp_path);
			$diagnostics[] = Array('message'=>$message, 'description'=>$description);		
		}
		
		// Check for download methods - fopen and curl
		$current_method = $params->get('download_method');
		$http_support = in_array('http', stream_get_wrappers());
		$curl_support = function_exists('curl_init');
		if(!$http_support && !$curl_support) 
		{
			$message = JText::_('UNAVAILABLE_METHOD_MESSAGE');
			$description = JText::_('UNAVAILABLE_METHOD_ALL_DESC');
			$diagnostics[] = Array('message'=>$message, 'description'=>$description);
		} 
		else
		{
			// is there http support and are we thinking about trying to use it?
			if(!$http_support && $current_method == JUPDATEMAN_DLMETHOD_FOPEN)
			{
				$message = JText::_('UNAVAILABLE_METHOD_MESSAGE');
				$description = JText::_('UNAVAILABLE_METHOD_FOPEN_DESC');
				$diagnostics[] = Array('message'=>$message, 'description'=>$description);
			}
			// is there curl support and are we thinking about trying to use it?
			if(!$curl_support && $current_method == JUPDATEMAN_DLMETHOD_CURL)
			{
				$message = JTEXT::_('UNAVAILABLE_METHOD_MESSAGE');
				$description = JText::_('UNAVAILABLE_METHOD_CURL_DESC');
				$diagnostics[] = Array('message'=>$message, 'description'=>$description);
			}
		}
		
		return $diagnostics;
	}
}

