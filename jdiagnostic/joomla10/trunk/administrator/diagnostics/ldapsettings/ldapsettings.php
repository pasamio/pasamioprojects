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

if (!function_exists('ldap_connect')) {
	die('LDAP: authentication (crit) PHP LDAP Library not detected');
} else if (!class_exists('ldapConnector')) {
	die('LDAP: authentication (crit) Joomla! LDAP Library not detected');
}

global $mosConfig_absolute_path;
$file = $mosConfig_absolute_path.'/mambots/system/joomla.ldap.php';
require($file);

$panel = mosGetParam($_POST,'screen','');
$step = mosGetParam($_POST,'step','');

theader();
switch($panel) {
	default:
		
?>
<p>LDAP Diagnostics</p>
<p>This system runs diagnostics against your configuration to try and determine errors.</p>
<p>Please select a diagnostic:</p>
<p>	<select name="screen">
		<option value="auth">Authentication Test</option>
	</select></p>
<p><input type="submit" value="Proceed >>"></p>
</form>
<?php
		break;
}
?> </form><?php

function theader() { ?>
	<form method="post" action="index2.php">
<input type="hidden" name="option" value="com_jdiagnostic">
<input type="hidden" name="mode" value="diagnostic">
<input type="hidden" name="tool" value="ldapsettings"><?php
}
?>
