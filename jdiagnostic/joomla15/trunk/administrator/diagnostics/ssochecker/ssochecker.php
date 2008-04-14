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


class SSOChecker extends JObservable {
	function __construct() {
		// Import SSO Library Files
		$isLoaded = JPluginHelper :: importPlugin('sso');
		if (!$isLoaded) {
			JError :: raiseWarning('SOME_ERROR_CODE', 'SSOChecker::__construct: Could not load SSO plugins.');
		}
	}

	function detectUser() {
		$plugins = JPluginHelper :: getPlugin('sso');
		$results = Array();
		foreach ($plugins as $plugin) {
			$className = 'plg' . $plugin->type . $plugin->name;
			if (class_exists($className)) {
				$plugin = new $className ($this);
			} else {
				JError :: raiseWarning('SOME_ERROR_CODE', 'JAuthSSOAuthentication::doSSOAuth: Could not load ' . $className);
				continue;
			}
	
			// Try to authenticate remote user
			$results[] = Array('plugin'=>$className,'username'=>($plugin->detectRemoteUser()));
		}
		return $results;
	}
}

?>
<form method="post" action="index.php">
<h1>SSO Checker</h1>
<p>This diagnostic returns the values found from the SSO system.</p>
<p>Results are returned as they are found. If there is more than one result, the first will be used in the actual system.</p>
<p>Note: if you see that there is a suffix or prefix (e.g. a Windows Domain or an email address) you might need to edit
settings of the individual plugin to attempt to remove this.</p>
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="mode" value="diag" />
<input type="hidden" name="diag" value="ssochecker" />
<input type="submit" name="pie" value="Validate user" />
</form>
<?php 

if(JRequest::getVar('pie',false)) {
	$checker = new SSOChecker();
	$results = $checker->detectUser();
	echo '<hr /><h2>Results</h2>';
	foreach($results as $result) {
		echo '<p>Plugin <b>'. $result['plugin'] .'</b> ' . (!strlen($result['username']) ? ' failed to detect a user</p>' : 'found user <b>'. $result['username'] .'</b></p>');
	}
}