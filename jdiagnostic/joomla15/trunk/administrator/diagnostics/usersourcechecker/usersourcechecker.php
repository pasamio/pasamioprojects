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
 
$username = JRequest::getVar('username','');
if(strlen($username)) {

}
?>
<p>This tool takes a user name and attempts to determine if SSO and User autocreation would work for that user.</p>
<p>Enter the username you wish to examine</p>
<p>Username: <input type="text" name="username" value="" /></p>
<p>Attempt Autocreation: <input type="checkbox" name="autocreation" /></p>