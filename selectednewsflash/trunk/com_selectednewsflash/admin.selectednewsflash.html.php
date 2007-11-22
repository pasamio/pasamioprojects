<?php
/**
* Selected Newsflash Administration Section - HTML
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Newsfeeds
*/
class HTML_selectednewsflash {

	function showSelection( &$rows, &$lists, $pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Selected Newsflash Manager
			</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title">
				Article Title
			</th>
			<th width="5%">
			Published
			</th>

		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$link 	= 'index2.php?option=com_newsfeeds&task=editA&hidemainmenu=1&id='. $row->id;

			$img 	= $row->published ? 'tick.png' : 'publish_x.png';
			$task 	= $row->published ? 'unpublish' : 'publish';
			$alt 	= $row->published ? 'Published' : 'Unpublished';
			
			$checked = '<input id="cb'.$i.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td align="center">
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
					<?php echo $row->title; ?>
				</td>
				<td width="10%" align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}
	
	function newNewsflash( $option ) {
		?>
	        <script language="JavaScript">
			// This code was a quick mock up to test AJAX integration in a Joomla framework (mambots)
			var interval = 100;
			//this.timerID = setInterval(function () { preSearch(true); }, interval);
			var lastsearchstring = '';
			
			function preSearch() {
				//Put the form data into a variable
				var theQuery = document.getElementById('query').value  ;
				if(theQuery != lastsearchstring) {
					lastsearchstring = theQuery;
				} else {
					return;
				}
				if(theQuery.length < 4) {
					return;
				}
				//If the form data is *not* blank, query the DB and return the results
				if(theQuery !== "") {
					xajax_ajaxDoArticleSearch(theQuery);
				} else {
			//                xajax_ajaxDoArticleSearch("%");
				}
			}
			
		</script>
	
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Select new Newsflash
			</th>
		</tr>
		</table>
		<table class="adminform">
		<tr>
			<th colspan="2">
			Details
			</th>
		</tr>
		<tr>
			<td>
			Content ID:
			</td>
			<td>
			<input class="inputbox" type="text" size="40" id="contentid" name="contentid" value="">
			</td>
		</tr>
		<tr>
			<td>
			Published:
			</td>
			<td>
			<input type="checkbox" class="inputbox" name="published">
			</td>
		</tr>
		<tr>				
		</table>
				
		<table class="adminform">
		<tr>
			<th colspan="2">
			Search for Article
			</th>
		</tr>
		<tr>
			<td valign="top">Search:<br>
			  <input type="text" name="search" autocomplete="off" id="query" onKeyUp="preSearch()" />
			</td>
			<td rowspan="2" valign="top"><div id="preview" style="border: 1px solid black"></div></td>
		</tr>
		<tr>
			<td valign="top">Results:<br>
			<div id="results"></div>
			</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="0">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		</form>
		<?php
	}
}
?>