<?php
/**
 * @version     $Id: CHANGELOG.php 202 2006-08-20 07:05:55Z schmalls $
 * @package     Joomla
 * @subpackage  J!Package Directory
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
1. Copyright and disclaimer
---------------------------
This application is opensource software released under the GPL.  Please
see source code and the LICENSE file

2. Release summary
------------------
- Initial release for the JPackage component

3. Changelog
------------
This is a non-exhaustive changelog

Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

Changelog
=========

2006-08-19 Joshua Thompson
 + added update fetching screens
 # fixed some bugs in the jpackage.getLatest method
 # fixed bugs in the dependency fetching code
 # fixed update to work with plugins and templates
 + added search model and view
 # fixed bugs in the jpackage.searchPackages function
 ! did some testing of the implemented functionality
 ! need to come up with some better testing extensions, especially languages and templates

2006-08-18 Joshua Thompson
 + added functions to get currently installed extension details
 ! set up two test servers php5.test.countercubed.com/joomla/xmlrpc/ and test.countercubed.com/joomla/xmlrpc/

2006-08-17 Joshua Thompson
 + added the "Sites" model and view and moved the getSite and getSites method from category to it
 + implemented site addition and editing screens

2006-08-16 Joshua Thompson
 + added toolbars
 ^ changed the "browse for packages" start page to show a site selection page
 ! changed the '/xmlrpc/includes/framework.php' file to jinclude 'joomla.database.table.user'
 ! added the icon_32_forward class to use the icon in the toolbar
 ! added searchtext icon to the khepri images

2006-08-15 Joshua Thompson
 ! created testing extensions (not in SVN)
 # fixed bugs in component, module, and language updates
 ! plugin and template updates will still not work (will have to change the install function some more)
 ! fixed josRedirect strpos error

2006-08-01 Joshua Thompson
 + added com_jpackage to the extensions menu
 ^ changed text for com_installer in the extensions menu
 + added com_jpackage to mod_submenu
 + added "Update" model and view to com_jpackage

2006-07-29 Joshua Thompson
 + addes $is_core parameter to JInstallerHelper::unpack()
 + added JInstallerCore to update core (needs further implementation)
 + implemented core model and view functions (needs testing)

2006-07-28 Joshua Thompson
 + added language, module, plugin, and template update methods
 + added _xmldocInstalled and installedFile to installer class

2006-07-27 Joshua Thompson
 # fixed bugs in the component update function
 ^ fixed the plugin to work with the apparently changed xml rpc

2006-07-11 Joshua Thompson
 + added component update method

2006-07-10 Joshua Thompson
 ^ changed installation to more 1.5 compatible (removed queries from xml file)
 ! got language working finally

2006-07-06 Joshua Thompson
 + added username/password to xmlrpc class (only basic http auth supported currently)
 + added jpackage.getDependencies to XML-RPC
 + added (but not tested) package and dependency installation

2006-07-01 Joshua Thompson
 ^ changed to Model-View-Controller architecture
 + added (but not tested) package search functions

2006-06-24 Joshua Thompson
 + added remote repository browsing

2006-06-16 Joshua Thompson
 + initial import of com_jpackage