<?php
/** 
 * Joomla! Upgrade Helper
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$file = mosGetParam( $_GET, 'file', null );

require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
$archive = new Archive_Tar( $mosConfig_absolute_path . '/cache/' . $file );
$archive->setErrorHandling( PEAR_ERROR_PRINT );

if (!$archive->extractModify( $mosConfig_absolute_path, '' )) {
	HTML_jupgrader::showError('Failed to extract archive!');
	return false;
}

$sql = 0;
if (is_dir( $mosConfig_absolute_path .'/installation' )) {
	$sql = 1;	
}

?>

<div align="left" class="upgradebox">
<p>You have successfully upgraded your Joomla! install! Congratulations!</p>
<?php if($sql) { ?>
<p>Notice: You will need to apply any SQL patches by hand. These are located in the 'installation' directory.</p>
<?php } ?>
</div>
