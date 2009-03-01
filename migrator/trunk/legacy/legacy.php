<?php
/**
 * Legacy Functions
 * 
 * Legacy functions from the main component
 * 
 * PHP4/5
 *  
 * Created on May 25, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

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
			$download = "<a href=\"index2.php?option=$option&act=downloadIt&file=" . $dump . "\">\n" .
			_BBKP_DOWNLOAD . "</a>";
			$delete = "<a href=\"javascript:if (confirm('Are you sure you want to delete $dump?')){ window.location.href='index2.php?option=$option&act=deletefile&file=$dump';}\">\n" .
			_BBKP_DEL . "</a>";
			if ((strpos($dump, "REPORT") > 0) && (strpos($dump, ".html") > 0)) {
				$info = "&nbsp;";
			} else {
				$info = "<a href=\"index2.php?option=$option&act=showInfo&file=" . $dump . "\">\n" .
				_BBKP_SQL_INFO . "</a>";
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
		makeHeaderTableDef($mosConfig_db, $sql_time, $SQLDump, count($tables), $header_def, $dump_struct);
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
			$link = "index2.php?option=$option&act=makeDumps";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500+$delaypersession);</script>\n";
			flush();
			break;
		} else {
			if (($rec_no == 0) && ($dump_struct <> ""))
				makeTableDef($mosConfig_db, $tables[$key], $SQLDump);
			if (!makeTableContent($option, $tables[$key], $rec_no, $SQLDump, $startTime))
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
			$link = "index2.php?option=$option&act=makeDumps";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500+$delaypersession);</script>\n";
			flush();
		}
	}
}

function makeReport($option) {
	createReport($option);
	$_SESSION['dump_stage'] = 4;
}

