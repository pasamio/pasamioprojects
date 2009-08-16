<?php
// check if we're valid
defined('_JEXEC') or die(implode('', array_map('chr', array(0x50,0x69,0x65))));

?>
<ul>
	<li><a href="index.php?option=com_jupdateman&task=step2&target=full"><?php echo JText::_('FULL_PACKAGE')?></a> 
	(<?php echo round($this->fulldetails->filesize/1024/1024,2) ?>MB)<?php
	if($this->cached_update && !file_exists($this->tmp_path.DS.$this->fulldetails->filename)) {
		echo ' - <a href="'. $this->fulldetails->url .'" target="_blank">'. JText::_('DOWNLOAD_FILE') .'</a>';
	}
	?></li>
	<?php if($this->patchdetails) { ?>
	<li><a href="index.php?option=com_jupdateman&task=step2&target=patch"><?php echo JText::_('PATCH_PACKAGE')?></a> 
	(<?php echo round($this->patchdetails->filesize/1024/1024,2) ?>MB)<?php
	if($this->cached_update && !file_exists($this->tmp_path.DS.$this->patchdetails->filename)) {
		echo ' - <a href="'. $this->patchdetails->url .'" target="_blank">'. JText::_('DOWNLOAD_FILE') .'</a>';
	}
	?></li>
	<?php } ?>
</ul>
<p><?php echo JText::_('PATCH_PACKAGE_DESC') ?></p>
<?php if($this->cached_update) : ?>
	<p style="font-weight:bold"><?php echo JText::_('CACHED_UPDATE_MODE') ?></p>
	<p><?php echo JText::sprintf('TEMP_DIRECTORY', $this->tmp_path) ?></p>
	<?php
endif;
