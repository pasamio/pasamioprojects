<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Mar 14, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

// register and import helper libraries we want to use
JLoader::register('JAuthenticationResponse', JPATH_LIBRARIES.DS.'joomla'.DS.'user'.DS.'authentication.php');
jimport('joomla.mail.helper');

class LDAPTester extends JObservable {

	function &testLogin($username, $password) {
		$plugin = JPluginHelper::getPlugin('authentication','ldap');
		JPluginHelper::importPlugin('authentication','ldap');
		// JAuthenticationResponse lives in joomla.user.authentication so we need to force it to load		
	//	jimport('joomla.user.authentication');
		$response = new JAuthenticationResponse();
		$className = 'plgAuthenticationLDAP';
		if (class_exists( $className )) {
			$plugin = new $className($this, (array)$plugin);
		}
		$plugin->onAuthenticate(Array('username'=>$username,'password'=>$password), Array(), $response);
		return $response;
	}
}

$username = JRequest::getVar('ldapusername','');
$password = JRequest::getVar('ldappassword','');
$db =& JFactory::getDBO();

if($username && $password) {
	$test = new LDAPTester();
	$result = $test->testLogin($username,$password);
	switch($result->status) {
		case JAUTHENTICATE_STATUS_SUCCESS:
			echo '<p>User authentication was successful! The user can login if they already exist.</p>';
			echo '<table>';
			echo '<tr><td><b>Username:</b></td><td>'. ($result->username ? $result->username : 'WARNING: Username not returned, suspect search failure').'</td></tr>';
			echo '<tr><td><b>Email:</b></td><td>'. ($result->email ? $result->email : 'WARNING: Email was blank, user autocreation will likely fail').'</td></tr>';
			echo '<tr><td valign="top"><b>Warnings:</b></td><td>';
			if (eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", $result->username) || strlen(utf8_decode($result->username )) < 2) {
					echo 'Invalid username detected<br />';
				}

			if ((trim($result->email) == "") || ! JMailHelper::isEmailAddress($result->email) ) {
				echo 'Invalid email detected<br />';
			}	
	
			// check for existing email
			$query = 'SELECT username'
				. ' FROM #__users '
				. ' WHERE email = '. $db->Quote($result->email);
			$db->setQuery( $query );
			$xid = $db->loadResult();
			if ($username != $xid) {
				echo 'An account already exists with this email address with the username "'. $xid.'"; user autocreation will fail. Please change the other user if this is incorrect.<br />';
			}			
			echo '</td></tr>';
			echo '</table>';
			break;
		case JAUTHENTICATE_STATUS_CANCEL:
			echo '<p>User authentication was cancelled, this is a strange occurence</p>';
			break;
		case JAUTHENTICATE_STATUS_FAILURE:
			echo '<p>User authentication failed</p>';
			if(strlen($result->error_message)) echo '<p>Error Message: '. $result->error_message .'</p>';
			break;
	} 
	echo '<hr />';
} 
?>
<table>
	<tr><td>Username:</td><td><input type="text" name="ldapusername" value="<?php echo $username ?>" /></td></tr>
	<tr><td>Password:</td><td><input type="password" name="ldappassword" value="<?php echo $password ?>" /></td></tr>
	<tr><td colspan="2"><input type="submit" value="Test Login"></td></tr> 
</table>
<input type="hidden" name="screen" value="auth" />
