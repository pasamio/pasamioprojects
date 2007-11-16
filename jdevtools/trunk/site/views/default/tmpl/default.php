<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Sep 21, 2007
 * 
 * @package JDevTools
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1>JDevTools</h1>
<table class="">
	<tr>
		<th><?php echo JText::_('Link') ?></th>
		<th><?php echo JText::_('Last Update') ?></th>
		<th><?php echo JText::_('Status') ?></th>
		<th><?php echo JText::_('Check') ?></th>
	</tr>

	<?php 
	$k = 0;
    for ($i=0, $n=count( $this->links ); $i < $n; $i++)
    {
        $row =& $this->links[$i];
        ?>
	<tr class="<?php echo "row$k"; ?>">
		<td><a href="<?php echo $row->url ?>"><?php echo $row->url ?></a></td>
		<td><?php echo $row->lastupdate ?></td>
		<td><?php echo $row->status ?></td>
	</tr>
	<?php
		$k = 1 - $k; 
	} ?>
</table>
