<?php
/**
 * @version     $Id: core.php 136 2006-07-29 22:53:53Z schmalls $
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
class JPackageViewCore extends JView
{
    /**
     * Name of the view
     * 
     * @access   private
     * @var      string
     */
    var $_viewName = 'Core';

    /**
     * Displays the view
     */
    function display()
    {
        // get core from model
        $core = & $this->get('Core');
?>
<div class="jpackage">
    <div class="breadcrumb">
        
    </div>
    <h2><?php echo JText::_('JPackage_Core'); ?></h2>
    <ul class="core">
<?php
        foreach ($core as $update) {
?>
        <li>
            <a href="?option=com_jpackage&amp;task=updateCore&amp;version=<?php echo $update['version']; ?>"><?php echo $update['name']; ?></a>
        </li>
<?php
        }
?>
    </ul>
</div>
<?php
    }
    
}