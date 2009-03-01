<?php
/**
* @version $Id: main.migrator.php 2006-05-25 23:00
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

  $max_php_run = ini_get("max_execution_time");
  if ($max_php_run <> 0){
     $run_time = intval($max_php_run/2);
  }else{
     $run_time = 15;
  }
//  $run_time = 15;
  $autoinc         = 1;
  $drop            = 1;
  $exists          = 1;
  $sql_compat      = "MYSQL40";
  $gzip            = 1;
  $full_inserts    = 1;
  $delaypersession = 5;
  $backup_path     = "/administrator/components/com_migrator/dumps/";
  DEFINE('_MIG_DATE_FORMAT_LC2', "%d.%m.%Y %H:%M");
  /*$core_tables = array ($mosConfig_dbprefix."banner",
                        $mosConfig_dbprefix."bannerclient",
                        $mosConfig_dbprefix."bannerfinish",
                        $mosConfig_dbprefix."categories",
                        $mosConfig_dbprefix."contact_details",
                        $mosConfig_dbprefix."content",
                        $mosConfig_dbprefix."content_frontpage",
                        $mosConfig_dbprefix."content_rating",
                        $mosConfig_dbprefix."core_acl_aro",
                        $mosConfig_dbprefix."core_acl_groups_aro_map",
                        $mosConfig_dbprefix."core_log_items",
                        $mosConfig_dbprefix."core_log_searches",
                        $mosConfig_dbprefix."menu",
                        $mosConfig_dbprefix."messages",
                        $mosConfig_dbprefix."messages_cfg",
                        $mosConfig_dbprefix."modules",
                        $mosConfig_dbprefix."newsfeeds",
                        $mosConfig_dbprefix."poll_data",
                        $mosConfig_dbprefix."poll_date",
                        $mosConfig_dbprefix."poll_menu",
                        $mosConfig_dbprefix."polls",
                        $mosConfig_dbprefix."sections",
                        $mosConfig_dbprefix."users",
                        $mosConfig_dbprefix."weblinks"
                        );
  $exclude_tables = array ($mosConfig_dbprefix."components",
                           $mosConfig_dbprefix."core_acl_aro_groups",
                           $mosConfig_dbprefix."core_acl_aro_sections",
                           $mosConfig_dbprefix."groups",
                           $mosConfig_dbprefix."mambots",
                           $mosConfig_dbprefix."modules_menu",
                           $mosConfig_dbprefix."session",
                           $mosConfig_dbprefix."stats_agents",
                           $mosConfig_dbprefix."template_positions",
                           $mosConfig_dbprefix."templates_menu",
                           $mosConfig_dbprefix."usertypes",
                           $mosConfig_dbprefix."weblinks"
                           );
	*/
  function mosGetMySQLVersionShort(){
    if (!function_exists("mysql_get_server_info")){
       $mysql_s=PMBP_I_NO_RES;
    }else{
       $mysql_s=@mysql_get_server_info();
    }
    $mysql_s = substr(str_replace(".", "", $mysql_s)."00000", 0, 5);
    return $mysql_s;
  }

  function mosGetSizes($size) {
    if ($size<1024)
       $size=number_format(Round($size,3), 0, ',', '.')." B";
    elseif ($size < 1048576)
       $size=number_format(Round($size/1024,3), 2, ',', '.')." KB";
    elseif ($size < 1073741824)
       $size=number_format(Round($size/1048576,3), 2, ',', '.')." MB";
    elseif (1073741824 < $size)
       $size=number_format(Round($size/1073741824,3), 2, ',', '.')." GB";
    elseif (1099511627776 < $size)
       $size=number_format(Round($size/1099511627776,3), 2, ',', '.')." TB";
    return $size;
  }

  function readBackupDir($dirname, $sort = false){
    $data_path = "";
    $filelist  = array();
    if(is_dir($dirname)){
       if ($dirhandle = opendir($dirname)){
          while (false !== ($dirfile = readdir($dirhandle))){
                if ($dirfile != "." && $dirfile != ".."){
                	if($dirfile == 'index.html') continue; // ignore the index.html file
                   $path_parts = pathinfo($data_path."/".$dirfile);
                   $file_ext = strtolower($path_parts["extension"]);
                   if (($file_ext == "sql") || ($file_ext == "html") || ($file_ext == "gz") || ($file_ext == "dat")) $filelist[] = $dirfile;
                }
          }
          if ((count($filelist) > 0) && ($sort)) rsort($filelist);
          return $filelist;
       }
    }else{
       mkdir($dirname, 0777);
    }
  }

  function makeHeaderTableDef($item, $bkp_time, &$SQLDump, $tab_count, $dump_type, $tab_struct){
    global $autoinc, $drop, $exists, $sql_compat, $gzip, $full_inserts, $mig_version;
    $crlf   = "\r\n";
    $header = "";
    if ($drop) $xdrop       = "x";
    if ($exists) $xexists   = "x";
    if ($autoinc) $xautoinc = "x";
    $header.= "# ===============================================================$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_HEAD_1."$crlf";
    $header.= "# Version: ".$mig_version."$crlf";
    $header.= "# http://www.mambobaer.de$crlf";
    $header.= "# "._BBKP_HEAD_4."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_HEAD_2." : $item $crlf";
    $header.= "# "._BBKP_HEAD_3." : ".strftime(_DATE_FORMAT_LC3, $bkp_time)."$crlf";
    $header.= "# "._BBKP_HEAD_7."           : $tab_count $crlf";
    $header.= "# "._BBKP_HEAD_9."         : $dump_type $crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_ENVIRONMENT."$crlf";
    $header.= "#   "._BBKP_SQL_SERVER."                : ".@mysql_get_server_info()."$crlf";
    $header.= "#   "._BBKP_SQL_CLIENT."                : ".@mysql_get_client_info()."$crlf";
    $header.= "#   "._BBKP_PHP_VERSION."                 : ".phpversion()."$crlf";
    $header.= "# $crlf";
    $header.= "# "._BBKP_SETTINGS."$crlf";
    $header.= "#   "._BBKP_DROP.": [".$xdrop."]$crlf";
    $header.= "#   "._BBKP_EXISTS.": [".$xexists."]$crlf";
    $header.= "#   "._BBKP_DB_AUTO_INC.": [".$xautoinc."]$crlf";
    $header.= "#   "._BBKP_DB_STRUCT.": [".$tab_struct."]$crlf";
    $header.= "#   "._BBKP_DB_COMP.": ".$sql_compat."$crlf";
    $header.= "# $crlf";
    $header.= "# ===============================================================$crlf$crlf";
    $SQLDump->writeFile($header);
  }

  function makeTableDef($base, $table, &$SQLDump){
    global $database, $autoinc, $drop, $exists, $sql_compat;
    $crlf="\r\n";

    $create = "";
    if ((mosGetMySQLVersionShort() >= 40100) && ($sql_compat != 'NONE')) {
        $database->setQuery('SET @@SESSION.SQL_MODE="'.$sql_compat.'"');
        $database->Query();
    }

    $result = $database->setQuery("SHOW CREATE TABLE `".$table."`");
    $rows   = $database->loadrow();
    $create_query = $rows[1];

    if (strpos($create_query, "(\r\n ")) {
       $create_query = str_replace("\r\n", $crlf, $create_query);
    }elseif (strpos($create_query, "(\n ")) {
       $create_query = str_replace("\n", $crlf, $create_query);
    }elseif (strpos($create_query, "(\r ")) {
       $create_query = str_replace("\r", $crlf, $create_query);
    }

    if ($drop != "") {
       $create = "DROP TABLE IF EXISTS `".$table."`;".$crlf;
    }else{
       $create = "";
    }
    if (!strpos($create_query, "CREATE TABLE")){
      if ($exists != "") {
         $create.= str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $create_query);
      }else{
         $create.= $create_query;
      }
    }else{
       $create.= $create_query;
    }

    $result = $database->setQuery("SHOW TABLE STATUS FROM `".$base."` LIKE '".$table."'");
    $stats  = $database->loadObjectList();
    $stat   = $stats[0];
    $auto_inc = $stat->Auto_increment;
    if (($stat->Auto_increment != "") && ($autoinc)){
       $create.= " AUTO_INCREMENT=".$stat->Auto_increment.";".$crlf.$crlf;
    }else{
       $create.=";".$crlf.$crlf;
    }

    $header = "# ===============================================================$crlf"
             ."# $crlf"
             ."# "._BBKP_HEAD_5." `$table` $crlf"
             ."# $crlf"
             ."# ===============================================================$crlf$crlf";
    $SQLDump->writeFile($header.$create);
  }

  function makeTableContent($option, $table, &$rec_no, &$SQLDump, $startTime){
    global $database, $run_time, $full_inserts, $delaypersession;

    $crlf ="\r\n";
    $num  = "";

    if ($rec_no == 0){
       $header = "# ===============================================================$crlf"
                ."# $crlf"
                ."# "._BBKP_HEAD_6." `$table $crlf"
                ."# $crlf"
                ."# ===============================================================$crlf$crlf";
       $SQLDump->writeFile($header);
    }

    $sql = "SELECT * FROM ".$table;
    $database->setQuery($sql);
    $result = $database->query();

    if ($rec_no <> 0){
        $rec_i = 1;
        while ($row = mysql_fetch_assoc($result)){
              if ($rec_i == $rec_no) break;
              $rec_i++;
        }
    }

    while ($result && $row = mysql_fetch_assoc($result)){
          $rec_no++;

          if ($full_inserts) {
             $item_list = $num."(";
             foreach ($row as $col => $value) {
                     $item_list.= "`".mysql_escape_string($col)."`, ";
             }
             $item_list = substr($item_list, 0, -2);
             $item_list.= ")";
             $insert = "INSERT INTO `$table` $item_list VALUES (";
          } else{
             $insert = "INSERT INTO `$table` VALUES (";
          }
          $data = "";
          foreach ($row as $value) {
                if (!isset($value)) {
                   $data.= " NULL,";
                } elseif ($value != "") {
                   $data.= " '".mysql_escape_string($value)."',";
                } else {
                   $data.= " '',";
                }
          }
          $insert.= ereg_replace(",$", "", $data);
          $insert.= ");".$crlf;
          $SQLDump->writeFile($insert);
          $checkTime = mosProfiler::getmicrotime();
          if (($checkTime - $startTime) >= $run_time){
             $_SESSION['table']  = $table;
             $_SESSION['rec_no'] = $rec_no;
             $link = "index2.php?option=$option&act=makeDumps";
             echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"".$link."\";',500+$delaypersession);</script>\n";
             flush();
             return false;
          }
    }
    $SQLDump->writeFile($crlf);
    $_SESSION['rec_no'] = 0;
    $rec_no = 0;
    return true;
  }

  function createReport($option){
    global $mosConfig_db, $mosConfig_dbprefix, $mosConfig_sitename, $mosConfig_absolute_path, $backup_path, $version, $_VERSION,
           $database, $core_tables, $exclude_tables, $mig_version;

    $sql_file_time = strftime("%Y%m%d_%H%M%S", $_SESSION['sql_file_time']);
    $full_size = filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_MIGRATION.sql.gz")+
                 filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_FULL.sql.gz")+
                 filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_PARTYTHIRD.sql.gz");

    $sql         = "SHOW TABLE STATUS FROM `".$mosConfig_db."` LIKE '".$mosConfig_dbprefix."%'";
    $result      = $database->setQuery($sql);
    $table_infos = $database->loadObjectList();
    if (is_array($table_infos)){
       $tab_header = "<tr>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">#</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">Table Name</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">Rows</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">Size</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">Auto Inc.</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">Created</td>\r\n"
                    ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">Checked</td>\r\n"
                    ."</tr>\r\n";
       $full_table_info = $tab_header;
       $cont_table_info = $tab_header;
       $tpct_table_info = $tab_header;
       $i_cont          = 1;
       $i_full          = 1;
       $i_tpco          = 1;
       foreach($table_infos as $table_info){
              if ($table_info->Check_time != ""){
                 $check_time = strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table_info->Check_time));
              }else{
                 $check_time = "----";
              }

              if (in_array($table_info->Name, $core_tables)){
                 $cont_table_info.= "<tr>\r\n"
                                   ."  <td style=\"width: 1%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".$i_cont++."</td>\r\n"
                                   ."  <td style=\"white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$table_info->Name."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Rows, 0, ',', '.')."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".mosGetSizes($table_info->Data_length)."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Auto_increment, 0, ',', '.')."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table_info->Create_time))."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$check_time."</td>\r\n"
                                   ."</tr>\r\n";
                 $sum_cont_rows = $sum_cont_rows + $table_info->Rows;
                 $sum_cont_size = $sum_cont_size + $table_info->Data_length;
              }

              $full_table_info.= "<tr>\r\n"
                                ."  <td style=\"width: 1%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".$i_full++."</td>\r\n"
                                ."  <td style=\"white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$table_info->Name."</td>\r\n"
                                ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Rows, 0, ',', '.')."</td>\r\n"
                                ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".mosGetSizes($table_info->Data_length)."</td>\r\n"
                                ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Auto_increment, 0, ',', '.')."</td>\r\n"
                                ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table_info->Create_time))."</td>\r\n"
                                ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$check_time."</td>\r\n"
                                ."</tr>\r\n";
              $sum_full_rows = $sum_full_rows + $table_info->Rows;
              $sum_full_size = $sum_full_size + $table_info->Data_length;

              if ((!in_array($table_info->Name, $core_tables)) && (!in_array($table_info->Name, $exclude_tables))){
                 $tpct_table_info.= "<tr>\r\n"
                                   ."  <td style=\"width: 1%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".$i_tpco++."</td>\r\n"
                                   ."  <td style=\"white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$table_info->Name."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Rows, 0, ',', '.')."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".mosGetSizes($table_info->Data_length)."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: right; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".number_format($table_info->Auto_increment, 0, ',', '.')."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF; padding-right: 8px;\">".strftime(_BBKP_DATE_FORMAT_LC2, strtotime($table_info->Create_time))."</td>\r\n"
                                   ."  <td style=\"width: 5%; white-space: nowrap; text-align: left; border-top: 1px solid #CFCFCF;\">".$check_time."</td>\r\n"
                                   ."</tr>\r\n";
                 $sum_tpco_rows = $sum_tpco_rows + $table_info->Rows;
                 $sum_tpco_size = $sum_tpco_size + $table_info->Data_length;
              }
       }
       $cont_table_info.= "<tr>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: right; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".number_format($sum_cont_rows, 0, ',', '.')."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: right; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".mosGetSizes($sum_cont_size)."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."</tr>\r\n";
       $full_table_info.= "<tr>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: right; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".number_format($sum_full_rows, 0, ',', '.')."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: right; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".mosGetSizes($sum_full_size)."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."</tr>\r\n";
       $tpct_table_info.= "<tr>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: left; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".number_format($sum_tpco_rows, 0, ',', '.')."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF; padding-right: 8px;\">".mosGetSizes($sum_tpco_size)."</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."  <td style=\"white-space: nowrap; text-align: center; font-weight: bold; background: #CFCFCF;\">&nbsp;</td>\r\n"
                         ."</tr>\r\n";

    }

    $content = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\r\n"
              ."<html>\r\n"
              ."  <head>\r\n"
              ."    <title>Report Data Migration for Joomla!</title>\r\n"
              ."    <meta name=\"description\" content=\"Data Migration for Joomla! 1.5\">\r\n"
              ."    <meta name=\"author\" content=\"Data Migration for Joomla! 1.5\">\r\n"
              ."  </head>\r\n"
              ."  <style type=\"text/css\">\r\n"
              ."<!--\r\n"
              ."body {\r\n"
              ." font-family: Verdana;\r\n"
              ." font-size: 14px;\r\n"
              ."}\r\n"
              ."table {\r\n"
              ." font-family: Verdana;\r\n"
              ." border-collapse: collapse;\r\n"
              ."}\r\n"
              ."td {\r\n"
              ." font-family: Verdana;\r\n"
              ." font-size: 11px;\r\n"
              ."}\r\n"
              ."th {\r\n"
              ." font-family: Verdana;\r\n"
              ." font-size: 18px;\r\n"
              ." font-weight: bold;\r\n"
              ." color: #333333;\r\n"
              ." border-bottom: 1px solid #CFCFCF;\r\n"
              ." text-indent: 15px;\r\n"
              ." text-align: left;\r\n"
              ."}\r\n"
              ."-->\r\n"
              ."</style>\r\n"
              ."  <body style=\"background: #FFFFFF; font-family: Tahoma; font-size: 11px; color: #333333; margin: 0px; padding: 0px; height: 100%;\" align=\"center\">\r\n"
              ."  <center><br />\r\n"
              ."    <table border=\"0\" align=\"center\" width=\"95%\" valign=\"top\" cellspacing=\"0\" cellpadding=\"0\">\r\n"
              ."      <tr>\r\n"
              ."        <th colspan=\"2\">Report - Data Migration for Joomla! 1.5</th>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td width=\"50%\" valign=\"top\"><br />\r\n"
              ."          <table border=\"0\" align=\"center\" width=\"100%\" style=\"margin-right: 5px;\">\r\n"
              ."            <tr>\r\n"
              ."              <td colspan=\"2\" style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF\">System</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Product</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$_VERSION->PRODUCT."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Version</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Date</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$_VERSION->RELDATE."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Code Name</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$_VERSION->CODENAME."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">MySQL Version</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".@mysql_get_server_info()."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">PHP Version</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".phpversion()."</td>\r\n"
              ."            </tr>\r\n"
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."        <td width=\"50%\" valign=\"top\"><br />\r\n"
              ."          <table border=\"0\" align=\"center\" width=\"100%\" style=\"margin-left: 5px;\">\r\n"
              ."            <tr>\r\n"
              ."              <td colspan=\"2\" style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF\">Report</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Date</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".strftime(_MIG_DATE_FORMAT_LC2, time())."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Files</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">3</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Size</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".mosGetSizes($full_size)."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Table</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$mosConfig_db."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">Remote IP</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$_SERVER['REMOTE_ADDR']."</td>\r\n"
              ."            </tr>\r\n"
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"2\"><br />&nbsp;</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"2\">\r\n"
              ."          <table border=\"0\" align=\"center\" width=\"100%\">\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF;\" colspan=\"3\">Backup Files</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$mosConfig_db."_".$sql_file_time."_MIGRATION.sql.gz</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"15%\">".strftime(_MIG_DATE_FORMAT_LC2, filemtime($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_MIGRATION.sql.gz"))."</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"8%\" align=\"right\">".mosGetSizes(filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_MIGRATION.sql.gz"))."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$mosConfig_db."_".$sql_file_time."_FULL.sql.gz</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"15%\">".strftime(_MIG_DATE_FORMAT_LC2, filemtime($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_FULL.sql.gz"))."</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"8%\" align=\"right\">".mosGetSizes(filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_FULL.sql.gz"))."</td>\r\n"
              ."            </tr>\r\n"
              ."            <tr>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\">".$mosConfig_db."_".$sql_file_time."_PARTYTHIRD.sql.gz</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"15%\">".strftime(_MIG_DATE_FORMAT_LC2, filemtime($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_PARTYTHIRD.sql.gz"))."</td>\r\n"
              ."              <td style=\"border-bottom: 1px solid #CFCFCF;\" width=\"8%\" align=\"right\">".mosGetSizes(filesize($mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_PARTYTHIRD.sql.gz"))."</td>\r\n"
              ."            </tr>\r\n"
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">&nbsp;</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF;\" colspan=\"3\">Migration Dump</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">\r\n"
              ."          <br />\r\n"
              ."          <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n"
              ."          ".$cont_table_info
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">&nbsp;</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF;\" colspan=\"3\">Full Dump</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">\r\n"
              ."          <br />\r\n"
              ."          <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n"
              ."          ".$full_table_info
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">&nbsp;</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td style=\"font-weight: bold; background: #BF4E4A; color: #FFFFFF;\" colspan=\"3\">Third Party Dump</td>\r\n"
              ."      </tr>\r\n"
              ."      <tr>\r\n"
              ."        <td colspan=\"3\">\r\n"
              ."          <br />\r\n"
              ."          <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n"
              ."          ".$tpct_table_info
              ."          </table>\r\n"
              ."        </td>\r\n"
              ."      </tr>\r\n";


    $content.= "    </table>\r\n"
              ."    <br />\r\n"
//              ."    <div style=\"border-top: 1px solid #BF4E4A;\">Report created with Migrator ".$mig_version." by <a href=\"http://www.mambobaer.de\" target=\"_blank\">MamboBaer.de</a></div>\r\n"
              ."  </center>\r\n"
              ."  </body>\r\n"
              ."</html>\r\n";

    $report_file = $mosConfig_absolute_path.$backup_path.$mosConfig_db."_".$sql_file_time."_REPORT.html";
    if ($fp = fopen($report_file, "wb")){
       @chmod ($report_file, 0777);
       fwrite($fp, $content);
       fclose($fp);
    }
  }


