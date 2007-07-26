<?php
/**
 * @version 	$Id: package.php 115 2006-07-25 21:57:26Z willebil $
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

jimport( 'joomla.application.view');

/**
 * View Element : individual directory package model viewer
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirViewPackage extends JView
{
	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	var $_viewName = 'Package';

	/**
	* Display the edit Package form.
	*
	* @access	public
	*/
	function editdirectory ()
	{
		// Get the entity from the model
		$package = & $this->get('Package');
		$row = $package['row'];
		$drow = $package['drow'];
		$prow = $package['prow'];

		//Just some vars to initialize
		$tabs =& JPane::getInstance('sliders');
		mosCommonHTML::loadOverlib();
		?>
		<!--
		* Start javascript field control.
		-->
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			submitform( pressbutton );
		}
		</script>

		<form action="index2.php" method="post" name="adminForm">
		<div id="page-site">
		<table width="100%" class="admintable" >
		<tr valign="top">
			<td width="60%">
				<table class="noshow" width="100%">
					<tr valign="top">
						<td>
							<fieldset class="adminform">
							<legend><?php echo JText::_('Directory Entry Details'); ?></legend>
							<span class="note"><?php echo JText::_('INSTALLURLINFO'); ?></span>
							<br /><br />
							<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="185" class="key"><?php echo JText::_('Install URL'); ?></td>
								<td><input type="text" id="url" name="url" class="input_box" size="70" value="<?php echo empty($row->url) ? 'http://' : $row->url; ?>" /></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Directory URL'); ?></td>
								<td align="left"><?php echo $row->directory; ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('File Size'); ?></td>
								<td align="left"><?php echo empty($row->filesize) ? '-' : JPackageDirGeneralHelper::getSize($row->filesize); ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Hash Type'); ?></td>
								<td align="left"><?php echo $package['hashtype']; ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Checksum'); ?></td>
								<td align="left"><?php echo empty($row->checksum) ? '-' : $row->checksum; ?></td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
				</table>
				<table class="noshow" width="100%">
					<tr valign="top">
						<td>
							<fieldset class="adminform">
							<legend><?php echo JText::_('Package Information'); ?></legend>
							<table class="admintable" cellspacing="1">
							<tbody>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Type'); ?></td>
								<td align="left"><?php echo empty($row->type) ? '-' : $row->type; ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Name'); ?></td>
								<td align="left"><?php echo empty($row->name) ? '-' : $row->name; ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Version'); ?></td>
								<td align="left"><?php echo empty($row->version) ? '-' : $row->version; ?></td>
							</tr>
							<tr valign="top">
								<td align="left" width="150" class="key"><?php echo JText::_('Description'); ?></td>
								<td align="left"><?php echo empty($row->description) ? '-' : $row->description; ?></td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td width="40%">
				<?php
				$tabs->startPane("extensions-pane");
				$tabs->startPanel(JText::_('Extensions Information'),"extensions-page");
				?>
				<table cellspacing="0" cellpadding="3" width="100%">
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project name'); ?></td>
					<td><?php echo empty($prow->name) ? JText::_('None Corresponding Found') : $package['prow']->name ; ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project owner'); ?></td>
					<td><?php echo empty($prow->created_by_alias) ? '-' : $prow->created_by_alias; ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Published'); ?></td>
					<td><?php echo $prow->published==0 ? JText::_('No') : JText::_('Yes'); ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project Created On'); ?></td>
					<td><?php echo empty($prow->created) ? '-' : $prow->created; ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project Created By'); ?></td>
					<td><?php echo empty($prow->created_by_alias) ? '-' : $prow->created_by_alias; ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project Modified On'); ?></td>
					<td><?php echo $prow->modified == "0000-00-00 00:00:00" ? '-' : $prow->modified; ?></td>
				</tr>
				<tr valign="top">
					<td align="left" width="150" class="key"><?php echo JText::_('Project Modified By'); ?></td>
					<td><?php echo empty($prow->modified_by_alias) ? '-' : $prow->modified_by_alias; ?></td>
				</tr>
				</table>
				<?php
				$tabs->endPanel();
				$tabs->startPanel(JText::_('Full Dependencies'),"dependencies-page");
				?>
				<table cellspacing="0" cellpadding="3" width="100%">
				<tr valign="top">
					<td colspan="3"><?php echo JText::_('FULLDEPINFO'); ?></td>
				</tr>
				<tr valign="top">
					<th><?php echo JText::_('Type'); ?></th>
					<th><?php echo JText::_('Name'); ?></th>
					<th><?php echo JText::_('Version'); ?></th>
				</tr>
				<?php
				if (count($drow) > 0) {
					for ($i=0; $i<count($drow);$i++) {
					?>
				<tr valign="top">
					<td><?php echo $drow[$i]->type; ?></td>
					<td><?php echo $drow[$i]->name; ?></td>
					<td><?php echo $drow[$i]->version; ?></td>
				</tr>
				<?php
					} # End for
				} else {
					?>
				<tr valign="top">
					<td colspan="3"><?php echo JText::_('No Dependencies Defined'); ?></td>
				</tr>
				<?php
				} # End if
				?>
				</table>
				<?php
				$tabs->endPanel();
				$tabs->startPanel(JText::_('Version Dependencies'),"version-page");
				?>
				<table cellspacing="0" cellpadding="0" width="100%" class="adminform">
				<tr valign="top">
					<td colspan="3"><?php echo JText::_('VERSIONDEPINFO'); ?></td>
				</tr>
				<tr valign="top">
					<td><strong><?php echo JText::_('Version'); ?></strong></td>
					<td colspan="2"><strong><?php echo JText::_('Next possible version'); ?></strong></td>
				</tr>
				<?php
				$array = $package['vdepend'];
				if (count($array) == 0) {
					echo "<tr valign=\"top\"><td colspan=\"3\">" . JText::_('No versions dependencies available') . "</td></tr>\n";
				} else {
					for ($i=0; $i<count($array);$i++) {
						echo "<tr valign=\"top\">";
						echo "<td>" . $array[$i][0] . "</td>\n";
						echo "<td colspan=\"2\">" . $array[$i][1] . "</td>\n";
						echo "</tr>\n";
					} # End for
				} # End if
				?>
				</table>
				<?php
				$tabs->endPanel();
				$tabs->startPanel(JText::_('Detailed Directory Entry Information'),"detailed-page");
				?>
				<table cellspacing="0" cellpadding="3" width="100%">
				<tr valign="top">
					<td width="150" class="key"><?php echo JText::_('Entry Created on'); ?></td>
					<td><?php echo empty($row->created) ? '-' : mosFormatDate( $row->created, '%Y-%m-%d %H:%M:%S' ); ?></td>
				</tr>
				<tr valign="top">
					<td width="150" class="key"><?php echo JText::_('Entry created by'); ?></td>
					<td><?php echo empty($row->created_by_alias) ? '-' : $row->created_by_alias ; ?></td>
				</tr>
				<tr valign="top">
					<td width="150" class="key"><?php echo JText::_('Entry last modified on'); ?></td>
					<td><?php echo empty($row->modified) ? '-' : mosFormatDate( $row->modified, '%Y-%m-%d %H:%M:%S' ); ?></td>
				</tr>
				<tr valign="top">
					<td width="150" class="key"><?php echo JText::_('Entry last modified by'); ?></td>
					<td><?php echo empty($row->modified_by_alias) ? '-' : $row->modified_by_alias ; ?></td>
				</tr>
				</table>
				<?php
				$tabs->endPanel();
				$tabs->endPane();
				?>
			</td>
		</tr>
		</table>
		</div>
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $package['option'];?>" />
		<input type="hidden" name="task" value="showdirectory" />
		<input type="hidden" name="version" value="<?php echo $package['version'];?>" />
		<input type="hidden" name="type" value="<?php echo $package['stype'];?>" />
		<input type="hidden" name="name" value="<?php echo $package['sname'];?>" />
		</form>
	<?php
	}
}
?>