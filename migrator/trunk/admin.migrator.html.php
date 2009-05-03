<?php
/**
 * HTML Drawing Class
 * 
 * This file handles HTML output 
 * 
 * PHP4/5
 *  
 * Created on May 25, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * HTML Drawing Class
 * Extends the old version for backwards compat
 */
class HTML_migrator extends HTML_migrator_legacy {
	
	function formHeader() {
		?><form action="index2.php" method="post" name="adminForm">
		<script language="javascript"><!--
			function progress(msg) {
				element = this.document.getElementById("proceed");
				if(element) {
					element.innerHTML = msg +  " (<?php echo _BBKP_AUTOPROGRESSACTIVE ?>)";
				}
			}
		//--></script><?php
	}
	
	function formFooter($option, $task) {
		?>
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="<?php echo $task; ?>" /> 		
			<input type="hidden" name="boxchecked" value="0" /> 
			<input type="hidden" name="act" value="" />
		</form>		
		<?php
	}
	
	function config($option, $settings) {
		echo '<div align="left" style="border: 1px solid black; padding: 5px; ">';
		echo '<form method="post" action="index2.php" name="adminForm">';
		echo '<h1>'. __BBKP_CONFIGURATION .'</h1><br />';
		echo '<p>'. __BBKP_CONFIGURATION_INCREMENTS .': <input type="text" name="increments" value="'. $settings->increments .'" /> ('. __BBKP_CONFIGURATION_INCREMENTS_DESC . ')</p>';
		echo '<input type="submit" value="'. __BBKP_CONFIGURATION_SAVE .'" />';
		HTML_migrator::formFooter($option, 'config_store');
		echo '<p><a href="index2.php?option=com_migrator">Home</a></p>';
		echo '</div>';
		echo __VERSION_STRING;	
	}
}
