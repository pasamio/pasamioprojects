<?php
/**
 * Configuration ETL Plugin
 * 
 * Configuration ETL Plugin for configuration.php
 * 
 * MySQL 4.0
 * PHP4
 *  
 * Created on 23/05/2007
 * 
 * @package Migrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * System Configuration ETL plugin
 * This is a fake plugin that copies the com_content configuration and the global config out of the file into the db
 */
class Configuration_ETL extends ETLPlugin {
	function getName() { return "Global Configuration ETL Plugin"; }
	function getAssociatedTable() { return 'configuration'; }
	function getTargetTable() { return 'various tables'; }
	
	function getSQLPrologue() { 
		return "CREATE TABLE jos_migration_configuration (\n".
			"`cid`		int(12) NOT NULL auto_increment, \n".
			"`key`		varchar(255) NOT NULL,\n".
			"`value` 	text,\n".
			"PRIMARY KEY(`cid`) );\n";
	}
	
	function getEntries() { return 2; }

	function doTransformation($start, $amount) {
		$retval = Array ();
		for($i = $start; $i < $start+$amount; $i++) {
			switch($i) {
				case 0: // We start from zero for mysql compat, LIMIT 0,1 different to LIMIT 1,1 (1,1 is second item not first which is 0,1);
					// Content ETL
					global $mosConfig_link_titles,$mosConfig_hideAuthor,$mosConfig_hideCreateDate,$mosConfig_hideModifyDate,$mosConfig_hideEmail,$mosConfig_hidePdf,$mosConfig_hidePrint,$mosConfig_hits,$mosConfig_icons,$mosConfig_readmore,$mosConfig_shownoauth,$mosConfig_item_navigation,$mosConfig_vote;
					$params = "";
					$params .= 'link_titles='.$mosConfig_link_titles."\n";
					$params .= 'show_author='.!$mosConfig_hideAuthor."\n";
					$params .= 'show_create_date='.!$mosConfig_hideCreateDate."\n";
					$params .= 'show_modify_date='.!$mosConfig_hideModifyDate."\n";
					$params .= 'show_email_icon='.!$mosConfig_hideEmail."\n";
					$params .= 'show_pdf_icon='.!$mosConfig_hidePdf."\n";
					$params .= 'show_print_icon='.!$mosConfig_hidePrint."\n";
					$params .= 'show_hits='.$mosConfig_hits."\n";
					$params .= 'show_icons='.$mosConfig_icons."\n";
					$params .= 'show_readmore='.$mosConfig_readmore."\n";
					$params .= 'show_noauth='.$mosConfig_shownoauth."\n";
					$params .= 'show_item_navigation='.$mosConfig_item_navigation."\n";
					$params .= 'show_vote='.$mosConfig_vote."\n";
					$params .= "show_title=1\n";
					$params .= "show_intro=1\n";
					$params .= "show_noauth=0\n";
					$params .= "show_section=0\n";
					$params .= "link_section=0\n";
					$params .= "show_category=0\n";
					$params .= "link_category=0\n";
					$retval[] = "UPDATE jos_components SET params = '$params' WHERE link = 'option=com_content';\n";
					// Note: important to have a	;\n
					break;
				case 1:
					global $mosConfig_offline, $mosConfig_sitename, $mosConfig_live_site, $mosConfig_MetaDesc, $mosConfig_debug, $mosConfig_MetaKeys, $mosConfig_MetaTitle, $mosConfig_MetaAuthor, $mosConfig_offline_message,$mosConfig_gzip,$mosConfig_sef,$mosConfig_editor,$mosConfig_smtpauth,$mosConfig_smtpuser,$mosConfig_smtppass,$mosConfig_smtphost,$mosConfig_sendmail,$mosConfig_fromname,$mosConfig_mailfrom,$mosConfig_mailer, $mosConfig_caching,$mosConfig_error_reporting,$mosConfig_list_limit;
					$params = '';
					//$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'offline','".mysql_real_escape_string($mosConfig_offline)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'offline_message','".mysql_real_escape_string($mosConfig_offline_message)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'sitename','".mysql_real_escape_string($mosConfig_sitename)."');\n";
					//$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'live_site','".mysql_real_escape_string($mosConfig_live_site)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'debug','".mysql_real_escape_string($mosConfig_debug)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'MetaDesc','".mysql_real_escape_string($mosConfig_MetaDesc)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'MetaKeys','".mysql_real_escape_string($mosConfig_MetaKeys)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'MetaTitle','".mysql_real_escape_string($mosConfig_MetaTitle)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'MetaAuthor','".mysql_real_escape_string($mosConfig_MetaAuthor)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'gzip','".mysql_real_escape_string($mosConfig_gzip)."');\n";
					//$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'sef','".mysql_real_escape_string($mosConfig_sef)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'editor','".mysql_real_escape_string($mosConfig_editor)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'smtpauth','".mysql_real_escape_string($mosConfig_smtpauth)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'smtpuser','".mysql_real_escape_string($mosConfig_smtpuser)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'smtppass','".mysql_real_escape_string($mosConfig_smtppass)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'smtphost','".mysql_real_escape_string($mosConfig_smtphost)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'sendmail','".mysql_real_escape_string($mosConfig_sendmail)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'fromname','".mysql_real_escape_string($mosConfig_fromname)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'mailfrom','".mysql_real_escape_string($mosConfig_mailfrom)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'mailer','".mysql_real_escape_string($mosConfig_mailer)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'caching','".mysql_real_escape_string($mosConfig_caching)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'error_reporting','".mysql_real_escape_string($mosConfig_error_reporting)."');\n";
					$retval[] = "INSERT INTO jos_migration_configuration VALUES(0,'list_limit','".mysql_real_escape_string($mosConfig_list_limit)."');\n";
					break;
			}
		}
		return $retval;
	}
}

