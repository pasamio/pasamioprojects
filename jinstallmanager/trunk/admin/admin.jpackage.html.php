<?php
/**
* @version $Id: admin.jpackage.html.php 126 2006-07-27 23:16:17Z schmalls $
* @package Joomla
* @subpackage JPackage
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage JPackage
*/
class JPackage_Admin_Html
{
    /**
     * 
     */
    function showDefault ()
    {
?>
<div class="jpackage">
    <ul class="actions">
        <li><a href="?option=com_jpackage&amp;action=getCategory"><?php echo JText::_('JPackage_BrowseForPackages'); ?></a></li>
    </ul>
</div>
<?php
    }
    
    /**
     * @param   array $sites
     * @param   array $cats
     * @param   array $cat_tree
     * @param   array $cat_rev_tree
     * @param   array $breadcrumbs
     * @param   int $cat_id
     * @param   array $links
     */
    function showCategory( $sites, $cats, $cat_tree, $cat_rev_tree, $breadcrumbs, $cat_id, $links )
    {
?>
<div class="jpackage">
    <div class="breadcrumb">
<?php
        /* ## add dropdown box for sites ## */
        echo '<a href="?option=com_jpackage&amp;action=getCategory">', JText::_('JPackage_BrowseForPackages'), '</a>';
        foreach ($breadcrumbs as $breadcrumb) {
            if ($breadcrumb == 0) {
                continue;
            }
            echo ' &rarr; <a href="?option=com_jpackage&amp;action=getCategory&amp;cat_id=',
                $cats[$breadcrumb]['cat_id'], '">', $cats[$breadcrumb]['cat_name'], '</a>';
        }
        echo ($cat_id == 0) ? '' : ' &rarr; ' . $cats[$cat_id]['cat_name'];
?>
    </div>
    <h2><?php echo JText::_('JPackage_Categories'); ?></h2>
    <ul class="cats">
<?php
        if (isset( $cat_tree[$cat_id] )) {
            foreach ($cat_tree[$cat_id] as $id) {
                echo '<li><a href="?option=com_jpackage&amp;action=getCategory&amp;cat_id=', $id, '">',
                    $cats[$id]['cat_name'], '</a> (', $cats[$id]['cat_cats'], '/', $cats[$id]['cat_links'],
                    ')</li>', "\n";
            }
        } else {
            echo '<li>', JText::_('JPackage_None'), '</li>';
        }
?>
    </ul>
    <h2><?php echo JText::_('JPackage_Packages'); ?></h2>
    <ul class="links">
<?php
        foreach ($links as $link) {
?>
        <li>
            <a href="?option=com_jpackage&amp;action=getPackage&amp;link_id=<?php echo $link['link_id']; ?>"><?php echo $link['link_name']; ?></a>
            <p><?php echo $link['link_desc']; ?></p>
        </li>
<?php
        }
?>
    </ul>
</div>
<?php
    }
    
    /**
     * @param   array $package
     */
    function showPackage( $package )
    {
        
    }
    
    /**
     * @param   array $latest
     */
    function showLatest( $latest )
    {
        
    }
    
    /**
     * 
     */
    function showSearch()
    {
        
    }
    
    /**
     * @param   array $results
     */
    function showResults( $results )
    {
        
    }
    
}