<?php
/**
 * Joomla! Upgrade Helper
 * Step 1 - Download XML update file and display download options
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
//jimport('domit.xml_domit_lite_parser'); // this is deprecated, need to update!
?>
<div align="left" class="upgradebox">
<?php
	global $_VERSION;
	$version = $_VERSION->getShortVersion();		

	$url = "http://pasamio.id.au/packages/jupgrader.xml";
	$url = "http://jsitepoint.com/update/packages/joomla/update.xml";
	$target = $mosConfig_absolute_path . '/cache/jupgrader.xml';
	$result = downloadFile($url,$target);
	if(is_object( $result )) {
		HTML_jupgrader::showError( 'Download Failed: '. $result->message . '('. $result->number . ')' );
		return false;
	}
			
	// Yay! file downloaded! Processing time :(	
	//$xmlDoc = new DOMIT_Lite_Document();
    //    $xmlDoc->resolveErrors( true );
	$xmlDoc =& JFactory::getXMLParser(); //  domit 1.5 style
	$xmlDoc = new JSimpleXML();

    if (!$xmlDoc->loadFile( $target )) {
		HTML_jupgrader::showError( 'Parsing XML Document Failed!</p>' );
		return false;
	}
	
	//$root = &$xmlDoc->documentElement;
	$root = &$xmlDoc->document;

	if ($root->name() != 'jupgrader') {
		HTML_jupgrader::showError( 'Parsing XML Document Failed: Not a JUpgrader definition file!</p>' );
		return false;
	}
	$rootattributes = $root->attributes();
	$latest = $rootattributes['release'];
	if($latest == $version) {
		echo "<p>No updates were found.</p><br /><br /><p>Please check again later or watch <a href='http://www.joomla.org' target='_blank'>www.joomla.org</a></p>";
		echo '</div>';
		return true;
	} elseif(version_compare($latest, $version, '<')) {
		echo "<p>You are running a greater version of Joomla! than what is available for download.</p><br /><br />";
		echo "<p>Please check <a href='http://www.joomla.org' target='_blank'>www.joomla.org</a> for release information.</p>";
		echo '</div>';
		return true;
	}
	echo "<p>You are currently running $version. The latest release is currently $latest. Please select a download:</p>";
	$fulldownload = '';
	$patchdownload = '';
	
	// Get the full package
	$fullpackage  = $root->getElementByPath( 'fullpackage', 1 );
	$fullpackageattr = $fullpackage->attributes();
	$fulldownload = $fullpackageattr['url'];
	$fullfilename = $fullpackageattr['filename'];
	$fullfilesize = $fullpackageattr['filesize'];
	
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
					
					$patchdownload = $patchattr['url'];
					$patchfilename = $patchattr['filename'];
					$patchfilesize = $patchattr['filesize'];
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
	<p>Note: Patch package only contains changed files and should be fine for most upgrades. Major upgrades (e.g. 1.5.x to 1.6) will probably require a full package.</p>
</div>