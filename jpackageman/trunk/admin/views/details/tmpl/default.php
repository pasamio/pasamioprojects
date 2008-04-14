<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Oct 5, 2007
 * 
 * @package JPackageMan
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_JEXEC') or die('Ni Ai Wo?');

?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
<div class="col50">
	<fieldset class="adminform">
	<legend>Package Details</legend>

		<table class="admintable" cellspacing="1">
			<tr>
				<td width="100%" class="key">
						<?php echo JText::_( 'Full Name' ) ?>
				</td>
				<td>
					<?php echo $this->package->name ?>
				</td>				
			</tr>
			<tr>
				<td width="100%" class="key">
						<?php echo JText::_( 'Package Name' ) ?>
				</td>
				<td><?php echo $this->package->packagename ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'URL' ) ?></td>
				<td><a target="_blank" href="<?php echo $this->package->url ?>"><?php echo $this->package->url ?></a></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Description' ) ?></td>
				<td><?php echo $this->package->description ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Packager' ) ?></td>
				<td><?php echo $this->package->packager ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Packager URL' ) ?></td>
				<td><a target="_blank" href="<?php echo $this->package->packagerurl ?>"><?php echo $this->package->packagerurl ?></a></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Update Site' ) ?></td>
				<td><?php echo $this->package->update ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Version' ) ?></td>
				<td><?php echo $this->package->version ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Manifest File' ) ?></td>
				<td><?php echo $this->package->manifest_filename ?></td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="col50">
	<fieldset class="adminform">
	<legend>File List</legend>
    <table class="adminlist">
    <thead>
        <!-- <tr>
            <th><?php echo JText::_('Filename') ?></th>
        </tr> -->
   </thead>
        <?php
        $k = 0;
         foreach($this->package->filelist as $file) : ?>
       	<tr class="<?php echo "row$k"; ?>">
       		<td>
       		<span class="editlinktip hasTip" title="<?php echo JText::_( 'Package Details' );?>::
       		Package ID: <?php echo $file->id; ?><br />
       		Type: <?php echo $file->type ?><br />
       		<?php if(in_array($file->type, Array('module','template','language'))) { echo 'Client: '. $file->client .'<br />'; }
       		else if($file->type == 'plugin') { echo 'Group: '. $file->group .'<br />'; } ?>">
       		<?php echo $file->filename; $k = 1 - $k; ?>
       		</span>
       		</td>
       	</tr>
       	<?php endforeach ?>
     </table>
   </fieldset>
   </div>

<input type="hidden" name="package" value="<?php echo $this->package->manifest_filename ?>" />   
<input type="hidden" name="option" value="com_jpackageman" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="jpackageman" />
   
</form>


