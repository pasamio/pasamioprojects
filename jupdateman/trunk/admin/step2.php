<?php
/**
 * Joomla! Upgrade Helper
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$url  = JRequest::getVar( 'url', null );
$file = JRequest::getVar( 'filename', null );
$size = JRequest::getVar( 'filesize', 0);

set_time_limit(0); // Make sure we don't timeout while downloading
$result = downloadFile($url,JPATH_SITE . '/cache/'.$file);
if(is_object( $result )) {
	HTML_jupgrader::showError( 'Download Failed: '. $result->message . '('. $result->number . ')</p>' );
	return false;
}
?>
<div align="left" class="upgradebox">
<p>The file '<?php echo $file ?>' has been downloaded from <a href="<?php echo $url ?>" target="_blank"><?php echo $url ?></a>.</p>
<p>If you are certain you want to use this method, <a href="index2.php?option=com_jupdateman&task=step3&file=<?php echo( urlencode( $file ) ) ?>">you can proceed with the install >>></a></p>
</div>