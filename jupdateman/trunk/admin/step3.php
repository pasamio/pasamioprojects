<?php
/** 
 * Joomla! Upgrade Helper
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$file = JRequest::getVar( 'file', null );

/*
require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
$archive = new Archive_Tar( JPATH_SITE . '/cache/' . $file );
$archive->setErrorHandling( PEAR_ERROR_PRINT );

if (!$archive->extractModify( JPATH_SITE, '' )) {
	HTML_jupgrader::showError('Failed to extract archive!');
	return false;
}
*/

jimport('joomla.filesystem.archive');
if(!JArchive::extract(JPATH_SITE . '/cache/' . $file, JPATH_SITE)) {
	HTML_jupgrader::showError('Failed to extract archive!');
	return false;
}

$sql = 0;
if (is_dir( JPATH_SITE .'/installation' )) {
	$sql = 1;	
}

?>

<div align="left" class="upgradebox">
<p>You have successfully upgraded your Joomla! install! Congratulations!</p>
<?php if($sql) { ?>
<p>Notice: You will need to apply any SQL patches by hand. These are located in the 'installation' directory.</p>
<?php } ?>
</div>
