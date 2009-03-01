<?php
/**
* @version $Id: toolbar.migrator.html.php 2006-05-25 23:00
* @package Migrator
* @copyright Copyright (C) 2006 by Mambobaer.de. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_VALID_MOS') or die('Restricted access');

class TOOLBAR_migrator_legacy {

  function _ABOUT($option) {
    mosMenuBar::startTable();
    mosMenuBar::back("Back", "index2.php?option=$option");
    mosMenuBar::endTable();
  }
  function _INFO($option) {
    mosMenuBar::startTable();
    mosMenuBar::back("Back", "index2.php?option=$option");
    mosMenuBar::endTable();
  }
  function _DEFAULT() {
    mosMenuBar::startTable();
    mosMenuBar::custom('makeDumps', '../components/com_migrator/images/backup.png', '../components/com_migrator/images/backup_f2.png', 'Dump It', false);
    mosMenuBar::spacer();
    mosMenuBar::custom('showAbout', '../components/com_migrator/images/info.png', '../components/com_migrator/images/info_f2.png', 'About', false);
    mosMenuBar::spacer();
    mosMenuBar::custom('testPlugin', '../components/com_migrator/images/info.png', '../components/com_migrator/images/info_f2.png', 'Test ETL Plugin', false);
    mosMenuBar::endTable();
  }
}

