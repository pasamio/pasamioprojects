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
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

/**
 * HTML Drawing Class
 * Extends the old version for backwards compat
 */
class HTML_migrator extends HTML_migrator_legacy {
	
	function formHeader() {
		?><form action="index2.php" method="post" name="adminForm"><?php
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
	
}
?>
