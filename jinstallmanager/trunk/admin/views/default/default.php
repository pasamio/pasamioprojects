<?php
/**
 * @version     $Id: default.php 202 2006-08-20 07:05:55Z schmalls $
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
class JPackageViewDefault extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Default';

    /**
     * Displays the view
     */
    function display()
    {
        $methods = & $this->get('Methods');
?>
<div class="jpackage">
    <form action="index.php" method="get" name="adminForm">
        <input type="hidden" name="option" value="com_jpackage" />
    <table class="adminlist">
        <tbody>
            <tr class="row0">
                <td>
                    <input type="radio" name="task" value="updates" onclick="isChecked(this.checked);" />
                    <strong><a href="?option=com_jpackage&amp;task=updates"><?php echo JText::_('Check for updates to the currently installed extensions'); ?></a></strong>
                </td>
            </tr>
            <tr class="row1">
                <td>
                    <?php echo JText::_('JPACKAGE_UPDATE_TEXT'); ?>
                </td>
            </tr>
            <tr class="row0">
                <td>
                    <input type="radio" name="task" value="browse" onclick="isChecked(this.checked);" />
                    <strong><a href="?option=com_jpackage&amp;task=browse"><?php echo JText::_('Browse for new extensions to install'); ?></a></strong>
                </td>
            </tr>
            <tr class="row1">
                <td>
                    <?php echo JText::_('JPACKAGE_BROWSE_TEXT'); ?>
                </td>
            </tr>
        </tbody>
    </table>
        <input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<?php
    }
    
}