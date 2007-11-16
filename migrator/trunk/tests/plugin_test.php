<?php
/**
 * ETL Plugin Tester
 * 
 * This file tests an ETL Plugin 
 * 
 * PHP4/5
 *  
 * Created on May 22, 2007
 * 
 * @package Migrator
 * @subpackage Tests
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

$plugin_target = "modules_etl";

migratorInclude('plugins/modules');
global $database;
$target = new $plugin_target($database);

echo 'Testing Name: '.  $target->getName() . '<br />';
echo 'Testing Table Name: '. $target->getAssociatedTable() . '<br />';
echo 'Testing Row Count: '. $target->getEntries() . '<br />';
echo 'Testing Transformation: <br /><pre>' . print_r($target->doTransformation(0, $target->getEntries()),1) . '</pre><br />';
?>
