<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Mar 17, 2008
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
 
require('net_ipv4.class.php');
$entries = JRequest::getVar('entries','');
$remoteip = $_SERVER['REMOTE_ADDR'];
$target = JRequest::getVar('target',$remoteip);
$results = '';
if($target && $entries) {
		$list = explode("\n", $entries);
		if(in_array($remoteip,$list)) {
			$results .= '<p>Matched individual IP address</p>';
		}
		
		foreach($list as $range) {
			if(strstr($range,'/')) {
				if(Net_IPv4::ipInNetwork($remoteip,trim($range))) $results .= '<p>IP address was matched in range '. $range.'</p>';
			}
		}
	$results .= '';
} 
?>
<h1>IP Address Checker</h1>
<?php echo $results ?>
<p>Enter the IP address you wish to check and your listing. It will then output matches.</p>
<form method="post" action="index.php">
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="diag" value="ipchecker" />
<input type="hidden" name="mode" value="diag" />
<p>Enter mask:<br />
<textarea cols="50" rows="10" name="entries"><?php echo $entries ?></textarea>
</p>
<p>Target IP Address:<input type="text" name="target" value="<?php echo $target ?>" /></p>
<input type="submit" value="Check IP" />
</form> 