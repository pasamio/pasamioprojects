<?php
/**
 * JAuth Wizard Main Controller
 * 
 * JAuth Wizard Main Controller File
 * 
 * MySQL 4.0/4.1/5.0
 * PHP4/5
 *  
 * Created on 12/06/2007
 * 
 * @package JDiagnostic
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

if(!defined('_VALID_MOS'))  if(!defined('_JEXEC')) die('Not within Joomla');
// Forwards compat layer
if(!defined('_JEXEC')) define('_JEXEC', 1);
echo '<div style="padding: 5px; text-align: left;">';
echo '<p class="componentheading" align="left">JDiagnostic</p><hr />';
$mode = mosGetParam($_REQUEST,'mode',''); 
switch($mode) {
	case 'tool':
		doTool();
		break;
	case 'diagnostic':
		doDiagnostic();
		break;
	default:
		displayDefault();
		break;
}
echo '</div>';



function basePath() { global $mosConfig_absolute_path; return $mosConfig_absolute_path .'/administrator/components/com_jdiagnostic/'; }
function diagnosticPath() { return basePath().'diagnostics/'; }
function toolsPath() { return basePath().'tools/'; }

function displayDefault() {
	// :D
	$tools = opendir(toolsPath());
	$diags = opendir(diagnosticPath());
	?><table class="adminTable" align="left"><tr><th align="left">Tools</th></tr><?php
	$toolset = Array();
	$diagset = Array();
	while($tool = readdir($tools)) {
		if($tool != '.' && $tool != '..' && filetype(toolsPath().'/'.$tool) == 'dir') {
			$toolset[] = $tool;
		}
	}
	foreach($toolset as $tool) {
		echo '<tr><td><a href="index2.php?option=com_jdiagnostic&mode=tool&tool='.$tool.'">'.$tool.'</a></td></tr>';
	}
	?><tr><th align="left">Diagnostics</th></tr><?php
	while($diag = readdir($diags)) {
		if($diag != '.' && $diag != '..' && filetype(diagnosticPath().'/'.$diag) == 'dir') {
			$diagset[] = $diag;
		}
	}
	foreach($diagset as $diag) {
		echo '<tr><td><a href="index2.php?option=com_jdiagnostic&mode=diag&diag='.$diag.'">'.$diag.'</a></td></tr>';
	}
	echo '</table>';
	
}

function doTool() {
	$tool = mosGetParam($_REQUEST,'tool', '');
	if(!$tool) die('Invalid tool');
	require_once(toolsPath().$tool.'/'.$tool.'.php');
}

function doDiagnostic() {
	$diag = mosGetParam($_REQUEST,'diag', '');
	if(!$diag) die('Invalid Diagnostic');
	require_once(diagnosticPath().$diag.'/'.$diag.'.php');
}
?>
