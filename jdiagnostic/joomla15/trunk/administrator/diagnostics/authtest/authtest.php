<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Mar 28, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

defined('_JEXEC') or die();

// register and import helper libraries we want to use
JLoader::register('JAuthenticationResponse', JPATH_LIBRARIES.DS.'joomla'.DS.'user'.DS.'authentication.php');

$username = JRequest::getVar('ldapusername','');
$password = JRequest::getVar('ldappassword','');

class AuthTester extends JObservable {
	function __construct() {
		// Import SSO Library Files
		$isLoaded = JPluginHelper :: importPlugin('authentication');
		if (!$isLoaded) {
			JError :: raiseWarning('SOME_ERROR_CODE', 'AuthTester::__construct: Could not load authentication plugins.');
		}
	}

	function validateUser($username,$password) {
		$plugins = JPluginHelper :: getPlugin('authentication');
		$results = Array();
		$version = new JVersion;
		foreach ($plugins as $plugin) {
			$className = 'plg' . $plugin->type . $plugin->name;
			if (class_exists($className)) {
				$plugin = new $className ($this, (array)$plugin);
			} else {
				JError :: raiseWarning('SOME_ERROR_CODE', 'AuthTester::validateUser: Could not load ' . $className);
				continue;
			}
			$response = new JAuthenticationResponse();
			// Try to authenticate remote user
			if($version->RELEASE == '1.5')
			{
				$result = $plugin->onAuthenticate(Array('username'=>$username,'password'=>$password), Array(), $response);
			} else {
				$result = $plugin->onUserAuthenticate(Array('username'=>$username,'password'=>$password), Array(), $response);
			}
			$results[] = Array('plugin'=>$className, 'result'=>intval($result), 'response'=>clone($response));
		}
		return $results;
	}
}

?>
<form method="post" action="index.php">
<h1>Authentication Test</h1>
<p>This diagnostic returns the values found from the authentication system.</p>
<p>Results are returned as they are found. If there is more than one result, the first will be used in the actual system.</p>
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="mode" value="diag" />
<input type="hidden" name="diag" value="authtest" />
<table>
	<tr><td>Username:</td><td><input type="text" name="ldapusername" value="<?php echo $username ?>" /></td></tr>
	<tr><td>Password:</td><td><input type="password" name="ldappassword" value="<?php echo $password ?>" /></td></tr>
	<tr><td colspan="2"><input type="submit" value="Test Login"></td></tr> 
</table>
</form>
<?php 
$db =& JFactory::getDBO();

if($username && $password) {
	$checker = new AuthTester();
	$results = $checker->validateUser($username, $password);
	echo '<hr /><h2>Results</h2>';
	foreach($results as $result) {
		echo '<p>Plugin <b>'. $result['plugin'] .'</b> ' . ($result['response']->status != JAUTHENTICATE_STATUS_SUCCESS ? ' failed to authenticate the user with error <b>'. $result['response']->error_message .'</b>' : 'authenticated user successfully').'.</p>';

	}
}
