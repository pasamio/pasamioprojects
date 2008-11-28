<?php
/**
* Installer file
* Creates the AJAX Mambot
* @package Selected-Newsflash
*/

function com_install() {
	global $mosConfig_absolute_path;
	mkdir($mosConfig_absolute_path . '/mambots/ajax');
} 
