<?php
/**
* @version $Id: english.php 2006-05-25 23:00
* @package Migrator
* @copyright Copyright (C) 2006 by Mambobaer.de. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

  //DEFINE('_DATE_FORMAT_LC',"%d.%m.%Y"); //Uses PHP's strftime Command Format
  DEFINE('_BBKP_DATE_FORMAT_LC2',"%d.%m.%Y %H:%M");
  DEFINE('_DATE_FORMAT_LC3',"%d. %B %Y at %H:%M:%S");
  //DEFINE('_BBKP_DATE_FORMAT_LC2',"d.m.Y H:i:s");
  DEFINE('_BBKP_DATE_FORMAT_LC3',"%d.%m.%Y %H:%M:%S");

  DEFINE('_BBKP_HEAD_1', "This backup was created with Migrator for Joomla");
  DEFINE('_BBKP_HEAD_2', "Name of Database    ");
  DEFINE('_BBKP_HEAD_3', "Backup created at   ");
  DEFINE('_BBKP_HEAD_4', "Migrator - Copyright 2006 by Harald Baer");
  DEFINE('_BBKP_HEAD_5', "Tabellenstructure for");
  DEFINE('_BBKP_HEAD_6', "Data for Table");
  DEFINE('_BBKP_HEAD_7', "Tables    ");
  DEFINE('_BBKP_HEAD_8', "Job Name  ");
  DEFINE('_BBKP_HEAD_9', "Content Type");
  DEFINE('_BBKP_ENVIRONMENT', "Environment");
  DEFINE('_BBKP_SQL_SERVER', "MySQL Server");
  DEFINE('_BBKP_SQL_CLIENT', "MySQL Client");
  DEFINE('_BBKP_PHP_VERSION', "PHP Version");
  DEFINE('_BBKP_OVERHEAD', "Overhead");
  DEFINE('_BBKP_LINES', "Lines");
  DEFINE('_BBKP_TABLES', "Tables");
  DEFINE('_BBKP_SIZES', "Size");
  DEFINE('_BBKP_AUTO_INC', "Auto Inc.");
  DEFINE('_BBKP_CREATE_TIME', "Creation");
  DEFINE('_BBKP_CHECK_TIME', "Check");
  DEFINE('_BBKP_FILESIZE', "Filesize");
  DEFINE('_BBKP_FILENAME', "Filename");
  DEFINE('_BBKP_TIME', "Time");
  DEFINE('_BBKP_SECONDS', "Seconds");
  DEFINE('_BBKP_DATE', "Date");
  DEFINE('_BBKP_SQL_INFO', "SQL File Info");
  DEFINE('_BBKP_DEL', "Delete");
  DEFINE('_BBKP_DOWNLOAD', "Download");
  DEFINE('_BBKP_SETUP_TITLE', "Settings");
  DEFINE('_BBKP_FULL_INSERTS', "Complete 'INSERT's");
  DEFINE('_BBKP_AUTOINCREMENT', "Add AUTO_INCREMENT-Value");
  DEFINE('_BBKP_TABLE_FILTER', "Only environment tables");
  DEFINE('_BBKP_RUN_TIME', "Max. Script Runtime (sec)");
  DEFINE('_BBKP_DROP',    "With 'DROP TABLE'           ");
  DEFINE('_BBKP_EXISTS',  "With 'IF NOT EXISTS'        ");
  DEFINE('_BBKP_DB_COMP', "MySQL Export-Compatibility  ");
  DEFINE('_BBKP_DB_AUTO_INC', "Auto Inc.                   ");
  DEFINE('_BBKP_DB_STRUCT', "Contains Table Structure    ");
  DEFINE('_BBKP_SETTINGS', "Options");
  DEFINE('_BBKP_GZIP', "Compress as gzip");
  DEFINE('_BBKP_CHECK_OP', "OP");
  DEFINE('_BBKP_CHECK_TYPE', "Type");
  DEFINE('_BBKP_CHECK_MESSAGE', "Message");
  DEFINE('_BBKP_BACKUP_WORKING', "Backup in process...");
  DEFINE('_BBKP_BACKUP_TABLE', "Current table");
  DEFINE('_BBKP_BACKUP_RECORD', "Record");
  DEFINE('_BBKP_DELAY_TIME', "Delay for next session");
  DEFINE('_BBKP_EMAIL', "eMail Address for transfer");
  DEFINE('_BBKP_NO_FILES', "No SQL dump files available!");
  DEFINE('_BBKP_NO_JOBS', "No scheduled backup jobs available!");
  DEFINE('_BBKP_JOB_TITLE', "Job Title");
  DEFINE('_BBKP_JOB_SCHED', "Schedule");
  DEFINE('_BBKP_JOB_STATE', "State (active/inactive)");
  DEFINE('_BBKP_JOB_VERSION', "Versions");
  DEFINE('_BBKP_SUBJECT', "Backup from ");
  DEFINE('_BBKP_JOB_ACTIVATE', "activate Job");
  DEFINE('_BBKP_JOB_DEACTIVATE', "deactivate Job");
  DEFINE('_BBKP_JOB_STATUS', "Status");
  DEFINE('_BBKP_JOB_DROP', "DROP");
  DEFINE('_BBKP_JOB_COMP', "Compat.");
  DEFINE('_BBKP_JOB_EXISTS', "EXISTS.");
  DEFINE('_BBKP_JOB_INC', "Inc");
  DEFINE('_BBKP_JOB_FULL', "Full");
  DEFINE('_BBKP_JOB_EMAIL', "eMail");
  DEFINE('_BBKP_JOB_GZIP', "GZIP");
  DEFINE('_BBKP_JOB_VERSIONS', "Versions");
  DEFINE('_BBKP_JOB_LAST_RUN', "Last Rund");
  DEFINE('_BBKP_JOB_ID', "Job ID");
  DEFINE('_BBKP_JOB_FTP', "FTP");
  DEFINE('_BBKP_SCHED_TYPE', "every;hourly;daily;weekly;monthly");
  DEFINE('_BBKP_FTP_SERVER', "FTP Server");
  DEFINE('_BBKP_FTP_PASV', "FTP Passive");
  DEFINE('_BBKP_FTP_PORT', "FTP Port");
  DEFINE('_BBKP_FTP_USER', "FTP User");
  DEFINE('_BBKP_FTP_PASSWD', "FTP Password");
  DEFINE('_BBKP_FTP_PATH', "FTP Server Path");
  DEFINE('_BBKP_IP_ADDRESS', "IP Addr.");
  DEFINE('_BBKP_HOST', "Host");
  DEFINE('_BBKP_BROWSER', "Browser");
  DEFINE('_BBKP_OS', "OS");
  DEFINE('_BBKP_NO_LOGS', "no entries available");
  DEFINE('_BBKP_UNKNOW_FILE', "Unknown");
  DEFINE('_BBKP_MANUALLY_FILE', "Manually");

  DEFINE('_BBKP_NOTASKSLEFT', 'Done, there are no tasks left.');
  DEFINE('_BBKP_HOME', 'Home');
  DEFINE('_BBKP_NAME', 'Name');
  DEFINE('_BBKP_TRANSFORMATION','Transformation');
  DEFINE('_BBKP_CRITINCLUDEERR', 'CRITICAL ERROR: Failed attempt to include:');
  DEFINE('_BBKP_EXECTASK', 'Executing Task: ');
  DEFINE('_BBKP_EXAMINING', 'Examining ');
  DEFINE('_BBKP_PROCESSED', 'Processed ');
  DEFINE('_BBKP_PERCOFTABLE', '% of table');
  DEFINE('_BBKP_PERCOFALLTASKS', '% of all tasks');
  DEFINE('_BBKP_BEFORETIMEOUT', ' before timeout ');
  DEFINE('_BBKP_OF', ' of ');
  DEFINE('_BBKP_TO', ' to ');
  DEFINE('_BBKP_ROWS', ' rows ');
  DEFINE('_BBKP_NEXT', 'Next');
  DEFINE('_BBKP_PLUGINCREATEFAILURE', 'Failed to create plugin: ');
  DEFINE('_BBKP_MIGRATIONINPROGRESS', 'Migration In Progress');
  DEFINE('_BBKP_MIGMESSAGE', 'If you have Javascript enabled the migrator will automatically progress until complete. Alternatively you can click next after each step');
  DEFINE('_BBKP_INCLUDING','Including ');
  DEFINE('_BBKP_FOUND', 'Found ');
  DEFINE('_BBKP_TASK', 'Task #');
  DEFINE('_BBKP_TABLE','; Table: ');
  DEFINE('_BBKP_START','; Start: ');
  DEFINE('_BBKP_AMOUNTTOCONVERT', '; Amount to convert: ');
  DEFINE('_BBKP_TOTALROWS','; Total Rows: ');
  DEFINE('_BBKP_TRANSFORMSTABLE','; Transforms table ');
  DEFINE('_BBKP_TIMESPENT', 'Time spent this cycle: ');
  DEFINE('_BBKP_TASKSREMAINING', 'tasks remaining');
  DEFINE('_BBKP_CREATE_TITLE', 'Select plugins to enable for migration. By default all plugins should be selected. If you experience errors with a given plugin, you can deselect it.');
  DEFINE('_BBKP_START_MIGRATION', 'Start Migration');
  DEFINE('_BBKP_AUTOPROGRESSACTIVE','Auto progress active');