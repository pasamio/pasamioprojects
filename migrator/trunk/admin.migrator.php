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

require_once ($mainframe->getPath('admin_html'));
require_once ($mosConfig_absolute_path . "/administrator/components/com_migrator/class.migrator.php");
require_once ($mosConfig_absolute_path . "/administrator/components/com_migrator/main.migrator.php");

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

function migratorInclude($file) {
	global $mosConfig_absolute_path;
	$file = $mosConfig_absolute_path . '/administrator/components/com_migrator/' . $file . '.php';
	if(file_exists($file)) {
		require_once($file);
	}
}

function doTestPlugin() {
	migratorInclude('tests/plugin_test');
}

function showAbout($option) {
	HTML_migrator :: showAbout($option);
}

function downloadIt($option) {
	global $mosConfig_absolute_path;

	$file = mosGetParam($_REQUEST, 'file', '');

	if ($file <> "") {
		$downloader = new Dump_File($mosConfig_absolute_path . "/administrator/components/$option/dumps", $file);
		$downloader->download();
		die;
	}
}

function showDumps($option) {
	global $mosConfig_absolute_path, $mosConfig_db, $run_time;

	unset ($_SESSION['dump_stage']);
	unset ($_SESSION['sql_file_time']);
	unset ($_SESSION['prev_time']);
	unset ($_SESSION['sql_file_time']);
	unset ($_SESSION['tables']);
	unset ($_SESSION['table']);
	unset ($_SESSION['sql_file']);
	unset ($_SESSION['rec_no']);
	unset ($_SESSION['start_time']);
	$list = "";
	$data_path = $mosConfig_absolute_path . "/administrator/components/$option/dumps";
	$dumps = readBackupDir($data_path, true);
	if (count($dumps) > 0) {
		$z_temp = "";
		foreach ($dumps as $dump) {
			$download = "<a href=\"index2.php?option=$option&task=downloadIt&file=" . $dump . "\">\n" .
			"<img src=\"components/$option/images/download.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . _BBKP_DOWNLOAD . "\" title=\"" . _BBKP_DOWNLOAD . "\" /></a>";
			$delete = "<a href=\"javascript:if (confirm('Are you sure you want to delete $dump?')){ window.location.href='index2.php?option=$option&task=deleteFile&file=$dump';}\">\n" .
			"<img src=\"components/$option/images/delete.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . _BBKP_DEL . "\" title=\"" . _BBKP_DEL . "\" /></a>";
			if ((strpos($dump, "REPORT") > 0) && (strpos($dump, ".html") > 0)) {
				$info = "&nbsp;";
			} else {
				$info = "<a href=\"index2.php?option=$option&task=showInfo&file=" . $dump . "\">\n" .
				"<img src=\"components/$option/images/sql_info.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . _BBKP_SQL_INFO . "\" title=\"" . _BBKP_SQL_INFO . "\" /></a>";
			}

			$x_temp = substr($dump, strlen($mosConfig_db) + 1, 15);
			$y_temp = mktime(substr($x_temp, 9, 2), substr($x_temp, 11, 2), substr($x_temp, 13, 2), substr($x_temp, 4, 2), substr($x_temp, 6, 2), substr($x_temp, 0, 4));
			if ($z_temp <> $y_temp) {
				$list .= "<tr class=\"row0\">\n" .
				"  <td colspan=\"5\"><b>SQL Dump Set from " . strftime(_MIG_DATE_FORMAT_LC2, $y_temp) . "</b></td>\n" .
				"</tr>\n" .
				"<tr class=\"row0\">\n" .
				"  <td>&nbsp;&nbsp;&nbsp;" . $dump . "</td>\n" .
				"  <td style=\"text-align: right; white-space: nowrap;\">" . mosGetSizes(filesize($data_path . "/" . $dump)) . "</td>\n" .
				"  <td width=\"1%\">" . $delete . "</td>\n" .
				"  <td width=\"1%\">" . $info . "</td>\n" .
				"  <td width=\"1%\">" . $download . "</td>\n" .
				"</tr>\n";
				$z_temp = $y_temp;
			} else {
				$list .= "<tr class=\"row0\">\n" .
				"  <td>&nbsp;&nbsp;&nbsp;" . $dump . "</td>\n" .
				"  <td style=\"text-align: right;\">" . mosGetSizes(filesize($data_path . "/" . $dump)) . "</td>\n" .
				"  <td width=\"1%\">" . $delete . "</td>\n" .
				"  <td width=\"1%\">" . $info . "</td>\n" .
				"  <td width=\"1%\">" . $download . "</td>\n" .
				"</tr>\n";
			}
		}
	} else {
		$list = "<tr class=\"row0\">\n" .
		"  <td style=\"text-align: center; white-space: nowrap;\" colspan=\"5\">No files available</td>\n" .
		"</tr>\n";
	}
	HTML_migrator :: showDumps($option, $list);
}

function showInfo($option) {
	global $mosConfig_absolute_path;

	$data_path = $mosConfig_absolute_path . "/administrator/components/$option/dumps";
	$SQLDump = new JFiler();
	$filename = $data_path . "/" . $_REQUEST['file'];

	$buffer = "";
	$info = "<div style=\"border: 1px solid #cccccc; padding: 5px; margin: 5px; font-family: COURIER NEW;\">";
	$info .= $SQLDump->getFileInfo($data_path . "/" . $_REQUEST['file']);
	$info .= "</div>";

	HTML_migrator :: showInfo($option, $info);
}

function deleteFile($option) {
	global $mosConfig_absolute_path;

	$data_path = $mosConfig_absolute_path . "/administrator/components/com_migrator/dumps/";
	$file = mosGetParam($_REQUEST, 'file', "");
	unlink($data_path . $file);

	mosRedirect("index2.php?option=$option", $file . " deleted!");
}

function makeDumps($option) {
	global $tables, $mosConfig_absolute_path, $mosConfig_db, $mosConfig_dbprefix, $database, $autoinc, $drop, $exists, $sql_compat, $gzip, $fullinserts, $run_time, $delaypersession, $core_tables, $exclude_tables;

	$tstart = mosProfiler :: getmicrotime();
	$SQLDump = new JFiler($gzip);

	if (!isset ($_SESSION['dump_stage'])) {
		$_SESSION['dump_stage'] = 0;
		$dump_stage = 0;
	} else {
		$dump_stage = $_SESSION['dump_stage'];
	}
	switch ($dump_stage) {
		case 0 :
			if ((!isset ($_SESSION['tables'])) && (!isset ($_SESSION['table']))) {
				$sql = "SHOW TABLE STATUS FROM `" . $mosConfig_db . "` LIKE '" . $mosConfig_dbprefix . "%'";
				$database->setQuery($sql);
				$table_lists = $database->loadObjectList();
				$i = 0;
				foreach ($table_lists as $table) {
					$_SESSION['tables'][] = $table->Name;
				}
			}
			$dump_ext = "_FULL.sql";
			$header_def = "FULL DUMP";
			$dump_struct = "x";
			break;
		case 1 :
			if ((!isset ($_SESSION['tables'])) && (!isset ($_SESSION['table']))) {
				$_SESSION['tables'] = $core_tables;
			}
			$dump_ext = "_MIGRATION.sql";
			$header_def = "MIGRATION DUMP";
			$dump_struct = "";
			break;
		case 2 :
			if ((!isset ($_SESSION['tables'])) && (!isset ($_SESSION['table']))) {
				$sql = "SHOW TABLE STATUS FROM `" . $mosConfig_db . "` LIKE '" . $mosConfig_dbprefix . "%'";
				$database->setQuery($sql);
				$table_lists = $database->loadObjectList();
				$i = 0;
				foreach ($table_lists as $table) {
					if ((!in_array($table->Name, $core_tables)) && (!in_array($table->Name, $exclude_tables)))
						$_SESSION['tables'][] = $table->Name;
				}
			}
			$dump_ext = "_PARTYTHIRD.sql";
			$header_def = "THIRD PARTY DUMP";
			$dump_struct = "";
			break;
		case 3 :
			makeReport($option);
			unset ($_SESSION['sql_file_time']);
			mosRedirect("index2.php?option=$option");
			return;
			break;
	}

	if (!isset ($_POST['tables'])) {
		$tables = mosGetParam($_SESSION, 'tables', '');
	} else {
		$tables = mosGetParam($_POST, 'tables', '');
		$_SESSION['tables'] = $tables;
	}

	if (!isset ($_SESSION['start_time'])) {
		$_SESSION['start_time'] = $tstart;
	} else {
		$tstart = $_SESSION['start_time'];
	}

	if (isset ($_SESSION['table'])) {
		$table = $_SESSION['table'];
		$key = array_search($table, $tables);
	} else {
		$key = 0;
	}

	if (isset ($_SESSION['rec_no'])) {
		$rec_no = mosGetParam($_SESSION, 'rec_no', '');
	} else {
		$rec_no = 0;
	}

	if (!isset ($_SESSION['sql_file_time'])) {
		$_SESSION['sql_file_time'] = time();
		$sql_time = $_SESSION['sql_file_time'];
	} else {
		$sql_time = $_SESSION['sql_file_time'];
	}

	if (!isset ($_SESSION['sql_file'])) {
		$sql_file = $mosConfig_db . "_" . strftime("%Y%m%d_%H%M%S", $sql_time) . $dump_ext;
		$_SESSION['sql_file'] = $sql_file;
		$SQLDump->createFile($mosConfig_absolute_path . "/administrator/components/$option/dumps/" . $sql_file);
		makeHeaderTableDef($mosConfig_db, $sql_time, & $SQLDump, count($tables), $header_def, $dump_struct);
	} else {
		$sql_file = mosGetParam($_SESSION, 'sql_file');
		$SQLDump->openFile($mosConfig_absolute_path . "/administrator/components/$option/dumps/" . $sql_file);
	}

	$startTime = mosProfiler :: getmicrotime();
	while ($key < count($tables)) {
		$checkTime = mosProfiler :: getmicrotime();
		if (($checkTime - $startTime) >= $run_time) {
			$_SESSION['table'] = $tables[$key -1];
			$_SESSION['rec_no'] = 0;
			HTML_migrator :: showProcess($tables[$key], $rec_no, $sql_file, $key, count($tables), $header_def);
			$link = "index2.php?option=$option&task=makeDumps";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500+$delaypersession);</script>\n";
			flush();
			break;
		} else {
			if (($rec_no == 0) && ($dump_struct <> ""))
				makeTableDef($mosConfig_db, $tables[$key], & $SQLDump);
			if (!makeTableContent($option, $tables[$key], & $rec_no, & $SQLDump, $startTime))
				break;
			$key++;
		}
	}

	if ($key < count($tables)) {
		HTML_migrator :: showProcess($tables[$key], $rec_no, $sql_file, $key, count($tables), $header_def);
		flush();
	} else {
		$SQLDump->closeFile();
		switch ($dump_stage) {
			case 0 :
				$_SESSION['dump_stage'] = 1;
				break;
			case 1 :
				$_SESSION['dump_stage'] = 2;
				break;
			case 2 :
				$_SESSION['dump_stage'] = 3;
				break;
		}
		if ($dump_stage <= 3) {
			//if ($key > count($tables)) $key = 0;
			$key = 0;
			HTML_migrator :: showProcess($tables[$key], $rec_no, $sql_file, $key, count($tables), $header_def);
			unset ($_SESSION['tables']);
			unset ($_SESSION['table']);
			unset ($_SESSION['sql_file']);
			unset ($_SESSION['rec_no']);
			unset ($_SESSION['start_time']);
			$link = "index2.php?option=$option&task=makeDumps";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500+$delaypersession);</script>\n";
			flush();
		}
	}
}

function makeReport($option) {
	createReport($option);
	$_SESSION['dump_stage'] = 4;
}
?>