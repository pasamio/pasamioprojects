<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on May 25, 2007
 * 
 * @package Migrator
 * @subpackage Tests
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

defined('_VALID_MOS') or die('Restricted Access');

global $database;
$enumerator = new ETLEnumerator();
$plugins = $enumerator->createPlugins(true);
$tasks = new TaskBuilder($database, $plugins);
$tasks->buildTaskList();
$tasks->saveTaskList();
$tasklist = new TaskList($database);
$tasklist->listAll();
$database->setQuery("TRUNCATE TABLE #__migrator_tasks");
$database->Query();
echo '<p>Note: Task table has been truncated.</p>';

