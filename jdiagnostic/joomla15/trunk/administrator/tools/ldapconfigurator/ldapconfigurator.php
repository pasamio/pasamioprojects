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
} /*else if (!class_exists('JLDAP')) {
	die('LDAP: authentication (crit) Joomla! LDAP Library not detected');
}*/
//global $mosConfig_absolute_path;
//$file = $mosConfig_absolute_path.'/mambots/system/joomla.ldap.php';
//require($file);

jimport('joomla.client.ldap');

$panel = JRequest::getVar('screen','');
$step = JRequest::getVar('step','');

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
	<form method="post" action="index.php">
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
			hiddenVar('base_dn');//=DC=site1,DC=pasamio,DC=homelinux,DC=net
		case 'connect':
			hiddenVar('username');
			hiddenVar('password');
			hiddenVar('users_dn');//=CN=[username],CN=Users,DC=site1,DC=pasamio,DC=homelinux,DC=net
		// Server Variables
		case 'server':
			hiddenVar('host');
			hiddenVar('port');
			hiddenVar('use_ldapV3',1);
			hiddenVar('negotiate_tls',0);
			hiddenVar('no_referrals', 0);
			hiddenVar('sitename','joomla.org');
			break;
	}
}

function hiddenVar($name,$val='') {
	$val = JRequest::getVar($name,$val);
	echo '<input type="hidden" name="'.$name.'" value="'.$val.'">';
}

function testConnect() {
	global $msg,$ldap;
	$host = JRequest::getVar('host','');
	$port = JRequest::getVar('port',389);
	$ldap = new JLDAP();
	$ldap->host = $host;
	$ldap->port = $port;
	$ldap->use_ldapV3 = 1;
	$ldap->no_referrals = 0;
	$ldap->negotiate_tls = 0;	
	$ldap->auth_method = 'bind';
	if(!$ldap->connect()) { $msg = 'Failed to connect to LDAP server'; return false; } 
	return true;
}

function testBind() {
	global $msg, $ldap;
	if(!testConnect()) return false;
	$ldap->username = JRequest::getVar('username','');
	$ldap->password = JRequest::getVar('password','');
	$ldap->users_dn = JRequest::getVar('users_dn','');
	if($ldap->users_dn != '') {
		$udn = str_replace('[username]','',$ldap->users_dn);
		$ldap->username = str_replace($udn,'',$ldap->username);
		JRequest::setVar('username',$ldap->username);
	}
	$ldap->base_dn = JRequest::getVar('base_dn','');
	if(!$ldap->bind()) { $msg = 'Failed to bind to LDAP server'; return false; }
	return true;	
}

function saveConfig() {
	$database =& JFactory::getDBO();
	$config = 'useglobal=0'."\n".
'host='.JRequest::getVar('host','')."\n".
'port='.JRequest::getVar('port',389)."\n".
'use_ldapV3='.JRequest::getVar('use_ldapV3',1)."\n".
'negotiate_tls='.JRequest::getVar('negotiate_tls',0)."\n".
'no_referrals='.JRequest::getVar('no_referrals','0')."\n".
'autocreate='.JRequest::getVar('autocreate','1')."\n".
'autocreateregistered='.JRequest::getVar('autocreateregistered','1')."\n".
'defaultgroup='.JRequest::getVar('defaultgroup','registered')."\n".
'forceldap='.JRequest::getVar('forceldap','0')."\n".
'base_dn='.JRequest::getVar('base_dn')."\n".
'search_dn='.JRequest::getVar('search_dn')."\n".
'search_string='.JRequest::getVar('search_string')."\n".
'auth_method='.JRequest::getVar('auth_method','search')."\n".
'username='.JRequest::getVar('username')."\n".
'password='.JRequest::getVar('password')."\n".
'users_dn='.JRequest::getVar('users_dn')."\n".
'ldap_fullname='.JRequest::getVar('ldap_fullname','displayName')."\n".
'ldap_email='.JRequest::getVar('ldap_email','mail')."\n".
'ldap_uid='.JRequest::getVar('ldap_uid','sAMAccountName')."\n".
'ldap_password='.JRequest::getVar('ldap_password','userPassword')."\n".
'ldap_blocked='.JRequest::getVar('ldap_blocked','loginDisabled')."\n".
'ldap_groupname='.JRequest::getVar('ldap_groupname','memberOf')."\n".
'cbconfirm='.JRequest::getVar('cbconfirm',0)."\n".
'groupMap='.JRequest::getVar('groupMap','');
	$database->setQuery('UPDATE #__plugins SET params = "'. $database->getEscaped($config).'", published = 1 WHERE element = "ldap" AND folder = "authentication"');
	$database->Query();
	return true;
}
