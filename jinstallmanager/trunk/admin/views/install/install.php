<?php
/**
 * @version     $Id: install.php 227 2006-08-27 21:15:31Z pasamio $
 * @package     JInstallManager
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
class JPackageViewInstall extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Install';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $dependencies   = & $this->get( 'Dependencies' );
        $cats           = & $this->get( 'Structure', 'jpackagemodelcategory' );
        $breadcrumbs    = & $this->get( 'Breadcrumbs', 'jpackagemodelcategory' );
        $site           = & $this->get( 'Site', 'jpackagemodelsites' );
        $id             = & $this->get( 'PackageId' );
        $cat_id         = & $this->get( 'CatId', 'jpackagemodelcategory' );
        $installed      = & $this->get( 'Extensions', 'jpackagemodelupdates' );
	$model		= $this->getModel();
        $dependencies   = $model->reduceDependencies( $dependencies,
            array_merge( $installed['component'],
                $installed['module'],
                $installed['plugin'],
                $installed['language'],
                $installed['template'] ) );
?>
<div class="jpackage">
    <div class="breadcrumb">
<?php
        foreach ($breadcrumbs as $breadcrumb) {
            if ($breadcrumb == 0) {
                continue;
            }
            echo '<a href="?option=com_jpackage&amp;task=category&amp;cat_id=',
                $cats[$breadcrumb]['id'], '">', $cats[$breadcrumb]['name'], '</a> &rarr; ';
        }
        echo ($cat_id == 0) ? '' : '<a href="?option=com_jpackage&amp;task=category&amp;cat_id=' . $cat_id . '">' . $cats[$cat_id]['name'] . '</a> &rarr; ';
?>
    </div>
    <h2><?php echo JText::_('The Following Will Be Installed'); ?></h2>
    <form action="index.php" method="post" name="adminForm">
        <input type="hidden" name="option" value="com_jpackage" />
    <table class="adminlist">
        <thead>
            <tr>
                <th>
                    <?php echo JText::_('Extension Name'); ?>
                </th>
                <th>
                    <?php echo JText::_('Extension Type'); ?>
                </th>
                <th>
                    <?php echo JText::_('Version'); ?>
                </th>
                <th>
                    <?php echo JText::_('Description'); ?>
                </th>
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
                <td>
                    <strong><?php echo $dependency['name']; ?></strong>
                </td>
                <td>
                    <?php echo $dependency['type']; ?>
                </td>
                <td>
                    <?php echo $dependency['version']; ?>
                </td>
                <td>
                    <?php echo $dependency['description']; ?>
                </td>
            </tr>
<?php
        }
?>
        </tbody>
    </table>
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="1" />
    </form>
</div>
<?php
    }
    
}
