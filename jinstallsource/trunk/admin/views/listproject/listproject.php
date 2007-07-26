<?php
/**
 * @version 	$Id: listproject.php 137 2006-07-30 15:44:37Z willebil $
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
 * Entity View class for the J!Package Directory component: projects
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirViewListProject extends JView
{
	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	var $_viewName = 'ListProject';

	/**
	* Method to display an overview of all selected projects.
	*
	* @access	public
	*/
	function display()
	{
		/**
		* Get the entity from the model (retrieve all data used to display)
		*/ 
		$list = & $this->get('ListProject');

		/**
		 * And render the output here for the List View.
		 */
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<td width="100%">
				<?php echo JText::_('Project Name'); ?></small>
				<input type="text" name="project" id="aproject" value="<?php echo $list['sproject'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<input type="button" value="<?php echo JText::_( 'Go' ); ?>" class="button" onclick="this.form.submit();" />
				<input type="button" value="<?php echo JText::_( 'Reset' ); ?>" class="button" onclick="getElementById('aproject').value='';this.form.submit();" />
			</td>
			<td align="right">
				<table cellpadding="0" cellspacing="5">
				<tr>
					<td width="100%">&nbsp;</td>
					<td><?php echo $list['category']; ?></td>
				</tr>
				</table>
			<td>
			<td align="right">
				<table cellpadding="0" cellspacing="5">
				<tr>
					<td width="100%">&nbsp;</td>
					<td><?php echo $list['owner']; ?></td>
				</tr>
				</table>
			<td>
			<td align="right">
				<table cellpadding="0" cellspacing="5">
				<tr>
					<td width="100%">&nbsp;</td>
					<td><?php echo $list['pname']; ?></td>
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
			<th class="title"><div align="center"><?php echo JText::_('Published'); ?></div></th>
			<th class="title"><div align="left"><?php echo JText::_('Owner'); ?></div></th>
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
			<td align="left"><a href="<?php echo $uurl; ?>"><?php echo $row->created_by_alias; ?></a></td>
		</tr>
		<?php $i++;; } ?>
		</table>

		<?php echo $list['pagenav']->getListFooter(); ?>
		<input type="hidden" name="option" value="<?php echo $list['option'];?>" />
		<input type="hidden" name="task" value="projects" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>