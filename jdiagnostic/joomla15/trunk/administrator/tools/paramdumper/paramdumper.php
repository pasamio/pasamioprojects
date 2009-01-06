<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Mar 28, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// fun with comboboxes
JHTML::_('behavior.combobox');

/**
 * Creates a list of tables with a field called 'params'
 */
function buildTableList() {
	$db =& JFactory::getDBO();
	$config =& JFactory::getConfig();
	$tables = $db->getTableList();
	$k = 0;
	$validtables = Array();
	$validtables[] = Array('value'=>'Select Table');
	foreach ($tables as $tn) {
		// make sure we get the right tables based on prefix
		if (!preg_match( "/^".$config->get('dbprefix')."/i", $tn )) {
			continue;
		}
		$fields = $db->getTableFields( $tn );
		if(isset( $fields[$tn]['params'] )) {// && isset($fields[$tn]['id'])
			$validtables[] = Array('value'=>$tn);
		//} else if (isset($fields[$tn]['params'])) {
		//	echo '<p>'. $tn .' has a params field but it doesnt have an id field!</p>';
		}
	}
	return $validtables;
}
 
?>
<h1>Param Dumper</h1>
<p>This tool allows you to directly view or copy in the raw INI values of the param fields for various systems.
Usage of this tool isn't for the weak of heart.</p>
<p>Different extensions within Joomla utilise INI files to configure things, and these are stored in the 'params'
field of their respective table. It is sometimes useful to grab these values to back them up or restore them.</p>
<p>Usage:
<ul>
<li>First you will need to work out if the table is supported, these are tables with the exact field name 'params'</li>
<li>Once you have selected your table, you will have to determine the key field, typically this is called 'id'</li>
<li>Once you have determined the key field, you will need to determine the unique identifier of the field. This might
be available when viewing or editing the individual item as an "ID" column (Note: this isn't the # column)</li>
<li>When you have determined all of this information, you can select "Load Params" to load the data from the table.
Once it is loaded you can update the value with new values by hitting "Save Params".</li>
</ul>
<form method="post" action="index.php">
<input type="hidden" name="option" value="com_jdiagnostic" />
<input type="hidden" name="tool" value="paramdumper" />
<input type="hidden" name="mode" value="tool" />
<?php
$table = JRequest::getVar('tablename','');
$field = JRequest::getVar('fieldname','');
$key = $field ? JRequest::getVar('key','') : '';

echo JHTML::_('select.genericlist', buildTableList(), 'tablename', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'value', $table );
if($table) {
	$dbo =& JFactory::getDBO();
	$fieldset = $dbo->getTableFields($table);
	$fieldset = $fieldset[$table];
	$fields = Array();
	$fields[] = Array('value'=>'','text'=>'Select Field');
	foreach($fieldset as $fieldname=>$fieldtype) {
		$fields[] = Array('value'=>$fieldname,'text'=>$fieldname.' ('.$fieldtype.')');
	}
	echo JHTML::_('select.genericlist', $fields, 'fieldname', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $field );
	if($field) {
		echo '<input type="text" name="key" value="'. $key .'" />';
		echo '<input type="submit" name="load" value="Load Params" />';
		if($key) {
			$action = JRequest::getVar('save','');
			if($action) {
				$params = JRequest::getVar('params','');
				if($params) {
					$dbo->setQuery('UPDATE '. $dbo->nameQuote($table) .' SET params = "'. $dbo->getEscaped($params) .'" WHERE '. $dbo->nameQuote($field) .' = "'. $dbo->Quote($key) .'"');
					$dbo->Query();
				}
			}
			$dbo->setQuery('SELECT params FROM '. $dbo->nameQuote($table) .' WHERE '. $dbo->nameQuote($field) .' = '. $dbo->Quote($key));
			$dbo->Query();
			if($dbo->getNumRows()) {
				$result = $dbo->loadResult();
				echo '<input type="submit" name="save" value="Save Params" />';
				echo '<br /><textarea name="params" cols="100" rows="20">'. $result .'</textarea>';
			} else {
				echo '<p>Found no rows</p>';
			}
		}
	}
}
?>
</form>