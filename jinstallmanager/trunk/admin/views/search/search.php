<?php
/**
 * @version     $Id: package.php 126 2006-07-27 23:16:17Z schmalls $
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
class JPackageViewSearch extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Search';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $results        = & $this->get( 'Results' );
        $search         = & $this->get( 'Search' );
        $site           = & $this->get( 'Site', 'jpackagemodelsites' );
?>
<div class="jpackage">
    <table class="adminform">
        <tbody>
            <tr>
                <td style="text-align:right;">
                    <form action="index.php" method="post">
                        <?php echo JText::_('Search'); ?> <input type="text" name="search_text" value="<?php echo $search; ?>" />
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
                <th><?php echo JText::_('Project Name'); ?></th>
                <th><?php echo JText::_('Description'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 0;
        foreach ($results as $package) {
            $i++;
            $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td>
                    <strong><a href="?option=com_jpackage&amp;task=package&amp;site=<?php echo $site; ?>&amp;link_id=<?php echo $package['id']; ?>"><?php echo $package['name']; ?></a></strong>
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
