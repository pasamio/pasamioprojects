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

if(!count($_SESSION)) die('BLANK SESSION!');

define('__VERSION_STRING', 'Migrator 1.2');

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
$func = strtolower($func);
switch ($func) {
	case 'testbacklink':
		testBacklink();
		break;
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
	case 'purge':
		purge();
		break;
	case 'start' :
		start();
		break;
	case 'create':
		create();
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
	case 'downloadit' :
		downloadIt($option);
		break;
	case 'done':
		done($option);
		break;
	case 'install':
		install($option);
		break;
	case 'add':
		addPlugin($option);
		break;
	default :
		displayResource('default');
		break;
}

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

function purge() {
	global $database;
	$database->setQuery("TRUNCATE TABLE #__migrator_tasks");
	$database->Query();
	displayResource('purge');
	back();
}

function create() {
	HTML_migrator::formHeader();
	echo '<p>'. _BBKP_CREATE_TITLE . '</p>';
	$enumerator = new ETLEnumerator();
	echo '<table class="adminlist">';
	echo '<tr><th></th><th>'. _BBKP_NAME . '</th><th>'. _BBKP_TRANSFORMATION .'</th></tr>';
	foreach ($enumerator->createPlugins() as $plugin) {
		$name = str_replace('_etl','',strtolower(get_class($plugin)));
		
		$checked = $plugin->getSelectionPreference() ? 'checked="true"' : '';
		$cbox = '<input type="checkbox" name="pluginCheck['.$name.']" id="pluginCheck['.$name .']" '. $checked .' />';
		echo '<tr><td>'. $cbox .'</td><td>'. implode('</td><td>', explode(';', $plugin->toString())) . '</td></tr>';
	}
	echo '</table>';
	echo '<p><a href="#top" onclick="submitbutton(\'start\');">'. _BBKP_START_MIGRATION . ' &gt;&gt;</a></p>';
	back();
	HTML_migrator::formFooter('com_migrator','start'); 
}

function start() {
	global $mosConfig_absolute_path, $mosConfig_db, $mosConfig_dbprefix, $database;
	HTML_migrator::formHeader();
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
		$filename = $mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file;
		
		if(!is_writable(dirname($filename)) || !$SQLDump->createFile( $filename )) {
			displayResource('unwriteable');
			return;
		}
		
		//makeHeaderTableDef($mosConfig_db, $sql_time, & $SQLDump, count($tables), $header_def, $dump_struct);
	} else {
		$sql_file = mosGetParam($_SESSION, 'sql_file');
		if(!$SQLDump->openFile($mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/" . $sql_file)) {
			displayResource('unwriteable');
			return;
		}
	}
	$table_base_path = $mosConfig_absolute_path . "/administrator/components/com_migrator/tables/";
	if($tables = opendir($table_base_path)) {
		$tablelist = Array();
		while($entry = readdir($tables)) {
			if( $entry != "." && $entry != ".." && is_file($table_base_path.$entry)) {
				if(stristr($entry,'.sql') !== FALSE) {
					$tablelist[] = $entry;
				}
			}
		}
		sort($tablelist);
		foreach($tablelist as $entry) {
			$file = fopen($table_base_path.$entry,'r');
			$data = fread($file, filesize($table_base_path.$entry));
			$SQLDump->writeFile($data."\n");
			fclose($file);
		}

		closedir($tables);
	}
	
	$enumerator = new ETLEnumerator();
	$plugins = $enumerator->createPlugins();
	$tasks = new TaskBuilder($database, $plugins);
	$tasks->saveTaskList();
	$tasklist = new TaskList($database);
	$tasklist->listAll();
	
	$link = "index2.php?option=com_migrator&act=dotask";
	echo '<p>Note: If this page doesnt change after 5 minutes, click <div id="proceed"><a href="index2.php?option=com_migrator&act=dotask">'. _BBKP_NEXT .' &gt;&gt;&gt;</a></div></p>';
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500); progress('"._BBKP_NEXT." &gt;&gt;&gt;');</script>\n";
	//flush();
	//die();
}

function doTask() {
	global $mosConfig_absolute_path, $mosConfig_db, $mosConfig_dbprefix, $database;
	HTML_migrator::formHeader();
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
		echo '<div style="border: 5px solid red; padding: 5px;">';
		echo '<h1>'._BBKP_MIGRATIONINPROGRESS.'</h1><hr />';
		echo '<p style="font-weight:bold; color: green;">'._BBKP_MIGMESSAGE.'</p>';
		//$SQLDump->writeFile("# Starting bulk migration run\n");
		while ($task = $tasklist->getNextTask()) {
			$task->execute($SQLDump);
		}
	}
	
	$table_base_path = $mosConfig_absolute_path . "/administrator/components/com_migrator/footer/";
	if($tables = opendir($table_base_path)) {
		$tablelist = Array();
		while($entry = readdir($tables)) {
			if( $entry != "." && $entry != ".." && is_file($table_base_path.$entry)) {
				if(stristr($entry,'.sql') !== FALSE) {
					$tablelist[] = $entry;
				}
			}
		}
		sort($tablelist);
		foreach($tablelist as $entry) {
			$file = fopen($table_base_path.$entry,'r');
			$data = fread($file, filesize($table_base_path.$entry));
			$SQLDump->writeFile($data."\n");
			fclose($file);
		}

		closedir($tables);
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

	echo '<p>'. _BBKP_NOTASKSLEFT . ' <a href="index2.php?option=com_migrator&act=done">'. _BBKP_HOME .'</a></p>';
	$link = "index2.php?option=com_migrator&act=done&mosmsg=Your+SQL+Download+File+Name+is+". urlencode($sql_file);
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500);</script>\n";

}

function listPlugins() {
	$enumerator = new ETLEnumerator();
	echo '<table class="adminlist">';
	echo '<tr><th>'. _BBKP_NAME . '</th><th>'. _BBKP_TRANSFORMATION .'</th></tr>';
	foreach ($enumerator->createPlugins() as $plugin) {
		echo '<tr><td>' . implode('</td><td>', explode(';', $plugin->toString())) . '</td></tr>';
	}
	echo '</table>';
	back();
}

function done($option) {
	displayResource('done');
}

function addPlugin() {
	displayResource('add');
}

function testBacklink() {
	global $database;
	include_once(migratorBasePath() .'plugins/backlink_migration.php');
	$backlink = new Backlink_Migration_ETL($database);
	$results = $backlink->doTransformation(1,10);
	foreach($results as $result) {
		echo '<p>'. $result .'</p>';	
	}
}

function install() {
	global $mosConfig_absolute_path;
	$installbasepath = $mosConfig_absolute_path .'/administrator/components/com_migrator/';
	if(isset($_FILES['uploadfile']) && is_array($_FILES['uploadfile'])) {
		$file = $_FILES['uploadfile'];
		switch(substr($file['name'], -3)) {
			case 'sql':
				// We have a table upload
				if(move_uploaded_file($file['tmp_name'], $installbasepath.'/tables/'.$file['name']))
				mosRedirect("index2.php?option=com_migrator&act=add","Install succeeded");
				else mosRedirect("index2.php?option=com_migrator&act=add","Install failed.");
				break;
			case 'php':
				// We have a migrator plugin upload
				if(move_uploaded_file($file['tmp_name'], $installbasepath.'/plugins/'.$file['name']))
				mosRedirect("index2.php?option=com_migrator&act=add","Install succeeded");
				else mosRedirect("index2.php?option=com_migrator&act=add","Install failed.");
				break;
			default:
				// Error?
				mosRedirect("index2.php?option=com_migrator&act=add","Install failed: Unknown file.");
				return;
				break;
		}
	} else mosRedirect("index2.php?option=com_migrator&act=add","Attempt to install failed.");
}
