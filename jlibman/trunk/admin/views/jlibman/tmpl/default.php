<?php
/**
 * View: JLibMan Default Template 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
 * 
 * @package JLibMan
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th>
                <?php echo JText::_( 'Library' ); ?>
            </th>
            <th>
            	<?php echo JText::_( 'Version' ); ?>
            </th>
            <th>
            	<?php echo JText::_( 'Package URL' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
        ?>
        <tr class="<?php echo "row$k"; ?>">

            <td>
                <a href="index.php?option=com_jlibman&view=details&package=<?php echo $row->manifest_file ?>"><?php echo $row->name ?></a>
            </td>
            <td align="center">
            	<?php echo $row->version; // $row->version[0]->data(); ?>
            </td>
            <td align="center">
            	<a target="_blank" href="<?php echo $row->url ?>"><?php echo $row->url ?></a>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>

<input type="hidden" name="option" value="com_jlibman" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="jlibman" />
</form>
