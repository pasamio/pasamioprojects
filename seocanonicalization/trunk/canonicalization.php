<?php
/**
 * SEO Canonicalizatoin
 * 
 * This ensures that a site is accessible only via a single location by
 * determining the HTTP_HOST 
 * 
 * PHP4/5
 *  
 * Created on Apr 17, 2007
 * 
 * @package JAuthTools
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects/
 */

jimport('joomla.plugin.plugin');

/**
 * SEO Canonicalization
 * Reconfigures site locations
 */
class plgSystemCanonicalization extends JPlugin {
	
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @since 1.5
	 */
	function plgSystemCanonicalization(& $subject, $config) {
		parent :: __construct($subject, $config);
	}

	function onAfterInitialise() {
		$correct_host = $this->params->get('correct_host','');
		if(!$correct_host) return false;
		
		// This should probably be filtered
		if(@$_SERVER['HTTP_HOST'] == $correct_host || @$_SERVER['SERVER_NAME'] == $correct_host) {
			return true;	
		}
		
		// And this too
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
				$url = 'https://';
		} else {
				$url = 'http://';
		}
		// And maybe this, though
		$url .= $correct_host . $_SERVER['REQUEST_URI'];
		header('Location: '. $url, true, 301);
		$app        = & JFactory::getApplication();
		$app->close();
	}
}
