<?php
/**
* @version $Id: toolbar.migrator.php 2006-05-25 23:00
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

require_once($mainframe->getPath('toolbar_html'));
require_once($mainframe->getPath('toolbar_default'));

switch ($task) {
  case 'showAbout':
       TOOLBAR_migrator::_ABOUT($option);
        break;
  case 'showInfo':
       TOOLBAR_migrator::_INFO($option);
        break;
  default:
        TOOLBAR_migrator::_DEFAULT();
    break;
}

