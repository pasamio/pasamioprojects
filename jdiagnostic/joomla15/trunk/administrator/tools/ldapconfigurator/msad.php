<?php
/**
 * Short Description
 * 
 * Long Description
 * 
 * MySQL 4.0/4.1/5.0
 * PHP4/5
 *  
 * Created on 13/06/2007
 * 
 * @package JDiagnostic
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

$msg = '';
$ldap = null;

switch($step) {
	case 'step6':
		step6();
		break;
	case 'step5':
		step5();
		break;
	case 'step4':
		step4();
		break;
	case 'step3':
		step3();
		break;
	case 'step2':
		step2();
		break;
	default:
		step1();
		break;
}

function step1() {
	global $msg;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	?><p>Microsoft Active Directory stands out as a Microsoft product: it is case sensitive, mostly follows
the LDAP standard and requires authenticated logins by default to complete searches. All of these
wonderful features put together make MSAD one of the more challenging systems to configure.</p>
	<p><b>Notes:</b><i>You might wish to use port 3268 again your domain controllers, this LDAP server 
	doesn't give out referrals and has a complete set of your domain.</i></p>
	<table>
	<tr><td><b>Site Name</b></td><td><input type="text" name="sitename" size="50" /> (e.g. joomla.org)</td></tr>
	<tr><td><b>LDAP Port:</b></td><td><input type="text" name="port" value="389" /></td></tr>
	<tr><td valign="top"><b>Default Settings:</b></td><td>
		<ul>
			<li>Use LDAPv3</li>
			<li>TLS Disabled</li>
			<li>Referrals Disabled</li>
			<li>LDAP Server set to your sitename</li>
		</ul></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<input type="hidden" name="step" value="step2">
	<input type="hidden" name="screen" value="msad">
	<?php
}

function step2() {
	global $msg,$ldap;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	JRequest::setVar('host', JRequest::getVar('sitename','joomla.org'));
	if(!testConnect()) step1();
	hiddenVars('server');
	$sitename = JRequest::getVar('sitename','joomla.org');
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	$users_dn = JRequest::getVar('users_dn','CN=[username],CN=Users,'. $dummydn);
	$username = JRequest::getVar('username','');
	$password = JRequest::getVar('password','');
	//echo '<pre>DN: '.print_R($_POST,1).'</pre>';
	?>
	<p>Microsoft Active Directory by default requires a username and password to perform any operation
	against it. You should put in a valid user account here unless you have configured Active Directory
	to allow searching from an anonymous bind. This is used temporarily to provide an interface to
	search for the Base DN to search from and will be ignored later.</p>
	<p>Enter the login of the user you wish to connect with using their user principal name 
	(e.g. username@your.site.name.com).</p>
	<table>
	<tr><td><b>Connect Username:</b></td><td><input type="text" name="username" value="<?php echo $username ?>" /></td></tr>
	<tr><td><b>Connect Password:</b></td><td><input type="password" name="password" value="<?php echo $password ?>" /></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<input type="hidden" name="step" value="step3">
	<input type="hidden" name="screen" value="msad">	
	<?php
}

function step3() {
	global $msg,$ldap;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	if(!testBind()) step2();
	hiddenVars('connect');
	$sitename = JRequest::getVar('sitename','joomla.org');
	$search_string = JRequest::getVar('search_string','userPrincipalName=[search]@'. $sitename);
	$search_dn = JRequest::getVar('search_dn','');
	$autocreate = JRequest::getVar('autocreate',1);
	$forceldap = JRequest::getVar('forceldap',0);
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	$users_dn = JRequest::getVar('users_dn','[username]@'. $sitename);
	if(!strlen($users_dn)) $users_dn = '[username]@'. $sitename ;
	$base_dn = JRequest::getVar('base_dn',$dummydn);
	
	?><p>Now that we can connect to the AD server, we need to configure how Joomla! will determine users.</p>
	<p>By default Active Directory places all users in the CN=Users container, but you may have placed 
	your users somewhere else.  The search string is used to find users based on the username they provide
	to Joomla!, this should remain as the default in most cases. The Base DN should point to where
	your users are set up as this is where the plugin will start searching from initially. This may be CN=Users 
	or it might be OU=Finance. The Base DN may also be the base of your Active Directory, which is usually your 
	site name (which is the default), however you may want to specify a lower location in your tree to improve 
	performance.</p>
	<p>Your User DN is where the authentication user you use sits. For example this will typically be set to its
	default though if you put your users somehwere else it may be different.</p>
	<p>You can use the simple tool below to find your user configured paths. You can reset the defaults by
	clicking the link to the right of the setting you wish to reset. Click on "Base DN" to set the base DN to be
	that value and click on "Users DN" to set the users DN to be that value.</p>
	<table>
	<tr><td><b>Search String:</b></td><td><input type="text" name="search_string" value="<?php echo $search_string ?>"  size="50" /></td></tr>
	<tr><td><b>Base DN:</b></td><td><input type="text" id="base_dn" name="base_dn" value="<?php echo $base_dn ?>" size="100" /> (e.g. <a href="#" onclick="setBaseDN('<?php echo $dummydn ?>'); return false;"><?php echo $dummydn ?></a>)</td></tr>
	<tr><td><b>User DN:</b></td><td><input type="text" id="users_dn" name="users_dn" value="<?php echo $users_dn ?>" size="100" /> (e.g. <a href="#" onclick="setUserDN('<?php echo $users_dn ?>'); return false;"><?php echo $users_dn ?></a>)</td></tr>
	<tr><td><b>Default Settings:</b></td><td><ul><li>Auth Method: Bind</li></ul></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<?php 
		$document =& JFactory::getDocument();

		$document->addScript('components/com_jdiagnostic/tools/ldapconfigurator/tree.js');
		$document->addStyleSheet('components/com_jdiagnostic/tools/ldapconfigurator/config.css');
		$results = $ldap->search(Array('(OU=*)'),$base_dn);
		$dntree = Array();
		foreach($results as $result) {
			$dnsegment = str_replace('OU=','',str_replace(','.$dummydn, '', $result['dn']));
			$dnparts = explode(',', $dnsegment);
			$dnparts = array_reverse($dnparts);
			$tree =& $dntree;
			foreach($dnparts as $part) {
				if(!isset($tree[$part])) {
					$tree[$part] = Array();
				}
				$tree =& $tree[$part]; 
				}
		}
		echo 'Select DN:';
		echo '<div style="border: 1px solid black; padding: 5px;">';
		outputTree($dntree,$dummydn);
		echo '</div>';
	?>
	<input type="hidden" name="step" value="step4">
	<input type="hidden" name="screen" value="msad">	
	<input type="hidden" name="auth_method" value="bind">
	<?php
}

function outputTree(&$tree, $dummydn, $base='') {
	foreach($tree as $leaf=>$value) {
		if($base) {
			echo '<div>';
		} else {
			echo '<div class="root">';
		}
		if($base) $location = $base .','. $leaf;
		else $location = $leaf;
		if(count($value)) {
			echo setDN($location, $dummydn).'<a href="" onclick="toggleNode(this.parentNode); return false;">'. $leaf .' -></a>';
			outputTree($value, $dummydn, $location);
		} else {
			echo setDN($location, $dummydn) . $leaf;
		}
		echo '</div>';
	}
}

function setDN($location, $dummydn) {
	$bdn = 'OU='.implode(',OU=',array_reverse(explode(',',$location))).','.$dummydn;
	$udn = 'CN=[username],'.$bdn;
	return ' (<a href="#" onclick="setBaseDN(\''. $bdn .'\'); return false;">Base DN</a> | <a href="#" onclick="setUserDN(\''. $udn .'\'); return false;">Users DN</a> ) ';
}


function step4() {
	global $msg,$ldap;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	if(!testBind()) step3();
	// reset username and password
	JRequest::setVar('username','');
	JRequest::setVar('password','');
	hiddenVars('user');
	$groupmap = JRequest::getVar('groupMap','');
	$cbconfirm = JRequest::getVar('cbconfirm',0);
	$autocreateregistered = JRequest::getVar('autocreateregistered',1);
	$sitename = JRequest::getVar('sitename','joomla.org');
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	
	?><p>Joomla! needs some information about the attributes that Active Directory uses to store
	certain user pieces of information. This is all pretty standard and is set in the default settings.
	If you find you need to change these values, edit the plugin individually later.</p>
	<p>For Active Directory it is unusual for these settings not to be their defaults, they are simply 
	displayed for your knowledge. Click next to apply the settings.</p>
	<table>
	<tr><td valign="top"><b>Default Settings:</b></td><td>Mappings:<ul><li>Email: mail</li><li>Full Name: displayName</li>
	<li>User ID: 'sAMAccountName'</li><li>Group Name: 'memberOf'</li></ul></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<input type="hidden" name="step" value="step5">
	<input type="hidden" name="screen" value="msad">	
	<input type="hidden" name="ldap_mail" value="search">
	<input type="hidden" name="ldap_fullname" value="displayName">
	<input type="hidden" name="ldap_email" value="mail">
	<input type="hidden" name="ldap_uid" value="sAMAccountName">
	<input type="hidden" name="ldap_password" value="userPassword">
	<input type="hidden" name="ldap_blocked" value="loginDisabled">
	<input type="hidden" name="ldap_groupname" value="memberOf">	
	<?php	
}

function step5() {
	saveConfig() or die('Failed to save configuration!');
	?><p>Congratulations, the settings have been automatically written to the LDAP Authentication plugin and should activate automatically.</p>
	<p><a href="index2.php?option=com_jdiagnostic">Home</a></p>
	<?php
}

?>
