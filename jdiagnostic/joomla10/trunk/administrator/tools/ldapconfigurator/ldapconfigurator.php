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
 * @package JMigrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

if (!function_exists('ldap_connect')) {
	die('LDAP: authentication (crit) PHP LDAP Library not detected');
} /*else if (!class_exists('ldapConnector')) {
	die('LDAP: authentication (crit) Joomla! LDAP Library not detected');
}*/
global $mosConfig_absolute_path;
$file = $mosConfig_absolute_path.'/mambots/system/joomla.ldap.php';
require($file);

$panel = mosGetParam($_POST,'screen','');
$step = mosGetParam($_POST,'step','');

theader();
switch($panel) {
	case 'msad':
		require_once('msad.php');
		break;
	case 'generic':
		require_once('generic.php');
		break;
	default:
		
?>
<p>LDAP Configuration Wizard</p>
<p>This wizard will allow easy set up of LDAP system</p>
<p>Please select your LDAP directory:</p>
<p>	<select name="screen">
		<option value="msad">Microsoft Active Directory</option>
		<option value="generic">Generic (e.g. OpenLDAP, Fedora Directory Services, etc)</option>
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
<input type="hidden" name="mode" value="tool">
<input type="hidden" name="tool" value="ldapconfigurator"><?php
}


function hiddenVars($step) {
	switch($step) {
		default: // By default pull everything
		case 'optional':
			hiddenVar('groupMap');
			hiddenVar('cbconfirm');
			hiddenVar('autocreateregistered');			
		// LDAP Mapping
		case 'mappings':
			hiddenVar('ldap_fullname'); //displayName for AD, name for else
			hiddenVar('ldap_email'); //mail
			hiddenVar('ldap_uid'); //sAMAccountName, uid or cn
			hiddenVar('ldap_password');//userPassword
			hiddenVar('ldap_blocked');//loginDisabled for edir
			hiddenVar('ldap_groupname');// memberOf ad groupMembership edir JoomlaGroup openldap 
		// User Set up Variables
		case 'user':
			hiddenVar('search_dn');//=DC=site1,DC=pasamio,DC=homelinux,DC=net
			hiddenVar('search_string');
			hiddenVar('auth_method');
			hiddenVar('autocreate');
			hiddenVar('forceldap');
			hiddenVar('defaultgroup');
		case 'connect':
			hiddenVar('username');
			hiddenVar('password');
			hiddenVar('users_dn');//=CN=[username],CN=Users,DC=site1,DC=pasamio,DC=homelinux,DC=net
			hiddenVar('base_dn');//=DC=site1,DC=pasamio,DC=homelinux,DC=net
		// Server Variables
		case 'server':
			hiddenVar('host');
			hiddenVar('port');
			hiddenVar('use_ldapV3',1);
			hiddenVar('negotiate_tls',0);
			hiddenVar('no_referrals', 1);
			break;
	}
}

function hiddenVar($name,$val='') {
	$val = $val ? $val : mosGetParam($_POST,$name,'');
	echo '<input type="hidden" name="'.$name.'" value="'.$val.'">';
}

function testConnect() {
	global $msg,$ldap;
	$host = mosGetParam($_POST,'host','');
	$port = mosGetParam($_POST,'port',389);
	$ldap = new ldapConnector();
	$ldap->host = $host;
	$ldap->port = $port;
	$ldap->use_ldapV3 = 1;
	$ldap->no_referrals = 1;
	$ldap->negotiate_tls = 0;	
	$ldap->auth_method = 'bind';
	if(!$ldap->connect()) { $msg = 'Failed to connect to LDAP server'; return false; }  
	return true;
}

function testBind() {
	global $msg, $ldap;
	if(!testConnect()) return false;
	$ldap->username = mosGetParam($_POST,'username','');
	$ldap->password = mosGetParam($_POST,'password','');
	$ldap->users_dn = mosGetParam($_POST,'users_dn','');
	$ldap->base_dn = mosGetParam($_POST,'base_dn','');
	if(!$ldap->bind()) { $msg = 'Failed to bind to LDAP server'; return false; }
	return true;	
}

function saveConfig() {
	global $database;
	$config = 'useglobal=0'."\n".
'host='.mosGetParam($_POST,'host','')."\n".
'port='.mosGetParam($_POST,'port',389)."\n".
'use_ldapV3='.mosGetParam($_POST,'use_ldapV3',1)."\n".
'negotiate_tls='.mosGetParam($_POST,'negotiate_tls',0)."\n".
'no_referrals='.mosGetParam($_POST,'no_referrals','1')."\n".
'autocreate='.mosGetParam($_POST,'autocreate','1')."\n".
'autocreateregistered='.mosGetParam($_POST,'autocreateregistered','1')."\n".
'defaultgroup='.mosGetParam($_POST,'defaultgroup','registered')."\n".
'forceldap='.mosGetParam($_POST,'forceldap','0')."\n".
'base_dn='.mosGetParam($_POST,'base_dn')."\n".
'search_dn='.mosGetParam($_POST,'search_dn')."\n".
'search_string='.mosGetParam($_POST,'search_string')."\n".
'auth_method='.mosGetParam($_POST,'auth_method','search')."\n".
'username='.mosGetParam($_POST,'username')."\n".
'password='.mosGetParam($_POST,'password')."\n".
'users_dn='.mosGetParam($_POST,'users_dn')."\n".
'ldap_fullname='.mosGetParam($_POST,'ldap_fullname','displayName')."\n".
'ldap_email='.mosGetParam($_POST,'ldap_email','mail')."\n".
'ldap_uid='.mosGetParam($_POST,'ldap_uid','sAMAccountName')."\n".
'ldap_password='.mosGetParam($_POST,'ldap_password','userPassword')."\n".
'ldap_blocked='.mosGetParam($_POST,'ldap_blocked','loginDisabled')."\n".
'ldap_groupname='.mosGetParam($_POST,'ldap_groupname','memberOf')."\n".
'cbconfirm='.mosGetParam($_POST,'cbconfirm',0)."\n".
'groupMap='.mosGetParam($_POST,'groupMap','');
	$database->setQuery('UPDATE #__mambots SET params = "'. $database->getEscaped($config).'" WHERE element = "ldap.ssi" OR element = "joomla.ldap"');
	$database->Query();
	return true;
}
