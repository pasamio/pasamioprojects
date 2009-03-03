<?php
/**
 * Main Migrator Support Class
 * 
 * This file contains classes to support the migrator
 * The majority of the task and ETL is handled here
 * 
 * PHP4/5
 *  
 * Created on May 25, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// no direct access
defined('_VALID_MOS') or die('Restricted access');

/**
 * Migrator Include Function
 * Short hand to referencing back to this plugin
 * @param string file name of file to include without .php
 */
function migratorInclude($file) {
	global $mosConfig_absolute_path;
	$file = migratorBasePath() . $file . '.php';
	if (file_exists($file)) {
		require_once ($file);
	} else
		die(_BBKP_CRITINCLUDEERR . $file);
}

/**
 * Displays a file from the resource directory
 * @param $file string Filename to display
 */
function displayResource($file) {
	global  $mosConfig_lang;
	$file_lang = migratorBasePath() . '/resources/' . $file . '.' . $mosConfig_lang . '.html';
	$file = migratorBasePath() . '/resources/' . $file . '.english.html';
	if (file_exists($file_lang)) {
		echo '<div align="left" style="border: 1px solid black; padding: 5px; ">';
		include ($file_lang);
		echo '</div>';
	} else if (file_exists($file)) {
		echo '<div align="left" style="border: 1px solid black; padding: 5px; ">';
		include ($file);
		echo '</div>';
	}
	else
		die(_BBKP_CRITINCLERR . $file);
	echo __VERSION_STRING;
}

/**
 * Migrator base path
 * @return string Path to this component
 */
function migratorBasePath() {
	global $mosConfig_absolute_path;
	return $mosConfig_absolute_path . '/administrator/components/com_migrator/';
}

/**
 * This method processes a string and replaces all accented UTF-8 characters by unaccented
 * ASCII-7 "equivalents", whitespaces are replaced by hyphens and the string is lowercased.
 *
 * @static
 * @param	string	$input	String to process
 * @return	string	Processed string
 * @since	1.5
 */
function stringURLSafe($string)
{
	//remove any '-' from the string they will be used as concatonater
	$str = str_replace('-', ' ', $string);
	
	$str = htmlentities(utf8_decode($str));
	$str = preg_replace(
		array('/&szlig;/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
		array('ss',"$1","$1".'e',"$1"),
		$str);

	// remove any duplicate whitespace, and ensure all characters are alphanumeric
	$str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

	// lowercase and trim
	$str = trim(strtolower($str));
	return $str;
}

/**
 * ETL Plugin
 * An ETL Plugin examines the database and returns SQL queries
 */
class ETLPlugin {
	/** @var $db Local Database Object */
	var $db = null;

	/** @var $ignorefieldlist List of fields to ignore */
	var $ignorefieldlist = Array ();
	
	/** @var $newfieldlist List of fields to add (with blank values) */
	var $newfieldlist = Array();

	/** @var $valuesmap List of field values that need mapping/transformation */
	var $valuesmap = Array ();

	/** @var $namesmap List of field names that need mapping/transformation */
	var $namesmap = Array ();
	
	/** @var $_currentRecord The current record that is being translated */
	var $_currentRecord = null;

	function ETLPlugin(& $database) {
		$this->db = $database;
	}

	function toString() {
		return $this->getName() . _BBKP_TRANSFORMSTABLE . $this->getAssociatedTable() . _BBKP_TO . $this->getTargetTable() . '<br />';
	}

	/**
	 * Returns the name of the plugin
	 */
	function getName() {
		return "ETL Plugin Default";
	}

	/**
	 * Returns the table that this plugin transforms
	 */
	function getAssociatedTable() {
		return '';
	}

	/**
	 * Returns the table that this plugins transforms its data into
	 */
	function getTargetTable() {
		return $this->getAssociatedTable();
	}

	/**
	 * Returns the number of entries in the table
	 */
	function getEntries() {
		$this->db->setQuery('SELECT count(*) FROM #__' . $this->getAssociatedTable() . $this->getWhere());
		return $this->db->loadResult();
	}

	/**
	 * Maps old params to new params
	 * Run before names
	 */
	function mapValues($key, $input) {
		return $input;
	}

	/**
	 * Maps old names to new names (useful for renaming fields)
	 */
	function mapNames($key) {
		return $key;
	}
	
	/**
	 * Generates an SQL where clause
	 *
	 */
	function getWhere() {
		return '';
	}

	/**
	 * Generates any SQL statements that need to be completed before the rows are generated
	 */
	function getSQLPrologue() {
		return '';
	}
	
	/**
	 * Generates any SQL statements that should be completed after the rows are generated 
	 */
	function getSQLEpilogue() {
		return '';
	}
	
	/**
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__' . $this->getAssociatedTable() . $this->getWhere() . ' LIMIT ' . $start . ',' . $amount);
		$retval = Array ();
		$newFields = Array();
		foreach($this->newfieldlist as $fieldname) {
			$newFields[$fieldname] = '';
		}
		$results = $this->db->loadAssocList();
		
		if(!count($results)) return $retval;
		foreach ($results as $result) {
			$fieldvalues = '';
			$fieldnames = '';
			$result = array_merge($result, $newFields); // push in the new fields
			$this->_currentRecord =& $result; // Reference this so that sub funcs might get to it
			foreach ($result as $key => $value) {
				if (in_array($key, $this->ignorefieldlist)) {
					continue;
				}
				if (in_array($key, $this->valuesmap)) {
					$value = $this->mapValues($key, $value);
				}
				if (in_array($key, $this->namesmap)) {
					$key = $this->mapNames($key);
				}
				if (strlen($fieldvalues)) {
					$fieldvalues .= ',';
					$fieldnames .= ',';
				}
				$fieldvalues .= '\'' . $this->db->getEscaped($value) . '\'';
				$fieldnames .= '`' . $key . '`';
			}
			$retval[] = 'INSERT INTO jos_' . $this->getTargetTable() . ' (' . $fieldnames . ')' .
			' VALUES ( ' . $fieldvalues . ');'."\n";
		}
		return $retval;
	}
	
	/**
	 * Handles if the item should be selected by default for migration or not
	 */
	function getSelectionPreference() {
		return true;
	}
}

/**
 * Plugin Enumerator
 * Discovers and holds plugins
 */
class ETLEnumerator {
	/** @var $pluginlist Plugin list */
	var $pluginList = Array ();
	/** @var $plugins Plugins */
	var $plugins = Array ();

	function getPlugins($debug = false) {
		global $mosConfig_absolute_path;
		if (count($this->pluginList)) {
			return $this->pluginList;
		}

		$dir = opendir(migratorBasePath() . 'plugins');
		while ($file = readdir($dir)) {
			if (stristr($file, 'php')) {
				if ($debug)
					echo _BBKP_FOUND . $file . '<br />';
				$this->pluginList[] = str_replace('.php', '', $file);
			}
		}
		closedir($dir);
		sort($this->pluginList);
		return $this->pluginList;
	}

	function includePlugins($debug = false) {
		if (!count($this->pluginList)) {
			$this->getPlugins();
		}
		foreach ($this->pluginList as $plugin) {
			if ($debug)
				echo _BBKP_INCLUDING . $plugin . '<br />';
			migratorInclude('plugins/' . $plugin);
		}
	}

	function & createPlugins($debug = false) {
		if (!count($this->pluginList)) {
			$this->getPlugins();
		}
		$pluginChecks = Array();
		
		if(isset($_POST['pluginCheck'])) {
			foreach($_POST['pluginCheck'] as $key=>$value) {
				$pluginChecks[$key] = $value;
			}
		}
		
		foreach ($this->pluginList as $plugin) {
			if((!count($pluginChecks)) || (isset($pluginChecks[$plugin]))) {
				$this->createPlugin($plugin);
			}
		}
		
		return $this->plugins;
	}

	function & createPlugin($pluginname, $debug = false) {
		global $database;
		if (!count($this->pluginList)) {
			$this->getPlugins();
		}
		if (in_array($pluginname, $this->pluginList)) {
			migratorInclude('plugins/' . $pluginname);
			$classname = $pluginname . '_etl';
			$this->plugins[$pluginname] = new $classname ($database);
			return $this->plugins[$pluginname];
		}
		return false;
	}

	function & getPlugin($pluginname) {
		if (isset ($this->plugins[$pluginname])) {
			return $this->plugins[$pluginname];
		}
		if (in_array($pluginname, $this->pluginList)) {
			return $this->createPlugin($pluginname);
		}
		return false;
	}
}

/**
 * Base type of a task
 */
class Task extends mosDBTable {
	var $taskid = 0;
	var $tablename = '';
	var $start = 0;
	var $amount = 0;
	var $tasksremaining = 0;

	function Task(& $db, $table = '', $s = 0, $a = 0, $t = null,$tr=0) {
		$this->tablename = $table;
		$this->start = $s;
		$this->total = $t ? $t : $a;
		$this->amount = $a ? $a : $this->total - $this->start;
		$this->tasksremaining = ($tr >= $this->amount) ? $tr :  $this->amount;
		$this->mosDBTable('#__migrator_tasks', 'taskid', $db);
	}

	function toString() {
		return _BBKP_TASK . $this->taskid . _BBKP_TABLE . $this->tablename . _BBKP_START . $this->start . _BBKP_AMOUNTTOCONVERT . $this->amount . _BBKP_TOTALROWS . $this->total . ';<br />';
	}

	function execute($outputfile=null) {
		global $run_time, $startTime;
		echo '<p>'. _BBKP_EXECTASK .  $this->toString() .'</p>';
		$enumerator = new ETLEnumerator();
		$plugin = $enumerator->createPlugin($this->tablename);
		if($plugin === false) {
			$this->delete(); // clean up this task
			echo '<p>'._BBKP_PLUGINCREATEFAILURE. $this->tablename.'</p>';
			return false;
		}		
		if(!$this->amount || $this->start > $this->amount) { $this->delete(); return false; }
		if($this->start == 0) {
			if($outputfile) 	$outputfile->writeFile($plugin->getSQLPrologue()); else echo $plugin->getSQLPrologue().'<br />';
		}
		for ($i = $this->start; $i <= $this->amount; $i++) {
			// Ensure that we get at least one through
			$sql = $plugin->doTransformation($i,1);
			foreach($sql as $query) {
				if($outputfile) $outputfile->writeFile($query); else echo $query.'<br />';
			}
			$checkTime = mosProfiler :: getmicrotime();
			if (($checkTime - $startTime) >= $run_time) {
				$rows  = $i - $this->start;
				//4042917 3434450 tasks remaining
				$max = $this->amount;
				$remaining = $this->amount - $i;
				echo '<p>'. _BBKP_PROCESSED . $rows .  _BBKP_ROWS . '('. $this->start . _BBKP_TO . $i .') ' . _BBKP_OF . $this->tablename . _BBKP_BEFORETIMEOUT . ' ('. number_format((($i / $this->amount) * 100),2) . _BBKP_PERCOFTABLE . ')</p>';
				echo '<p>' . $remaining .' '. _BBKP_TASKSREMAINING . '</p>';
				//echo '<p>' . _BBKP_PROCESSED . $rows. _BBKP_ROWS . '('. $this->start . _BBKP_TO . $i .') '. _BBKP_OF . $this->tablename . _BBKP_BEFORETIMEOUT .' ('. number_format((($i / $this->amount) * 100),2) . _BBKP_PERCOFTABLE .'; ~'. $this->tasksremaining-$rows .' '. _BBKP_TASKSREMAINING . ';</p>'; // '. _BBKP_TIMESPENT . $checkTime - $startTime .'
				// Update this task
				$this->start = $i + 1;
				$this->store();
				//die('Updating a task due to timeout');
				$link = "index2.php?option=com_migrator&act=dotask";
				echo '<div id="proceed"><a href="'.$link.'">'._BBKP_NEXT.'</a></div>';
				// mark:javascript autoprogress
				echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',1000); progress('". _BBKP_NEXT ."');</script>\n";
				echo '</div>';
				flush();
				exit();
				return false;
			}
		}
		//echo 'Now deleting task outside loop';
		if($outputfile) 	$outputfile->writeFile($plugin->getSQLEpilogue()); else echo $plugin->getSQLEpilogue().'<br />';

		$this->delete() or die($database->_db->getErrorMsg());
		return true;
	}
}

/**
 * Task Builder
 * Creates a list of tasks
 */
class TaskBuilder {
	/** @var $db Local DB reference */
	var $db = null;
	/** @var $plugins Local ETL Enumerator */
	var $plugins = null;
	/** @var $tasklist List of tasks */
	var $tasklist = Array ();
	/** @var $tasksremaining Number of tasks remaining over all */
	var $tasksremaining = 0;
	
	function TaskBuilder(& $database, & $plugins) {
		$this->db = & $database;
		$this->plugins = & $plugins;
	}

	function buildTaskList($debug = false) {
		$this->countAllRows();
		foreach ($this->plugins as $name => $plugin) {
			if ($debug)
				echo _BBKP_EXAMINING . $name . '<br />';
			$this->tasklist[] = new Task($this->db, $this->plugins[$name]->getAssociatedTable(), 0, $this->plugins[$name]->getEntries(), $this->tasksremaining);
		}
		return $this->tasklist;
	}

	function saveTaskList($debug = false) {
		if (!count($this->tasklist)) {
			$this->buildTaskList();
		}
		foreach ($this->tasklist as $task) {
			$this->db->setQuery("INSERT INTO #__migrator_tasks VALUES (0,'" . $task->tablename . "','" . $task->start . "','" . $task->amount . "','" . $task->amount . "')");
			$this->db->Query() or die($this->db->getErrorMsg());
		}
	}
	
	function countAllRows() { 
		$this->db->setQuery("SELECT sum(total-start) FROM #__migrator_tasks");
		$this->tasksremaining = $this->db->loadResult();
	}
}

/**
 * Task Execution System
 */
class TaskList {
	/** @var $db Local DB reference */
	var $db = null;

	function TaskList(& $database) {
		$this->db = & $database;
	}

	function & getNextTask() {
		$this->db->setQuery("SELECT taskid FROM #__migrator_tasks ORDER BY taskid LIMIT 0,1");
		$taskid = $this->db->loadResult();// or die('Failed to find next task: ' . $this->db->getErrorMsg());
		$false = false;
		if(!$taskid) return $false;
		$task = new Task($this->db);
		if($task->load($taskid)) return $task; else return $false; //die('Task '. $taskid .' failed to load:'. print_r($this,1));
	}

	function listAll() {
		$this->db->setQuery("SELECT taskid FROM #__migrator_tasks ORDER BY taskid");
		$results = $this->db->loadResultArray();
		$task = new Task($this->db);
		foreach ($results as $result) {
			$task->load($result);
			echo $task->toString();
		}
	}
	
	function countTasks() {
		$this->db->setQuery("SELECT count(*) FROM #__migrator_tasks");
		return $this->db->loadResult();
	}
}

