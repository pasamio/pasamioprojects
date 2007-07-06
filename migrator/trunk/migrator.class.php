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
 * @package JMigrator
 * @author Sam Moffatt <S.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */

// no direct access
defined('_VALID_MOS') or die('Restricted access');

/**
 * Migrator Include Function
 * Short hand to referencing back to this plugin
 */
function migratorInclude($file) {
	global $mosConfig_absolute_path;
	$file = migratorBasePath() . $file . '.php';
	if (file_exists($file)) {
		require_once ($file);
	} else
		die('CRITICAL ERROR: Failed attempt to include:' . $file);
}

/**
 * Displays a file from the resource directory
 * @param $file string Filename to display
 */
function displayResource($file) {
	$file = migratorBasePath() . '/resources/' . $file . '.html';
	if (file_exists($file)) {
		echo '<div align="left" style="border: 1px solid black; padding: 5px; ">';
		include ($file);
		echo '</div>';
	} else
		die('CRITICAL ERROR: Failed attempt to include:' . $file);
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
 * ETL Plugin
 * An ETL Plugin examines the database and returns SQL queries
 */
class ETLPlugin {
	/** @var $db Local Database Object */
	var $db = null;

	/** @var $fieldlist List of fields to ignore */
	var $ignorefieldlist = Array ();

	/** @var $valuesmap List of field values that need mapping/transformation */
	var $valuesmap = Array ();

	/** @var $namesmap List of field names that need mapping/transformation */
	var $namesmap = Array ();

	function ETLPlugin(& $database) {
		$this->db = $database;
	}

	function toString() {
		return $this->getName() . '; Transforms table ' . $this->getAssociatedTable() . ' to ' . $this->getTargetTable() . '<br />';
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
		$this->db->setQuery('SELECT count(*) FROM #__' . $this->getAssociatedTable());
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
	 * Does the transformation from start to amount rows.
	 */
	function doTransformation($start, $amount) {
		$this->db->setQuery('SELECT * FROM #__' . $this->getAssociatedTable() . ' LIMIT ' . $start . ',' . $amount);
		$retval = Array ();
		$results = $this->db->loadAssocList();
		foreach ($results as $result) {
			$fieldvalues = '';
			$fieldnames = '';
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
				$fieldvalues .= '\'' . mysql_real_escape_string($value) . '\'';
				$fieldnames .= '`' . $key . '`';
			}
			$retval[] = 'INSERT INTO jos_' . $this->getTargetTable() . ' (' . $fieldnames . ')' .
			' VALUES ( ' . $fieldvalues . ');'."\n";
		}
		return $retval;
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
					echo 'Found ' . $file . '<br />';
				$this->pluginList[] = str_replace('.php', '', $file);
			}
		}
		closedir($dir);
		return $this->pluginList;
	}

	function includePlugins($debug = false) {
		if (!count($this->pluginList)) {
			$this->getPlugins();
		}
		foreach ($this->pluginList as $plugin) {
			if ($debug)
				echo 'Including ' . $plugin . '<br />';
			migratorInclude('plugins/' . $plugin);
		}
	}

	function & createPlugins($debug = false) {
		if (!count($this->pluginList)) {
			$this->getPlugins();
		}
		foreach ($this->pluginList as $plugin) {
			$this->createPlugin($plugin);
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

	function Task(& $db, $table = '', $s = 0, $a = 0, $t = null) {
		$this->tablename = $table;
		$this->start = $s;
		$this->amount = $a;
		$this->total = $t ? $t : $a;
		$this->mosDBTable('#__migrator_tasks', 'taskid', $db);
	}

	function toString() {
		return 'Task #' . $this->taskid . '; Table: ' . $this->tablename . '; Start: ' . $this->start . '; Amount to convert: ' . $this->amount . '; Total Rows: ' . $this->total . ';<br />';
	}

	function execute($outputfile=null) {
		global $run_time, $startTime;
		echo '<p>Executing Task: '. $this->toString() .'</p>';
		if(!$this->amount) { $this->delete(); return false; }
		for ($i = 0; $i <= $this->amount; $i++) {
			// Ensure that we get at least one through
			$enumerator = new ETLEnumerator();
			$plugin = $enumerator->createPlugin($this->tablename) or die('Failed to create plugin: '. $this->tablename);
			$sql = $plugin->doTransformation($this->start+$i,1);
			foreach($sql as $query) {
				if($outputfile) $outputfile->writeFile($query); else echo $query.'<br />';
			}
			$checkTime = mosProfiler :: getmicrotime();
			if (($checkTime - $startTime) >= $run_time) {
				// Update this task
				$this->start = $this->start + $i;
				$this->store();
				$link = "index2.php?option=com_migrator&act=dotask";
				echo "<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"" . $link . "\";',500);</script>\n";
				flush();
				die();
			}
			$this->delete() or die($database->_db->getErrorMsg());
		}
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

	function TaskBuilder(& $database, & $plugins) {
		$this->db = & $database;
		$this->plugins = & $plugins;
	}

	function buildTaskList($debug = false) {
		foreach ($this->plugins as $name => $plugin) {
			if ($debug)
				echo 'Examining ' . $name . '<br />';
			$this->tasklist[] = new Task($this->db, $this->plugins[$name]->getAssociatedTable(), 0, $this->plugins[$name]->getEntries());
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
		if(!$taskid) return false;
		$task = new Task($this->db);
		if($task->load($taskid)) return $task; else return false; //die('Task '. $taskid .' failed to load:'. print_r($this,1));
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
?>