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

class JAuthUserSourceChecker extends JObservable {

	/** @var options array options */
	var $_options;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	function __construct($options=Array()) {
		// Import User Source Library Files
		$isLoaded = JPluginHelper :: importPlugin('usersource');
		if (!$isLoaded) {
			JError :: raiseWarning('SOME_ERROR_CODE', 'JAuthUserSource::__construct: Could not load User Source plugins.');
		}
		$this->_options = $options;
	}
	
	function checkUser($username, $autocreate) {
		$results = Array();
		$plugins = JPluginHelper :: getPlugin('usersource');
		foreach ($plugins as $plugin) {
			$className = 'plg' . $plugin->type . $plugin->name;
			if (class_exists($className)) {
				$plugin = new $className ($this, (array)$plugin);
			} else {
				JError :: raiseWarning('SOME_ERROR_CODE', 'JAuthUserSource::doUserCreation: Could not load ' . $className);
				continue;
			}

			// Try to find user
			$user = new JUser();
			if($plugin->getUser($username,$user)) {
				$results[$className] = $user;
				if($autocreate) {
					$user->save();
					$autocreate = 0;
				}
			}
		}		
		return $results;
	} 

}

$username = JRequest::getVar('username','');
$autocreate = JRequest::getBool('autocreation',0);
?>
<h1>User Source Checker</h1>
<p>This tool takes a user name and attempts to determine if SSO and User autocreation would work for that user.</p>
<p>With the given username and optional autocreation option, you can view
<p>Enter the username you wish to examine</p>
<form method="post" action="index.php">
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="mode" value="diag" />
<input type="hidden" name="diag" value="usersourcechecker" />
<table>
<tr><td>Username</td><td><input type="text" name="username" value="<?php echo $username ?>" /></td><td>(This is the name that SSO should have detected)</tr>
<tr><td>Attempt Autocreation:</td><td><input type="checkbox" name="autocreation" <?php if($autocreate) echo 'CHECKED'; ?>/></td><td>(This will automatically create the user in the system if they don't exist; note: this uses the first available plugin!)</td></tr>
<tr><td colspan="2"><input type="submit" value="Check User" /></td></tr>
</table>
</form>
<?php
if(strlen($username)) {
	echo '<hr />';
	$checker = new JAuthUserSourceChecker();
	$results = $checker->checkUser($username,$autocreate);
	foreach($results as $plugin=>$result) {
		echo '<h1>'. $plugin .'</h1>';
		?>
		<table>
			<tr><td>Username:</td><td><?php echo $result->username ?></td></tr>
			<tr><td>Name:</td><td><?php echo $result->name ?></td></tr>
			<tr><td>Email:</td><td><?php echo $result->email ?></td></tr>
			<tr><td>User Type:</td><td><?php echo $result->usertype ?> (Group: <?php echo $result->gid ?>)</td></tr>
			<tr><td>Blocked:</td><td><?php echo ($result->block ? 'Yes' : 'No') ?></td></tr>
			<tr><td>Errors:</td><td><?php foreach($result->_errors as $error) echo $error.'<br />' ?></td></tr>
		</table>
		<?php
	}
}