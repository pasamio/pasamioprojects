<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Jul 7, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Developer Name 
 * @version SVN: $Id:$
 * @see http://joomlacode.org/gf/project/   JoomlaCode Project:    
 */
 
if(isset($_REQUEST['target'])) {
	$correct_host = $_REQUEST['target'];
	if(@$_SERVER['HTTP_HOST'] == $correct_host || @$_SERVER['SERVER_NAME'] == $correct_host) {
		echo '<p>Success!</p>';
	} else {
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
				$url = 'https://';
		} else {
				$url = 'http://';
		}
		$url .= $correct_host . $_SERVER['REQUEST_URI'];
		echo '<p>Failure! Domains dont match; <a href="'.$url.'">Attempt rewrite?</a></p>';
	}
} else { echo 'No target'; }
?>