<?php
/**
 * Short Description
 * 
 * Long Description
 * 
 * MySQL 4.0/4.1/5.0
 * PHP4/5
 *  
 * Created on 12/06/2007
 * 
 * @package JMigrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

if (!function_exists('ldap_connect')) {
	die('LDAP: authentication (crit) PHP LDAP Library not detected');
} else if (!class_exists('ldapConnector')) {
	die('LDAP: authentication (crit) Joomla! LDAP Library not detected');
}
?>
