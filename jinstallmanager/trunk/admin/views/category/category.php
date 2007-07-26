<?php
/**
 * @version     $Id: category.php 202 2006-08-20 07:05:55Z schmalls $
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
class JPackageViewCategory extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Category';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $site           = & $this->get( 'Site', 'jpackagemodelsites' );
        $cats           = & $this->get( 'Structure' );
        $cat_tree       = & $this->get( 'Tree' );
        $breadcrumbs    = & $this->get( 'Breadcrumbs' );
        $links          = & $this->get( 'Links' );
        $cat_id         = & $this->get( 'CatId' );
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
        echo ($cat_id == 0) ? '' : $cats[$cat_id]['name'] . ' &rarr; ';
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
                <th><?php echo JText::_('Category Name'); ?></th>
                <th><?php echo JText::_('Description'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
        if (isset( $cat_tree[$cat_id] )) {
            $i = 0;
            foreach ($cat_tree[$cat_id] as $id) {
                $i++;
                $class_text = 'row' . ($i % 2);
                echo '<tr class="', $class_text, '"><td><a href="?option=com_jpackage&amp;task=category&amp;site=', $site, '&amp;cat_id=', $id, '">',
                    $cats[$id]['name'], '</a> (', $cats[$id]['cats'], '/', $cats[$id]['projects'],
                    ')</td></tr>', "\n";
            }
        } else {
        echo '<tr class="row0"><td colspan="2">', JText::_('None'), '</td></tr>';
        }
?>
        </tbody>
    </table>
    <br />
    <table class="adminlist">
        <thead>
            <tr class="title">
                <th><?php echo JText::_('Project Name'); ?></th>
                <th><?php echo JText::_('Description'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 0;
        foreach ($links as $link) {
            $i++;
            $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td>
                    <a href="?option=com_jpackage&amp;task=package&amp;site=<?php echo $site; ?>&amp;cat_id=<?php echo $cat_id; ?>&amp;link_id=<?php echo $link['id']; ?>"><?php echo $link['name']; ?></a>
                </td>
                <td>
                    <?php echo $link['description']; ?>
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