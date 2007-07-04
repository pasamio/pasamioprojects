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
 * @package JMigrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

class Configuration_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $namesmap = Array();
	var $valuesmap = Array();
	
	function getName() { return "Glboal Configuration ETL Plugin"; }
	function getAssociatedTable() { return 'configuration'; }
	function getTargetTable() { return 'various tables'; }
	
	function getEntries() { return 1; }

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
			}
		}
		return $retval;
	}
}
?>
