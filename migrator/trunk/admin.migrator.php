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

define("MAX_LINE_LENGTH", 65536);
$max_php_run = ini_get("max_execution_time");
if ($max_php_run <> 0) {
	$run_time = intval($max_php_run / 2);
} else {
	$run_time = 15;
}
$startTime = mosProfiler :: getmicrotime();

require_once ($mainframe->getPath('class'));

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

switch ($func) {
	case 'testetl' :
		testETL();
		break;
	case 'testenumerator' :
		testEnumerator();
		break;
	case 'testtaskbuilder' :
		testTaskBuilder();
		break;
	case 'testtasklist' :
		testTaskList();
		break;
	case 'start' :
		start();
		break;
	case 'dotask' :
		doTask();
		break;
	case 'listplugins' :
		listPlugins();
		break;
	case '3rdparty' :
		displayResource('3pd');
		break;
	// Legacy Functions
	case 'showdumps' :
		showDumps($option);
		break;
	case 'deletefile' :
		deleteFile($option);
		break;		
	case 'downloadIt' :
		downloadIt($option);
		break;		
	default :
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

function testTaskList() {
	migratorInclude('tests/tasklist_test');
	back();
}

function start() {
	global $mosConfig_absolute_path, $mosConfig_db, $mosConfig_dbprefix, $database;
	$enumerator = new ETLEnumerator();
	$plugins = $enumerator->createPlugins();
	$tasks = new TaskBuilder($database, $plugins);
	$tasks->saveTaskList();
	$tasklist = new TaskList($database);
	$tasklist->listAll();
	$SQLDump = new JFiler(1);
	if (!isset ($_SESSION['sql_file_time'])) {
		$_SESSION['sql_file_time'] = time();
		$sql_time = $_SESSION['sql_file_time'];
	} else {
		$sql_time = $_SESSION['sql_file_time'];
	}
	if (!isset ($_SESSION['sql_file'])) {
		$sql_file = $mosConfig_db . "_" . strftime("%Y%m%d_%H%M%S", $sql_time) . '.sql';
		$_SESSION['sql_file'] = $sql_file;
		$SQLDump->createFile($mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file);
		//makeHeaderTableDef($mosConfig_db, $sql_time, & $SQLDump, count($tables), $header_def, $dump_struct);
	} else {
		$sql_file = mosGetParam($_SESSION, 'sql_file');
		$SQLDump->openFile($mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file);
	}
	$link = "index2.php?option=com_migrator&act=dotask";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500);</script>\n";
	flush();
	die();
}

function doTask() {
	global $mosConfig_absolute_path, $mosConfig_db, $mosConfig_dbprefix, $database;
	$tasklist = new TaskList($database);
	if ($tasklist->countTasks()) {
		$SQLDump = new JFiler(1);
		if (!isset ($_SESSION['sql_file_time'])) {
			$_SESSION['sql_file_time'] = time();
			$sql_time = $_SESSION['sql_file_time'];
		} else {
			$sql_time = $_SESSION['sql_file_time'];
		}
		if (!isset ($_SESSION['sql_file'])) {
			$sql_file = $mosConfig_db . "_" . strftime("%Y%m%d_%H%M%S", $sql_time) . '.sql';
			$_SESSION['sql_file'] = $sql_file;
			$SQLDump->createFile($mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file);
			//makeHeaderTableDef($mosConfig_db, $sql_time, & $SQLDump, count($tables), $header_def, $dump_struct);
		} else {
			$sql_file = mosGetParam($_SESSION, 'sql_file');
			$SQLDump->openFile($mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file);
		}

		while ($task = $tasklist->getNextTask()) {
			$task->execute($SQLDump);
		}
	}
	unset ($_SESSION['dump_stage']);
	unset ($_SESSION['sql_file_time']);
	unset ($_SESSION['prev_time']);
	unset ($_SESSION['sql_file_time']);
	unset ($_SESSION['tables']);
	unset ($_SESSION['table']);
	unset ($_SESSION['sql_file']);
	unset ($_SESSION['rec_no']);
	unset ($_SESSION['start_time']);
	echo '<p>Done, there are no tasks left. <a href="index2.php?option=com_migrator">Home</a></p>';
	$link = "index2.php?option=com_migrator";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500);</script>\n";

}

function listPlugins() {
	$enumerator = new ETLEnumerator();
	echo '<table class="adminlist">';
	echo '<tr><th>Name</th><th>Transformation</th></tr>';
	foreach ($enumerator->createPlugins() as $plugin) {
		echo '<tr><td>' . implode('</td><td>', explode(';', $plugin->toString())) . '</td></tr>';
	}
	echo '</table>';
	back();
}
?>