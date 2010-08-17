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
JToolbarHelper::Title('JDiagnostic');
echo '<a href="index.php?option=com_jdiagnostic">Diagnostics Home</a>';
echo '<div style="padding: 5px; text-align: left;">';
$mode = JRequest::getVar('mode',''); 
switch($mode) {
	case 'tool':
		JToolbarHelper::Title('JDiagnostic - Tool');
		doTool();
		break;
	case 'diagnostic':
	case 'diag':
		JToolbarHelper::Title('JDiagnostic - Diagnostic');
		doDiagnostic();
		break;
	default:
		displayDefault();
		break;
}
echo '</div>';



function basePath() { return JPATH_SITE .'/administrator/components/com_jdiagnostic/'; }
function diagnosticPath() { return basePath().'diagnostics/'; }
function toolsPath() { return basePath().'tools/'; }

function displayDefault() {
	// :D
	$tools = opendir(toolsPath());
	$diags = opendir(diagnosticPath());
	?>
	<p>JDiagnostics is a set of minitools and diagnostics that allow you to test individual
	aspects and see with slightly more detail the results of operations. It is useful for
	situations where you may not be able to accurately determine the issue.</p>
	<p>JDiagnostics is not meant to be highly 'user friendly' and has rough edges in places,
	which may mean error codes are outputted natively without being translated.</p>
	<table class="adminTable" align="left"><tr><th align="left">Tools</th></tr><?php
	$toolset = Array();
	$diagset = Array();
	while($tool = readdir($tools)) {
		if($tool[0] != '.' && filetype(toolsPath().'/'.$tool) == 'dir') {
			$toolset[] = $tool;
		}
	}
	foreach($toolset as $tool) {
		echo '<tr><td><a href="index.php?option=com_jdiagnostic&mode=tool&tool='.$tool.'">'.$tool.'</a></td></tr>';
	}
	?><tr><th align="left">Diagnostics</th></tr><?php
	while($diag = readdir($diags)) {
		if($diag[0] != '.' && filetype(diagnosticPath().'/'.$diag) == 'dir') {
			$diagset[] = $diag;
		}
	}
	foreach($diagset as $diag) {
		echo '<tr><td><a href="index.php?option=com_jdiagnostic&mode=diag&diag='.$diag.'">'.$diag.'</a></td></tr>';
	}
	echo '</table>';
	
}

function doTool() {
	$tool = JRequest::getCMD('tool', '');
	if(!$tool) echo('<p>Invalid tool</p>');
	$filename = toolsPath().$tool.'/'.$tool.'.php';
	if(file_exists($filename)) {
		require_once($filename);	
	} else {
		echo '<p>Invalid Tool</p>';	
	}
}

function doDiagnostic() {
	$diag = JRequest::getCMD('diag', '');
	if(!$diag) echo('<p>Invalid Diagnostic</p>');
	$filename = diagnosticPath().$diag.'/'.$diag.'.php';
	if(file_exists($filename)) {
			require_once($filename);	
	} else {
		echo '<p>Invalid diagnostic</p>';	
	}
}

