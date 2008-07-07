<?php
/**
* @version		$Id: author.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @subpackage	Articles
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Renders a author element
 *
 * @package 	Joomla
 * @subpackage	Articles
 * @since		1.5
 */
class JElementdomaincheck extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'DomainCheck';

	function fetchElement($name, $value, &$node, $control_name)
	{
		JHTML::script('checker.js','plugins/system/canonicalization/', false);
		$html = '';
		// TODO: Replace this with the correct function call when I remember it
		$html .= '<p><a href="javascript:validateDomain(\''. str_replace('administrator','',JURI::base()) .'\');">Check Setting</a></p>';
		$html .= '<iframe name="DomainCheckFrame" id="DomainCheckFrame" width="200" height="300" src="about:blank" frameborder="0" scrolling="no">You need iframe support to use this feature.</iframe>';
		return $html;
	}
}