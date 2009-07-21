<?php
/**
 * Joomla! Upgrade Helper
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

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

@set_time_limit(0); // Make sure we don't timeout while downloading
$config =& JFactory::getConfig();
$tmp_path = $config->getValue('config.tmp_path');

$params =& JComponentHelper::getParams('com_jupdateman');

if(!$params->get('cached_update', 0)) {
	$result = downloadFile($url,$tmp_path.DS.$file);
	if(is_object( $result )) {
		HTML_jupgrader::showError( 'Download Failed: '. $result->message . '('. $result->number . ')</p>' );
		return false;
	}
} else {
	if(!file_exists($tmp_path.DS.$details->filename)) {
		HTML_jupgrader::showError('File does not exist and running in cached mode. Upload the update file. <a href="'. $details->url .'" target="_blank">Download file here.</a> <br /><a href="index.php?option=com_jupdateman&task=step2">Reload Page.</a>');
		return false;
	}
}

$session->set('jupdateman_filename', $file);

?>
<p>The file '<?php echo $file ?>' has been downloaded from <a href="<?php echo $url ?>" target="_blank"><?php echo $url ?></a>.</p>
<p>If you are certain you want to use this method, <a href="index.php?option=com_jupdateman&task=step3">you can proceed with the install &gt;&gt;&gt;</a></p>