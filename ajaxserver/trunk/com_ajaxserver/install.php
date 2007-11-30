<?php

/**
* Installer file
* Creates the AJAX Mambot Directory
* @package AJAX-Server
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2007 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

function com_install() {
	global $mosConfig_absolute_path;
	@mkdir($mosConfig_absolute_path . '/mambots/ajax');
} 
