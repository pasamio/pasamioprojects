<?php
/**
 * @version     $Id: package.php 202 2006-08-20 07:05:55Z schmalls $
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
class JPackageViewPackage extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Package';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $packages       = & $this->get( 'Packages' );
        $cats           = & $this->get( 'Structure', 'jpackagemodelcategory' );
        $breadcrumbs    = & $this->get( 'Breadcrumbs', 'jpackagemodelcategory' );
        $site           = & $this->get( 'Site', 'jpackagemodelsites' );
        $cat_id         = & $this->get( 'CatId', 'jpackagemodelcategory' );
?>
<div class="jpackage">
    <table class="adminform">
        <tbody>
            <tr>
                <td class="breadcrumb">
<?php
        foreach ($breadcrumbs as $breadcrumb) {
            if ($breadcrumb == 0) {
                continue;
            }
            echo '<a href="?option=com_jpackage&amp;task=category&amp;site=', $site, '&amp;cat_id=',
                $cats[$breadcrumb]['id'], '">', $cats[$breadcrumb]['name'], '</a> &rarr; ';
        }
        echo ($cat_id == 0) ? '' : '<a href="?option=com_jpackage&amp;task=category&amp;site=' . $site . '&amp;cat_id=' . $cat_id . '">' . $cats[$cat_id]['name'] . '</a> &rarr; ';
?>
                </td>
                <td style="text-align:right;">
                    <form action="index.php" method="post">
                        <?php echo JText::_('Search'); ?> <input type="text" name="search_text" value="" />
                        <input type="hidden" name="option" value="com_jpackage" />
                        <input type="hidden" name="task" value="search" />
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="adminlist">
        <thead>
            <tr class="title">
                <td><?php echo JText::_('Extension Name'); ?></td>
                <td><?php echo JText::_('Extension Type'); ?></td>
                <td><?php echo JText::_('Version'); ?></td>
                <td><?php echo JText::_('Description'); ?></td>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 0;
        foreach ($packages as $package) {
            $i++;
            $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td>
                    <strong><a href="?option=com_jpackage&amp;task=install&amp;site=<?php echo $site; ?>&amp;id=<?php echo $package['id']; ?>"><?php echo $package['name']; ?></a></strong>
                </td>
                <td>
                    <?php echo $package['type']; ?>
                </td>
                <td>
                    <?php echo $package['version']; ?>
                </td>
                <td>
                    <?php echo $package['description']; ?>
                </td>
            </tr>
<?php
        }
?>
        </tbody>
    </table>
</div>
<?php
    }
    
}
