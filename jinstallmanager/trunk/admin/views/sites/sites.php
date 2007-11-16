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
class JPackageViewSites extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Sites';

    /**
     * Displays the view
     */
    function display()
    {
        // get parts from model
        $task  = & $this->get( 'Task' );
        $site  = & $this->get( 'Site' );
        $sites = & $this->get( 'Sites' );
        $continue = '';
        if ($task == 'updates') {
            $continue = 'showUpdates';
        }
        if ($task == 'browse') {
            $continue = 'category';
        }
?>
<div class="jpackage">
    <form action="index.php" method="get" name="adminForm">
    <table class="adminlist">
        <thead>
            <tr class="title">
                <th>
                    <?php echo JText::_('Site Name'); ?>
                </th>
                <th>
                    <?php echo JText::_('Site Url'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 0;
        foreach($sites as $value) {
            $i++;
            $class_text = 'row' . ($i % 2);
?>
            <tr class="<?php echo $class_text; ?>">
                <td>
                    <input id="cb<?php echo $i; ?>" name="site" value="<?php echo $value['site_id']; ?>" onclick="isChecked(this.checked);" type="radio">
                    <a href="?option=com_jpackage&amp;task=<?php echo $continue; ?>&amp;site=<?php echo $value['site_id']; ?>"><?php echo $value['name']; ?></a>
                </td>
                <td>
                    <?php echo $value['url']; ?>
                </td>
            </tr>
<?php
        }
?>
        </tbody>
    </table>
        <input type="hidden" name="option" value="com_jpackage" />
        <input type="hidden" name="return" value="<?php echo $task; ?>" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="task" value="" />
    </form>
</div>
<?php
    }
    
    /**
     * Displays the view
     */
    function displayEdit()
    {
        // get parts from model
        $return = & $this->get('Return');
        $site = & $this->get('Site');
        $sites = & $this->get('Sites');
?>
<div class="jpackage">
    <form action="?option=com_jpackage" method="post" name="adminForm">
    <table class="adminlist">
        <tbody>
            <tr class="row1">
                <td><?php echo JText::_( 'Site Name' ); ?></td>
                <td><input type="text" name="site_name" value="<?php echo ($site === 0) ? '' : $sites[$site]['name']; ?>" /></td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_( 'Site Url' ); ?></td>
                <td><input type="text" name="site_url" value="<?php echo ($site === 0) ? '' : $sites[$site]['url']; ?>" /></td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_( 'Site Username' ); ?></td>
                <td><input type="text" name="site_username" value="<?php echo ($site === 0) ? '' : $sites[$site]['username']; ?>" /></td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_( 'Site Password' ); ?></td>
                <td><input type="text" name="site_password" value="<?php echo ($site === 0) ? '' : $sites[$site]['password']; ?>" /></td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_( 'Site Authentication Method' ); ?></td>
                <td>
                    <select name="site_method">
                        <option value="http"<?php echo (($site !== 0) && ($sites[$site]['method'] == 'http')) ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'HTTP' ); ?></option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
        <input type="hidden" name="task" value="<?php echo ($site === 0) ? 'addSite' : 'editSite'; ?>" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
    </form>
</div>
<?php
    }
}
