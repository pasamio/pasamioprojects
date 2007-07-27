<?php
/**
 * @version 	$Id: list.php 137 2006-07-30 15:44:37Z willebil $
 * @package 	Joomla
 * @subpackage	J!Package Directory
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.view');

/**
 * Entity View class for the J!Package Directory component
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirViewList extends JView
{
	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	var $_viewName = 'List';

	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	function display()
	{
		/**
		* Get the entity from the model
		*/ 
		$list = & $this->get('List');

		/**
		 * And render the output here for the List View.
		 */
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<td>
				<?php echo JText::_('Select Version'); ?></small>
				<input type="text" name="version" id="aversion" value="<?php echo $list['version'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<input type="button" value="<?php echo JText::_( 'Go' ); ?>" class="button" onclick="this.form.submit();" />
				<input type="button" value="<?php echo JText::_( 'Reset' ); ?>" class="button" onclick="getElementById('aversion').value='';this.form.submit();" />
			</td>
			<td align="right">
				<table cellpadding="0" cellspacing="5">
				<tr>
					<td width="100%">&nbsp;</td>
					<td><?php echo $list['type']; ?></td>
					<td><?php echo $list['name']; ?></td>
				</tr>
				</table>
			<td>
		</tr>
		</table>

		<table cellpadding="4" cellspacing="0" width="100%" class="adminlist">
		<thead>
		<tr>
			<th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $list['rows'] ); ?>);" /></th>
			<th class="title"><div align="left"><?php echo JText::_('Name'); ?></div></th>
			<th class="title"><div align="left"><?php echo JText::_('Type'); ?></div></th>
			<th class="title" nowrap><div align="left"><?php echo JText::_('Published'); ?></div></th>
			<th class="title" nowrap><div align="left"><?php echo JText::_('Version'); ?></div></th>
			<th class="title" nowrap><div align="right"><?php echo JText::_('File Size'); ?></div></th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($list['rows'] as $row) {
			$url = "index2.php?option=" . $list['option'] . "&task=edit&version=" . $list['version'] ."&type=" . $list['stype'] ."&name=" . $list['sname'] ."&id=" . $row->id;
			?>
		<tr class="row<?php echo $i % 2; ?>">
			<td width='2%'><?php echo mosCommonHTML::CheckedOutProcessing($row, $i); ?></td>
			<td align="left" width="100%"><a href="<?php echo $url; ?>"><?php echo $row->name; ?></a></td>
			<td align="left"><?php echo JPackageDirGeneralHelper::imgPackageType ($row->type, $url); ?></td>
			<td width="10%" align="center"><?php echo mosCommonHTML::PublishedProcessing( $row, $i, 'tick.png', 'publish_x.png' );?></td>
			<td align="left" nowrap><?php echo $row->version; ?></td>
			<td align="right" nowrap><?php echo empty($row->filesize) ? '-' : JPackageDirGeneralHelper::getSize($row->filesize); ?></td>
		</tr>
		<?php $i++;; } ?>
		</table>

		<?php echo $list['pagenav']->getListFooter(); ?>
		<input type="hidden" name="option" value="<?php echo $list['option'];?>" />
		<input type="hidden" name="task" value="showdirectory" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>