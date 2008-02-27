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
	<tr><td><b>LDAP Server:</b></td><td><input type="text" name="host" size="50" /></td></tr>
	<tr><td><b>LDAP Port:</b></td><td><input type="text" name="port" value="389" /></td></tr>
	<tr><td valign="top"><b>Default Settings:</b></td><td><ul><li>Use LDAPv3</li><li>TLS Disabled</li><li>No Referrals</li></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<input type="hidden" name="step" value="step2">
	<input type="hidden" name="screen" value="msad">
	<?php
}

function step2() {
	global $msg,$ldap;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	if(!testConnect()) step1();
	hiddenVars('server');
	$sitename = JRequest::getVar('sitename','joomla.org');
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	$users_dn = JRequest::getVar('users_dn','CN=[username],CN=Users,'. $dummydn);
	$base_dn = JRequest::getVar('base_dn',$dummydn);
	$username = JRequest::getVar('username','');
	$password = JRequest::getVar('password','');
	//echo '<pre>DN: '.print_R($_POST,1).'</pre>';
	?>
	<p>Microsoft Active Directory by default requires a username and password to perform any operation
	against it. You should put in a valid user account here unless you have configured Active Directory
	to allow searching from an anonymous bind.</p>
	<p>The Base DN should be the base of your Active Directory, which is usually your domain name. The
	User DN should be that path, plus any prefix required to get to the service user account. For
	example if your service account is in the default user container, modifying something similar to
	the below settings should work.</p>
	<table>
	<tr><td><b>Connect Username:</b></td><td><input type="text" name="username" value="<?php echo $username ?>" /> (You can use the UPN here, e.g. username@<?php echo $sitename ?>, but the User DN below must be blank!)</td></tr>
	<tr><td><b>Connect Password:</b></td><td><input type="password" name="password" value="<?php echo $password ?>" /></td></tr>
	<tr><td><b>User DN:</b></td><td><input type="text" name="users_dn" value="<?php echo $users_dn ?>" /> (e.g. CN=[username],CN=Users,<?php echo $dummydn ?>)</td></tr>
	<tr><td><b>Base DN:</b></td><td><input type="text" name="base_dn" value="<?php echo $base_dn ?>" /> (e.g. <?php echo $dummydn ?>)</td></tr>
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
	$search_string = JRequest::getVar('search_string','sAMAccountName=[search]');
	$search_dn = JRequest::getVar('search_dn','');
	$autocreate = JRequest::getVar('autocreate',1);
	$forceldap = JRequest::getVar('forceldap',0);
	$sitename = JRequest::getVar('sitename','joomla.org');
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	
	?><p>Now that we can connect to the AD server, we need to configure how Joomla! will determine users.</p>
	<p>By default Active Directory places all users in the CN=Users container, but you may have placed 
	your users somewhere else.  The search string is used to find users based on the username they provide
	to Joomla!, this should remain as the default in most cases. The Search DN should point to where
	your users are set up. This may be CN=Users or it might be OU=Finance. Search DN can be seperated by
	semicolons (;) to specify multiple DN's. You may additionally wish to autocreate users and control
	which group they are assigned automatically. Finally force LDAP causes the LDAP SSI plugin to delete 
	the users password if they cannot log in via LDAP.</p>
	<table>
	<tr><td><b>Search DN:</b></td><td><input type="text" name="search_dn" value="<?php echo $search_dn ?>" /> (e.g. CN=Users,<?php echo $dummydn ?>)</td></tr>
	<tr><td><b>Search String:</b></td><td><input type="text" name="search_string" value="<?php echo $search_string ?>" /></td></tr>
	<tr><td><b>Autocreate:</b></td><td><input type="checkbox" name="autocreate" value="1" <?php if($autocreate) echo 'CHECKED' ?> /></td></tr>
  	<tr><td><b>Default Autocreate Group:</b></td><td>
  <select name="defaultgroup">
				<option value="frontend">Frontend User</option>
				<option value="registered">Registered</option>
				<option value="author">Author</option>
				<option value="editor">Editor</option>
  </select></td></tr>
  <tr><td><b>Force LDAP:</b></td><td><input type="checkbox" name="forceldap" value="1" <?php if($forceldap) echo 'CHECKED' ?> /></td></tr>
	<tr><td><b>Default Settings:</b></td><td><ul><li>Auth Method: Search</li></ul></td></tr>
	<tr><td colspan="2"><input type="submit" value="Next >>"></td></tr>
	</table>
	<input type="hidden" name="step" value="step4">
	<input type="hidden" name="screen" value="msad">	
	<input type="hidden" name="auth_method" value="search">
	<?php
}

function step4() {
	global $msg,$ldap;
	if($msg) echo '<p><b>'.$msg.'</b></p>';
	if(!testBind()) step3();
	hiddenVars('user');
	$groupmap = JRequest::getVar('groupMap','');
	$cbconfirm = JRequest::getVar('cbconfirm',0);
	$autocreateregistered = JRequest::getVar('autocreateregistered',1);
	$sitename = JRequest::getVar('sitename','joomla.org');
	$dummydn  = 'DC='.str_replace('.',',DC=',$sitename);
	
	?><p>Joomla! needs some information about the attributes that Active Directory uses to store
	certain user pieces of information. This is all pretty standard and is set in the default settings.
	If you find you need to change these values, edit the mambots individually later.</p>
	<p>There are a few optional features of the LDAP Tools that allow for CommunityBuilder integration
	(to integrate user autocreation) and group mapping. Tied with group mapping is the autocreate 
	registered option which determines if the default level of user should be autocreated. Group
	mapping allows the AD memberOf attribute to be mapped to a Joomla! group. More information on
	group mapping is available on the <a href="http://sammoffatt.com.au/jauthtools/index.php?title=LDAP_Tools/Group_Mapping"
	>JAuthTools Wiki</a>. It is safe to leave these at their defaults.</p>
	<table>
	<tr><td><b>Autoconfirm Community Builder:</b></td><td><input type="checkbox" name="cbconfirm" value="1" <?php if($cbconfirm) echo 'CHECKED' ?> /></td></tr>
	<tr><td><b>Autocreate Registered:</b></td><td><input type="checkbox" name="autocreateregistered" value="1" <?php if($autocreateregistered) echo 'CHECKED' ?> /></td></tr>
	<tr><td><b>Group Map:</b></td><td><textarea name="groupmap" ><?php echo $groupmap ?></textarea></td></tr>
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
	?><p>Congratulations, the settings have been automatically written to the LDAP Library and LDAP SSI
	mambot plugins and should activate automatically.</p>
	<p><a href="index2.php?option=com_jdiagnostic">Home</a></p>
	<?php
}

?>
