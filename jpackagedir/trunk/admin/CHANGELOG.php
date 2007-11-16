<?php
/**
 * @version 	$Id: CHANGELOG.php 165 2006-08-12 19:38:14Z willebil $
 * @package 	JPackageDir
 * @subpackage	J!Package Directory
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
- Initial release for the J!Update Directory component

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

ToTest (not complete)
=====================
 - Add, modify and delete (also list delete) of individual packages
 - Add, modify and delete projects
 - Add, modify and delete categories, including the categories parent/child
   logic.
 - All possible error situations within the code (exeption testing).
 - Saving packages, modify existing package with new url: needs to be same
   package information (name, version, type) or just throw error.

Todo (not complete)
===================
 + Add licence text to package info
 + Add severity code to package info
 + All @todo tags in code
 + Added an framework class layer extending the abstract class layer that is
   defined for database item retrieval (and storage). This is the first class
   that is supported within the framework.
 + Added an additional set of classes to be used within the extensions site
   of joomla.
 + Checking of project existence within package maintenance (control panel
   configurable)
 + Added configuration option for packages.
 + Added configuration option for projects.
 + Added configuration option for categories.
 ^ Proper toolbar handling for all basic list events (package, project, category)
 + Extra project fields in temp. project conversion.

 + Default category assignment on new categories
 + Add javascript verification for selecting at-least one category for a project

 ^ Image reference for package overview.

Changelog
=========
12-aug Wilco Jansen
 + Added Javascript menu for refactoring of all logic into one form.

11-aug Wilco Jansen
 ^ Replaced select option for categories with helper method for defining a full
   list of categories (very nice if i say so :-D)
 + Completed deletion logic for projects, including dependencies check for
   packages.
 ^ Fixed pageflow bug with cancel event for projects. 

08-aug Wilco Jansen
 ^ Fixed an error in query on project overview.
 + Project that are not assigned to one category are now visible in the project
   overview screen.
 + Modified the category selection in the project view, using the OPTGROUP
   for proper visual presentation of categories. 
 + Save categories in project details, including selection of categories is now
   implemented.

01-aug Wilco Jansen
 + Just added a beautifull recursive function into the helper class to retrieve
   all categories.
 + Added a multiple select box for categories selection in project details.

31-jul Wilco Jansen
 + Alter owner logic has been added for projects

30-jul Wilco Jansen
 + Added option for (un)publishing project items.
 + Added option for (un)publishing package items.
 + Added image reference to package type (just i minor visualisation)
 + Implemented a work-around for multiple publish/unpublish events for objects
   (project, category, package). Implemented a tailor made PublishedProcessing
   method for non default publish/unpublish events.
 ^ Simplified toolbar code for new/edit events of project/packages.
 + Check if project already exists on save

27-jul Wilco Jansen
 ^ Just some bug fixing in the project save method.

26-jul Wilco Jansen
 + Implemented save project, now basic storage of project info can be done.

25-jul Wilco Jansen
 ^ Finished packages form according to new ui design.
 + Started with project maintenance form (some new fields will be added).
 ^ Fixed column reference problem in projectlist.
 + Added some extra fields to the project table.
+^ Created an abstract class layer for data retrieval of all information needed, implemented full MVC logic now

24-jul Wilco Jansen
 + Added proper buttons for edit project event.
 + Cancel of project events returns to proper pre-selected project overview.
 + Add basic form lay-out according to new ui-design of Joomla! 1.5
 ^ Refactored UI design for packagesaccording to new ui standards of Joomla! 1.5

23-jul Wilco Jansen
 ^ Changed $offset and $limit within paging of list and listproject
 ^ Added category, owner and package selection to the project overview page.
 ^ Added configuration option in toolbar of packages, projects and categories
   (config option does not do anything atm)

09-jul Wilco Jansen
 + Completed save of directory entries (modify/add).
 + Completed deletion of entries logic, including dependencies.
 + Completed refactoring of extensionsdir component logic into the JPackageDir
   component, next we will add project logic to this component. 

06-jul Wilco Jansen
 + Implemented storing the individual package entries, including dependency
   information taken from the installer XML file.
 + Created a testset according to the new data model, available within test
   site where the project members can install the JPackage plugin needed for
   client side testing.

05-jul Wilco Jansen
 ^ Implemented checking/checkout for record "locking"

04-jul Wilco Jansen
 ^ Created JTableJPackageDirPackages class for table handling, stored in /table
   directory (awaiting framework adjustment for default support of logic).
 + Build the helper class for the package object containing all basic routines
   needed to to basic checkup routines, will also be used in front-end code.

01-jul Wilco Jansen
 + Updated wiki with new table information
 + Added item edit/addition, no wizard logic here, just a plain approach
   including all checks that need to be done to get a clean structured directory

29-jun Wilco Jansen
 + Added three new tables for framework (categories, projects and relations)

16-jun Wilco Jansen
 + Started with refactoring the com_extensionsdir into com_jpackagedir
