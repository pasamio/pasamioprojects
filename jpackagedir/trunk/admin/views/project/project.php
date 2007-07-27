<?php
/**
 * @version 	$Id: project.php 148 2006-08-08 21:45:44Z willebil $
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
 * View Element : project viewer
 *
 * @author		Wilco Jansen
 * @package		Joomla
 * @subpackage	J!Package Directory
 * @since		1.5
 */
class JPackageDirViewProject extends JView
{
	/**
	* Name of the view.
	*
	* @access	private
	* @var		string
	*/
	var $_viewName = 'Project';

	/**
	* Display the edit Package form.
	*
	* @access	public
	*/
	function editproject ()
	{
		global $mainframe;

		// Get the entity from the model
		$project = & $this->get('Project');
		$row = $project['row'];

		//Just some vars to initialize
		$tabs =& JPane::getInstance('sliders');
		$editor =& $mainframe->getEditor();
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

		function showlayer(el) {
			document.getElementById(el).style.display = ''
		}

		function hidelayer(el) {
			document.getElementById(el).style.display = 'none'
		}

		function ownerbuttonpressed() {
				showlayer ("ownership")
				hidelayer ("ownershipbutton")
		}
		</script>

		<form action="index2.php" method="post" name="adminForm">
		<div id="page-site">
		<table width="100%" class="admintable">
		<tr valign="top">
			<td width="60%">
				<table class="noshow" width="100%" class="admintable">
					<tr valign="top">
						<td>
							<fieldset>
							<legend><?php echo JText::_('Project Information'); ?></legend>
							<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="185" class="key"><?php echo JText::_('Project Name'); ?></td>
								<td><input type="text" id="name" name="name" class="input_box" size="50" value="<?php echo $row->name; ?>"></td>
							</tr>
							</tbody>
							</table>
							</fieldset>
					</td>
				</tr>
				</table>
				<table class="noshow" width="100%" class="admintable">
					<tr valign="top">
						<td>
							<fieldset>
							<legend><?php echo JText::_('Project Description'); ?></legend>
							<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td colspan="2">
								<?php
									// parameters : areaname, content, width, height, cols, rows
									echo $editor->display( 'description',  $row->description , '100%', '200', '50', '5' ) ;
								?>
								</td>
							</tr>
							</tbody>
							</table>
							</fieldset>
					</td>
				</tr>
				</table>
				<table class="noshow" width="100%" class="admintable">
					<tr valign="top">
						<td>
							<fieldset>
							<legend><?php echo JText::_('Project Details'); ?></legend>
							<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="185" class="key">Website</td>
								<td><input type="text" id="website" name="website" class="input_box" size="50" value="<?php echo $row->website; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key"><?php echo JText::_('E-Mail'); ?></td>
								<td><input type="text" id="email" name="email" class="input_box" size="50" value="<?php echo $row->email; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key"><?php echo JText::_('Licence'); ?></td>
								<td><input type="text" id="licence" name="licence" class="input_box" size="50" value="<?php echo $row->licence; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Developers Website</td>
								<td><input type="text" id="developerurl" name="developerurl" class="input_box" size="50" value="<?php echo empty($row->developerurl) ? 'http://' : $row->developerurl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">URL Detailed Project Info</td>
								<td><input type="text" id="projecturl" name="projecturl" class="input_box" size="50" value="<?php echo empty($row->projecturl) ? 'http://' : $row->projecturl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Documentation URL</td>
								<td><input type="text" id="docurl" name="docurl" class="input_box" size="50" value="<?php echo empty($row->docurl) ? 'http://' : $row->docurl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">FAQ URL</td>
								<td><input type="text" id="faqurl" name="faqurl" class="input_box" size="50" value="<?php echo empty($row->faqurl) ? 'http://' : $row->faqurl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Support Forum URL</td>
								<td><input type="text" id="supporturl" name="supporturl" class="input_box" size="50" value="<?php echo empty($row->supporturl) ? 'http://' : $row->supporturl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Tutorial URL</td>
								<td><input type="text" id="tutorialurl" name="tutorialurl" class="input_box" size="50" value="<?php echo empty($row->tutorialurl) ? 'http://' : $row->tutorialurl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Screenshot URL</td>
								<td><input type="text" id="screenshoturl" name="screenshoturl" class="input_box" size="50" value="<?php echo empty($row->screenshoturl) ? 'http://' : $row->screenshoturl; ?>"></td>
							</tr>
							<tr>
								<td width="185" class="key">Demo URL</td>
								<td><input type="text" id="demourl" name="demourl" class="input_box" size="50" value="<?php echo empty($row->demourl) ? 'http://' : $row->demourl; ?>"></td>
							</tr>
							</tbody>
							</table>
							</fieldset>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%">
				<?php
				$tabs->startPane("extensions-pane");
				$tabs->startPanel(JText::_('Categories'),"categories-page");
				?>
				<table cellspacing="0" cellpadding="3" width="100%">
				<tr valign="top">
					<td width="150" colspan="2"><?php echo JText::_('CATEGORIESLIST'); ?></td>
				</tr>
				<tr valign="top">
					<td width="150" class="key"><?php echo JText::_('Select Category'); ?></td>
					<td align="left"><?php echo $project['categories'];?></td>
				</tr>
				</table>
				<?php
				$tabs->endPanel();
				$tabs->startPanel(JText::_('Project Entry Information'),"genproject-page");
				?>
				<table cellspacing="1" cellpadding="5" width="100%">
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
				<tr valign="top">
					<td colspan="2">
						<div id="ownership" style="DISPLAY: none">
						<table cellspacing="0" cellpadding="3" width="100%">
						<tr valign="top">
							<td width="150" colspan="2"><?php echo JText::_('PROJECTOWNER'); ?></td>
						</tr>
						<tr valign="top">
							<td align="left" colspan="2"><?php echo $project['owners']; ?></td>
						</tr>
						</table>
						</div>

						<div id="ownershipbutton" style="DISPLAY: ">
						<table cellspacing="0" cellpadding="3" width="100%">
						<tr valign="top">
							<td align="right"><input name="ownerbutton" type="button" id="ownerbutton" value="Change Ownership" onClick="ownerbuttonpressed()"></td>
						</tr>
						</div>
						</table>
					</td>
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
		<input type="hidden" name="option" value="<?php echo $project['option'];?>" />
		<input type="hidden" name="task" value="projects" />
		<input type="hidden" name="project" value="<?php echo $project['sproject'];?>" />
		<input type="hidden" name="sname" value="<?php echo $project['sname'];?>" />
		<input type="hidden" name="category" value="<?php echo $project['scategory'];?>" />
		<input type="hidden" name="owner" value="<?php echo $project['sowner'];?>" />
		</form>
	<?php
	}
}
?>