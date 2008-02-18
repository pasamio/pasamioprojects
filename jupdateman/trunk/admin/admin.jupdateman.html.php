<?php
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_jupgrader { 
	
	function intro() {
		?>
		<div align="left" class="upgradebox">
		<p>Welcome to the Joomla! Upgrader component. My job is to guide you through upgrading your Joomla! installation.</p>
		<p>This is a simple step by step process, which will hopefully be as simple as possible. This is:<br>
			<ol>
				<li>Download the Update XML File and select your package file.</li>
				<li>Download the package file and display customary 'Are you sure?' message</li>			
				<li>Completed message</li>
			</ol>
			<br>
			So lets continue our travels and <a href="index2.php?option=com_jupdateman&task=step1">download the update file >>></a>
		</p>
		</div>
		<?php
	}
	
	function showError($message) {
		echo '<p style="color: red; font-weight: bold">'.$message.'</p>';
	}
}	


?>
