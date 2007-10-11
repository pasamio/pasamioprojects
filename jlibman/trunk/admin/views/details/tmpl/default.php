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
 * @package package_name
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
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
	<legend>Library Details</legend>

		<table class="admintable" cellspacing="1">
			<tr>
				<td width="100%" class="key">
						<?php echo JText::_( 'Full Name' ) ?>
				</td>
				<td>
					<?php echo $this->library->name ?>
				</td>				
			</tr>
			<tr>
				<td width="100%" class="key">
						<?php echo JText::_( 'Package Name' ) ?>
				</td>
				<td><?php echo $this->library->packagename ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'URL' ) ?></td>
				<td><a target="_blank" href="<?php echo $this->library->url ?>"><?php echo $this->library->url ?></a></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Description' ) ?></td>
				<td><?php echo $this->library->description ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Packager' ) ?></td>
				<td><?php echo $this->library->packager ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Packager URL' ) ?></td>
				<td><a target="_blank" href="<?php echo $this->library->packagerurl ?>"><?php echo $this->library->packagerurl ?></a></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Update Site' ) ?></td>
				<td><?php echo $this->library->update ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Version' ) ?></td>
				<td><?php echo $this->library->version ?></td>
			</tr>
			<tr>
				<td width="100%" class="key"><?php echo JText::_( 'Manifest File' ) ?></td>
				<td><?php echo $this->library->manifest_filename ?></td>
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
         foreach($this->library->filelist as $file) : ?>
       	<tr class="<?php echo "row$k"; ?>">
       		<td><?php echo $file; $k = 1 - $k; ?></td>
       	</tr>
       	<?php endforeach ?>
     </table>
   </fieldset>
   </div>

<input type="hidden" name="library" value="<?php echo $this->library->manifest_filename ?>" />   
<input type="hidden" name="option" value="com_jlibman" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="jlibman" />
   
</form>


