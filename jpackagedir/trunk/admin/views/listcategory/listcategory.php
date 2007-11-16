<?php
/**
 * @version 	$Id: listproject.php 137 2006-07-30 15:44:37Z willebil $
 * @package 	JPackageDir
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
 * Entity View class for the J!Package Directory component: categories
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirViewListCategory extends JView
{
	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	var $_viewName = 'ListCategory';

	/**
	* Method to display an overview of all selected categories.
	*
	* @access	public
	*/
	function display()
	{
		// Get the entity from the model (retrieve all data used to display)
		$list = & $this->get('ListCategory');

		echo $list['cattree'];


	?>
		<div id="page-site">
		<table width="100%">
		<tr valign="top">
			<td width="40%">
					<fieldset id="treeview">
					<legend><?php echo JText::_('Available Categories'); ?></legend>
					<table class="nopad">
					<tbody>
					<tr>
						<td><div id="catTree"></div></td>
					</tr>
					</tbody>
					</table>
					</fieldset>
			</td>
			<td width="60%">
				<table cellpadding="4" cellspacing="0" width="100%" class="adminlist">
				<thead>
				<tr>
					<th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $list['rows'] ); ?>);" /></th>
					<th class="title" width="98%"><div align="left"><?php echo JText::_('Name'); ?></div></th>
					<th class="title"><div align="center"><?php echo JText::_('Published'); ?></div></th>
				</tr>
				</thead>

				<?php
				$i = 0;
				foreach ($list['rows'] as $row) {
					$purl = "index2.php?option=" . $list['option'] . "&task=editproject&project=" . $list['sproject'] ."&category=" . $list['scategory'] . "&name=" . $list['sname'] . "&owner=" . $list['sowner'] . "&id=" . $row->id;
					$uurl = "index2.php?option=com_users&task=edit&cid[]="  . $row->created_by . "&hidemainmenu=1";
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td width='2%'>
						<?php echo mosCommonHTML::CheckedOutProcessing($row, $i); ?>
					</td>
					<td align="left" width="88%">
						<a href="<?php echo $purl; ?>"><?php echo $row->name; ?></a>
					</td>
					<td width="10%" align="center">
						<?php echo JPackageDirGeneralHelper::PublishedProcessing( $row, $i, 'tick.png', 'publish_x.png', 'project' );?>
					</td>
				</tr>
				<?php $i++;; } ?>
				</table>
			</td>
		</tr>
		</table>
		</div>



		<script language="javascript">
			ctDraw ('catTree', CategoryTree, ctThemeXP1, 'ThemeXP', 0, 0)
			ctExposeTreeIndex (0, parseInt (1))
		</script>
	<?php
	}
}
?>
