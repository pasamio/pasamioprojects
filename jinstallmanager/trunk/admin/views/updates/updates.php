<?php
/**
 * @version     $Id: package.php 126 2006-07-27 23:16:17Z schmalls $
 * @package     Joomla
 * @subpackage  JPackage
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.view' );

/**
 * @author      Joshua Thompson
 * @package     Joomla
 * @subpackage  JPackage
 * @since       1.5
 */
class JPackageViewUpdates extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Updates';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $site       = & $this->get( 'Site', 'jpackagemodelsites' );
        $updates    = & $this->get( 'Updates' );
?>
<div class="jpackage">
    <form action="index.php" method="post" name="adminForm">
        <input type="hidden" name="option" value="com_jpackage" />
        <input type="hidden" name="task" value="showUpdateInstall" />
        <input type="hidden" name="site" value="<?php echo $site; ?>" />
<?php
        if (empty( $updates )) {
            echo '<p>' . JText::_('NO_UPDATE_TEXT') . '</p>';
        } else {
?>
    <table class="adminlist">
        <thead>
            <tr class="title">
                <th><?php echo JText::_('Extension Name'); ?></th>
                <th><?php echo JText::_('Extension Type'); ?></th>
                <th><?php echo JText::_('Current Version'); ?></th>
                <th><?php echo JText::_('Available Version'); ?></th>
                <th><?php echo JText::_('Description'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
            $i = 0;
            foreach ($updates as $type => $updates2) {
                foreach ($updates2 as $update) {
                    $i++;
                    $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td>
                    <input type="checkbox" name="package_ids[]" value="<?php echo $update['id']; ?>" onclick="isChecked(this.checked);" />
                    <?php echo $update['name']; ?>
                </td>
                <td><?php echo $update['type']; ?></td>
                <td><?php echo $update['cur_version']; ?></td>
                <td><?php echo $update['version']; ?></td>
                <td><?php echo $update['description']; ?></td>
            </tr>
<?php    
                }
            }
?>
        </tbody>
    </table>
<?php
        }
?>
        <input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<?php
    }
    
    /**
     * Displays the updates to be installed
     */
    function displayUpdates()
    {
        // get parts from model
        $site           = & $this->get( 'Site', 'jpackagemodelsites' );
        $dependencies   = & $this->get( 'Dependencies' );
        $package_ids    = & $this->get( 'PackageIds' );
        $installed      = & $this->get( 'Extensions', 'jpackagemodelupdates' );
	$model 		= $this->getModel();
        $dependencies   = $model->reduceDependencies( $dependencies,
            array_merge( $installed['component'],
                $installed['module'],
                $installed['plugin'],
                $installed['language'],
                $installed['template'] ) );
?>
<div class="jpackage">
    <h2><?php echo JText::_('The Following Will Be Installed'); ?></h2>
    <form action="" method="post" name="adminForm">
        <input type="hidden" name="option" value="com_jpackage" />
        <input type="hidden" name="task" value="doUpdateInstall" />
        <input type="hidden" name="site" value="<?php echo $site; ?>" />
    <table class="adminlist">
        <thead>
            <tr class="title">
                <th><?php echo JText::_('Extension Name'); ?></th>
                <th><?php echo JText::_('Extension Type'); ?></th>
                <th><?php echo JText::_('Version'); ?></th>
                <th><?php echo JText::_('Description'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 0;
        foreach ($dependencies as $dependency) {
                $i++;
                $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td><?php echo $dependency['name']; ?></td>
                <td><?php echo $dependency['type']; ?></td>
                <td><?php echo $dependency['version']; ?></td>
                <td><?php echo $dependency['description']; ?></td>
            </tr>
<?php    
        }
?>
        </tbody>
    </table>
        <input type="hidden" name="boxchecked" value="1" />
<?php
        foreach ($package_ids as $package_id) {
?>
        <input type="hidden" name="package_ids[]" value="<?php echo $package_id; ?>" />
<?php
        }
?>
    </form>
</div>
<?php
    }
}
