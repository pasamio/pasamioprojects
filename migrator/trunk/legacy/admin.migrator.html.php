<?php
/**
* @version $Id: admin.migrator.html.php 2006-05-25 23:00
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

class HTML_migrator_legacy {

  function showDumps($option, $list){
    global $mosConfig_absolute_path, $mosConfig_live_site, $mig_version;

    $content = "<form action=\"index2.php?option=com_migrator\" method=\"post\" name=\"adminForm\">\n"
              ."  <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n"
              ."    <tr>\n"
              ."      <td width=\"100%\" style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\">\n"
              ."        <img src=\"".$mosConfig_live_site."/administrator/components/$option/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
              ."        <font style=\"color: #C64934;font-size: 18px;font-weight: bold; text-align: left;\">Migrator - Data Migration for Joomla! 1.5 </font>\n"
              ."      </td>\n"
              ."    </tr>\n"
              ."  </table>\n"
              ."  <br />\n"
              ."  <table class=\"adminlist\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n"
              ."    <tr>\n"
              ."      <th style=\"text-align: left;\">Dumps</th>\n"
              ."      <th style=\"text-align: center; width: 5%;\">Size</th>\n"
              ."      <th style=\"text-align: center; width: 5%;\" colspan=\"3\">Actions</th>\n"
              ."    </tr>\n"
              .$list
              ."    <tr>\n"
              ."      <th colspan=\"5\">&nbsp;</th>\n"
              ."    </tr>\n"
              ."  </table>\n"
              ."  <input type=\"hidden\" name=\"option\" value=\"$option\">\n"
              ."  <input type=\"hidden\" name=\"task\" value=\"showDumps\">\n"
              ."</form>\n";
//              ."<div class=\"footer\" align=\"center\">\n"
//              ."  <div style=\"font-size: 10px;\" align=\"center\">Migrator ".$mig_version." by <a href=\"http://www.mambobaer.de\" target=\"_blank\">MamboBaer.de</a></div>\n"
//              ."</div>\n";

    echo $content;
  }

  function showInfo($option, $info){
    global $mig_version;
    $content = "<form action=\"index2.php?option=com_ebackup\" method=\"post\" name=\"adminForm\">\n"
              ."<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
              ."  <tr>\n"
              ."    <td style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\">\n"
              ."      <img src=\"/administrator/components/$option/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
              ."      <font style=\"color: #C64934;font-size : 18px;font-weight: bold;text-align: left;\">Migrator - SQL Dump File Info</font>\n"
              ."    </td>\n"
              ."  </tr>\n"
              ."</table>\n"
              ."<table border=\"0\" class=\"contentpaneopen\" width=\"50%\">\n"
              ."  <tr>\n"
              ."    <td><br>\n"
              ."      <table border=\"0\" width=\"80%\" align=\"center\">\n"
              ."        <tr>\n"
              ."          <td>\n"
              ."            <table border=\"0\" class=\"adminlist\" width=\"60%\">\n"
              ."              <tr>\n"
              ."                <th align=\"center\">"._BBKP_SQL_INFO."</th>\n"
              ."              </tr>\n"
              ."              <tr>\n"
              ."                <td>".$info."</td>\n"
              ."              </tr>\n"
              ."              <tr>\n"
              ."                <th>&nbsp;</th>\n"
              ."              </tr>\n"
              ."            </table>\n"
              ."          </td>\n"
              ."        </tr>\n"
              ."      </table>\n"
              ."    </td>\n"
              ."  </tr>\n"
              ."</table>\n"
              ."  <input type=\"hidden\" name=\"option\" value=\"$option\" />\n"
              ."  <input type=\"hidden\" name=\"task\" value=\"\" />\n"
              ."  <input type=\"hidden\" name=\"boxchecked\" value=\"0\" />\n"
              ."</form>\n";
//              ."<div class=\"footer\" align=\"center\">\n"
//              ."  <div style=\"font-size: 10px;\" align=\"center\">Migrator ".$mig_version." by <a href=\"http://www.mambobaer.de\" target=\"_blank\">MamboBaer.de</a></div>\n"
//              ."</div>\n";
    echo $content;
  }
  function showProcess($table, $rec_no, $sql_file, $key, $act_tab, $dump_act){
    global $delaypersession;

    $content = "<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
              ."  <tr>\n"
              ."    <td style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\">\n"
              ."      <img src=\"/administrator/components/com_migrator/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
              ."      <font style=\"color: #C64934;font-size : 18px;font-weight: bold;text-align: left;\">Migrator - Backup...</font>\n"
              ."    </td>\n"
              ."  </tr>\n"
              ."  <tr>\n"
              ."    <td>\n"
              ."      <br />\n"
              ."      <table class=\"adminlist\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n"
              ."        <tr>\n"
              ."          <th colspan=\"2\">".$dump_act."&nbsp;"._BBKP_BACKUP_WORKING."</th>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <td>"._BBKP_BACKUP_TABLE.":</td>\n"
              ."          <td>".$table."</td>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <td>"._BBKP_BACKUP_RECORD.":</td>\n"
              ."          <td>".number_format($rec_no, 0, ',', '.')."</td>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <td>"._BBKP_FILENAME.":</td>\n"
              ."          <td>".$sql_file."</td>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <td>"._BBKP_TABLES.":</td>\n"
              ."          <td>".$key." / ".$act_tab."</td>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <td>"._BBKP_DELAY_TIME.":</td>\n"
              ."          <td>".$delaypersession." ms</td>\n"
              ."        </tr>\n"
              ."        <tr>\n"
              ."          <th colspan=\"2\">&nbsp;</th>\n"
              ."        </tr>\n"
              ."      </table>\n"
              ."    </td>\n"
              ."  </tr>\n"
              ."</table>\n";
    echo $content;
  }

  function showAbout($option){
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $content = "<form action=\"index2.php?option=com_migrator\" method=\"post\" name=\"adminForm\">\n"
              ."  <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n"
              ."    <tr>\n"
              ."      <td width=\"100%\" style=\"border-bottom: 1px solid #CCCCCC;\" width=\"100%\">\n"
              ."        <img src=\"".$mosConfig_live_site."/administrator/components/com_migrator/images/logo.png\" alt=\"\" style=\"margin-right:10px;\" />\n"
              ."        <font style=\"color: #C64934;font-size: 18px;font-weight: bold; text-align: left;\">Migrator - About</font>\n"
              ."      </td>\n"
              ."    </tr>\n"
              ."  </table>\n"
              ."  <br />\n"
              ."  <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n"
              ."    <tr>\n"
              ."      <td>\n";
    echo $content;
    include($mosConfig_absolute_path."/administrator/components/$option/migrator.info.html");
//              ."        ".include($mosConfig_absolute_path."/administrator/components/$option/migrator.info.html")
//              ."        ".include("administrator/components/$option/migrator.info.html")
    $content = "      </td>\n"
              ."    </tr>\n"
              ."  </table>\n"
              ."  <input type=\"hidden\" name=\"option\" value=\"$option\">\n"
              ."  <input type=\"hidden\" name=\"task\" value=\"showInfo\">\n"
              ."</form>\n";
    echo $content;
  }

}
