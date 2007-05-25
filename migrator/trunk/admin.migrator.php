<?php

/**
* @version $Id: admin.migrator.php 2006-05-29 23:00
* @package Migrator
* @copyright Copyright (C) 2006 by Mambobaer.de. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_VALID_MOS') or die('Restricted access');

$mig_version = "1.0.7";
define("MAX_LINE_LENGTH", 65536);


require_once($mainframe->getPath('class'));

migratorInclude('legacy/main.migrator');
migratorInclude('legacy/class.migrator');
migratorInclude('legacy/legacy');
migratorInclude('legacy/admin.migrator.html');

require_once ($mainframe->getPath('admin_html'));

//Get right Language file
if (file_exists($mosConfig_absolute_path . '/administrator/components/com_migrator/language/' . $mosConfig_lang . '.php')) {
	include ($mosConfig_absolute_path . '/administrator/components/com_migrator/language/' . $mosConfig_lang . '.php');
} else {
	include ($mosConfig_absolute_path . '/administrator/components/com_migrator/language/english.php');
}

if ($task <> '') {
	$func = $task;
}
elseif ($act <> '') {
	$func = $act;
} else {
	$act = mosGetParam($_REQUEST, 'act', "");
	if ($act <> '') {
		$func = $act;
	} else {
		$func = '';
	}
}


switch($func) {
	case 'testetl':
		testETL();
		break;
	case 'testenumerator':
		testEnumerator();
		break;
	case 'testtaskbuilder':
		testTaskBuilder();
		break;
	case 'doTask':
		doTask();
		break;
	case 'listplugins':
		listPlugins();
		break;
	case '3rdparty':
		displayResource('3pd');
		break;
	default:
		displayResource('default');
		break;
}

/*
switch ($func) {
	case 'showDumps' :
		showDumps($option);
		break;
	case 'makeDumps' :
		makeDumps($option);
		break;
	case 'makeDownload' :
		makeDownload($option);
		break;
	case 'showAbout' :
		showAbout($option);
		break;
	case 'doFullBackup' :
		startFullDump();
		break;
	case 'deleteFile' :
		deleteFile($option);
		break;
	case 'showInfo' :
		showInfo($option);
		break;
	case 'downloadIt' :
		downloadIt($option);
		break;
	case 'testPlugin':
		doTestPlugin();
		break;
	
	default :
		showDumps($option);
		break;
}
*/

function back() {
	echo '<p><a href="javascript:history.go(-1)">Back</a></p>';
}

function testETL() {
	migratorInclude('tests/plugin_test');
	back();
}

function testEnumerator() {
	migratorInclude('tests/enumerator_test');
	back();
}

function testTaskBuilder() {
	migratorInclude('tests/taskbuilder_test');
	back();
}

function doTask() {
	global $database;
	$tasklist = new TaskList($database);
	
}

function listPlugins() {
	$enumerator = new ETLEnumerator();
	echo '<table class="adminlist">';
	echo '<tr><th>Name</th><th>Transformation</th></tr>';
	foreach($enumerator->createPlugins() as $plugin) {
		echo '<tr><td>'. implode('</td><td>', explode(';',$plugin->toString())) .'</td></tr>';
	}
	echo '</table>';
	back();
}

?>