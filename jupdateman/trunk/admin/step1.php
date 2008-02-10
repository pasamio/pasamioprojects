<?php
/**
 * Joomla! Upgrade Helper
 * Step 1 - Download XML update file and display download options
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

?>
<div align="left" class="upgradebox">
<?php
	global $_VERSION;
	$version = $_VERSION->getShortVersion();		

	$url = "http://pasamio.id.au/packages/jupgrader.xml";
	$target = $mosConfig_absolute_path . '/cache/jupgrader.xml';
	$result = downloadFile($url,$target);
	if(is_object( $result )) {
		HTML_jupgrader::showError( 'Download Failed: '. $result->message . '('. $result->number . ')' );
		return false;
	}
			
	// Yay! file downloaded! Processing time :(	
	$xmlDoc = new DOMIT_Lite_Document();
        $xmlDoc->resolveErrors( true );

        if (!$xmlDoc->loadXML( $target, false, true )) {
		HTML_jupgrader::showError( 'Parsing XML Document Failed!</p>' );
		return false;
	}
	
	$root = &$xmlDoc->documentElement;

	if ($root->getTagName() != 'jupgrader') {
		HTML_jupgrader::showError( 'Parsing XML Document Failed: Not a JUpgrader definition file!</p>' );
		return false;
	}
	$latest = $root->getAttribute( 'release' );
	if($latest == $version) {
		echo "<p>No updates were found. Please check again later or watch <a href='http://www.joomla.org' target='_blank'>www.joomla.org</a></p>";
		return true;
	}
	echo "<p>You are currently running $version. The latest release is currently $latest. Please select a download:</p>";
	$fulldownload = '';
	$patchdownload = '';
	
	// Get the full package
	$fullpackage  = $root->getElementsByPath( 'fullpackage', 1 );
	$fulldownload = $fullpackage->getAttribute( 'url' );
	$fullfilename = $fullpackage->getAttribute( 'filename' );
	$fullfilesize = $fullpackage->getAttribute( 'filesize' );
	
	// Find the patch package
	$patches_root = $root->getElementsByPath( 'patches', 1 );
	if (!is_null( $patches_root ) ) {
		// Patches :D
		if($patches_root->hasChildNodes()) {
			// Many patches! :D
			$patches = $patches_root->childNodes;
			foreach($patches as $patch) {
				if ($patch->getAttribute( 'version' ) == $version) {
					$patchdownload = $patch->getAttribute( 'url' );
					$patchfilename = $patch->getAttribute( 'filename' );
					$patchfilesize = $patch->getAttribute( 'filesize' );
					break;
				}			
			}
					
		}
	}
	?>
	<ul>
	<li><a href="index2.php?option=com_jupdateman&task=step2&url=<?php echo( urlencode( $fulldownload ) ) ?>&filename=<?php echo( urlencode( $fullfilename ) ) ?>&filesize=<?php echo $fullfilesize ?>">Full Package</a> (<?php echo round($fullfilesize/1024/1024,2) ?>MB)</li>
		<?php if($patchdownload) { ?>
	<li><a href="index2.php?option=com_jupdateman&task=step2&url=<?php echo( urlencode( $patchdownload ) ) ?>&filename=<?php echo( urlencode( $patchfilename ) ) ?>&filesize=<?php echo $patchfilesize ?>">Patch Package</a> (<?php echo round($patchfilesize/1024/1024,2) ?>MB)</li>
		<?php } ?>
	</ul>
	<p>Note: Patch package only contains changed files and should be fine for most upgrades. Major upgrades (e.g. 1.0.x to 1.1) will probably require a full package.</p>
</div>