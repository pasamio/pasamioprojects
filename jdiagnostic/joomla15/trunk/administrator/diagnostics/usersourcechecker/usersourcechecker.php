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
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

class JAuthUserSource extends JObservable {

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
}

$username = JRequest::getVar('username','');
if(strlen($username)) {
	
}
?>
<h1>User Source Checker</h1>
<p>This tool takes a user name and attempts to determine if SSO and User autocreation would work for that user.</p>
<p>Enter the username you wish to examine</p>
<form method="post" action="index.php">
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="mode" value="diag" />
<input type="hidden" name="diag" value="usersourcechecker" />
<table>
<tr><td>Username</td><td><input type="text" name="username" value="" /></td><td>(This is the name that SSO should have detected)</tr>
<tr><td>Attempt Autocreation:</td><td><input type="checkbox" name="autocreation" /></td><td>(This will automatically create the user in the system if they don't exist)</td></tr>
<tr><td colspan="2"><input type="submit" value="Check User" /></td></tr>
</table>
</form>