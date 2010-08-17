<?php
/**
 * Short Description
 * 
 * Long Description
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
 
defined('_JEXEC') or die(); 

if (!function_exists('ldap_connect')) {
	die('LDAP: authentication (crit) PHP LDAP Library not detected');
}/* else if (!class_exists('JLDAP')) {
	die('LDAP: authentication (crit) Joomla! LDAP Library not detected');
}*/

//$file = $mosConfig_absolute_path.'/mambots/system/joomla.ldap.php';
//require($file);
jimport('joomla.client.ldap');

$panel = JRequest::getVar('screen','');
$step = JRequest::getVar('step','');

theader();

if($panel && file_exists(dirname(__FILE__).'/screens/'. $panel .'.php')) {
	include(dirname(__FILE__).'/screens/'. $panel .'.php');
} else {		
?>
<p>LDAP Diagnostics</p>
<p>This system runs diagnostics against your configuration to try and determine errors.</p>
<p>Please select a diagnostic:</p>
<p>	<select name="screen">
		<option value="auth">Authentication Test</option>
		<option value="bind">Connection and Bind Test</option>
	</select></p>
<p><input type="submit" value="Proceed >>"></p>
</form>
<?php
}
?> </form><?php

function theader() { ?>
	<form method="post" action="index.php">
<input type="hidden" name="option" value="com_jdiagnostic">
<input type="hidden" name="mode" value="diag">
<input type="hidden" name="diag" value="ldapsettings"><?php
}
?>
