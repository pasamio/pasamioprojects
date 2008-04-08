<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Feb 11, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// check we're in the right place...
defined('_JEXEC') or die('bad karma dude!');
JToolBarHelper::title( JText::_( 'Migration Assistant' ), 'config.png' );
JRequest::setVar('migration',1);
JRequest::setVar('oldPrefix','jos_');
define('MIGBASE',dirname(__FILE__));
include(MIGBASE.'/includes/tasks.php');
include(MIGBASE.'/includes/model.php');
include(MIGBASE.'/includes/mighelper.php');

if(isset($_GET['migratesettings']) && $_GET['migratesettings']) {
	migrateSettings();
} else if(isset($_POST['fullmigrate']) && $_POST['fullmigrate']) {
	fullMigrate();
} else if(isset($_REQUEST['task']) && $_REQUEST['task'] == 'dumpLoad') {
	//echo '<script language="javascript">alert("dump Load")</script>';
	dumpLoad();
} else if(isset($_REQUEST['task']) && $_REQUEST['task'] == 'postmigrate') {
	echo 'post migrate';
	postMigrate();
} else {
	echo '<h1>'. JText::_('Settings Migration') .'</h1>';
	echo '<p>'. JText::_('Use this for sites that have already been migrated with the RC7 release of the Migrator').'.</p>';
	// are you sure, no really
	echo '<p>'. JText::_('Are you sure you want to migrate settings?') . ' <a href="index.php?option=com_migrationassistant&migratesettings=true">'. JText::_('Migrate Settings Now').'.</a></p>';
	echo '<br /><h1>'. JText::_('Full Migration') .'</h1>';
	echo '<p>'. JText::_('Use this if you wish to migrate your entire site') .'.</p>';
	echo '<dl id="system-message"><dt class="notice">WARNING</dt><dd class="notice message-fade"><ul>';
	echo '<li>'. JText::_('Warning: This will delete all existing data in your site and any tables from installed extensions').'</li>';
	echo '<li>'. JText::_('Any installed extensions will be removed however their files will have to be manually deleted').'</li>';
	echo '</ul></dt></dl>';
	echo '<p>'. JText::_('Migration Script').'</p>';
	?>
	<form method="post" action="index.php" enctype="multipart/form-data">
	<input type="hidden" name="option" value="com_migrationassistant" />
	<input class="input_box" id="migration_script" name="sqlFile" type="file" size="20"  />
	<br/>
	<input class="input_box" id="sqlUploaded" name="sqlUploaded" type="checkbox" /><?php echo JText::_('I have already uploaded a SQL file') ?>
	<br/>
	<input class="input_box" type="submit" name="fullmigrate" value="<?php echo JText::_('Migrate') ?>" />
	<br /><br />	
	<?php
	print_r($_REQUEST);
}
?>