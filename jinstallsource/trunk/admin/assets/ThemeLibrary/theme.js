// change this variable to update the theme directory
var ctThemeXPBase = '/administrator/components/com_jpackagedir/assets/ThemeLibrary/';

// theme node properties
var ctThemeLibrary =
{
  	// tree attributes
  	//
	// except themeLevel, all other attributes can be specified
	// for each level of depth of the tree.

  	// HTML code to the left of a folder item
  	// first one is for folder closed, second one is for folder opened
	folderLeft: [['<img alt="" src="' + ctThemeXPBase + 'folder.gif" />', '<img alt="" src="' + ctThemeXPBase + 'folderopen.gif" />']],
  	// HTML code to the right of a folder item
  	// first one is for folder closed, second one is for folder opened
  	folderRight: [['', '']],
	// HTML code for the connector
	// first one is for w/ having next sibling, second one is for no next sibling
	// then inside each, the first field is for closed folder form, and the second field is for open form
	folderConnect: [[['',''],['','']]],

	// HTML code to the left of a regular item
	itemLeft: ['<img alt="" src="' + ctThemeXPBase + 'page.gif" />'],
	// HTML code to the right of a regular item
	itemRight: [''],
	// HTML code for the connector
	// first one is for w/ having next sibling, second one is for no next sibling
	itemConnect: [['','']],

	// HTML code for spacers
	// first one connects next, second one doesn"t
	spacer: [['&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;']],

	// deepest level of theme style sheet specified
	themeLevel: 1
};
