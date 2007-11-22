<?php
/**
 * AJAX Server Framework
 * @author Samuel Moffatt <pasamio.id.au>
 * @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

?>
<table class="adminheading">
	<tr>
		<th>
			AJAX Server Framework
		</th>
	</tr>
</table>
<table>
	<tr>
		<td>Server Location:</td>
		<td><?php echo $mosConfig_live_site . '/administrator/components/com_ajaxserver/ajax.server.php' ?></td>
	</tr>
	<tr>
		<td valign="top">XAJAX Details:</td>
		<td>
xajax version 0.1 beta4<br>		
copyright (c) 2005 by J. Max Wilson<br>
<a href="http://xajax.sourceforge.net" target="_blank">http://xajax.sourceforge.net</a>
		</td>
	</tr>
</table>
<form action="index2.php" method="post" name="adminForm">
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="hidemainmenu" value="0">
</form>
