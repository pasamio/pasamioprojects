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
        <?php foreach($this->table->columns as $col=>$val) : ?>
            <th>
                <?php echo $val; ?>
            </th>
        <?php endforeach ?>
        </tr>            
    </thead>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
        ?>
        <tr class="<?php echo "row$k"; ?>">
        	<?php foreach($this->table->columns as $col=>$val) : ?>
            	<td>
            		<?php if($col == $this->table->key) :
            				echo '<a href="index.php?option=com_tableeditor&task=details&table='. $this->table->table .'&key='.$row->$col.'">'. $row->$col ."</a>";
            			else:
                			echo $row->$col;
                		endif
                	?>
	            </td>
			<?php endforeach ?>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>

<input type="hidden" name="option" value="com_tableeditor" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="tableeditor" />
<input type="hidden" name="table" value="<?php echo $this->table->table ?>" />
</form>
