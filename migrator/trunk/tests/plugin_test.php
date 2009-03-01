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
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

defined('_VALID_MOS') or die('Restricted Access');

$plugin_target = "modules_etl";

migratorInclude('plugins/modules');
global $database;
$target = new $plugin_target($database);

echo 'Testing Name: '.  $target->getName() . '<br />';
echo 'Testing Table Name: '. $target->getAssociatedTable() . '<br />';
echo 'Testing Row Count: '. $target->getEntries() . '<br />';
echo 'Testing Transformation: <br /><pre>' . print_r($target->doTransformation(0, $target->getEntries()),1) . '</pre><br />';

