<?php

$dbo =& JFactory::getDBO();
$dbo->setQuery('SELECT params FROM #__plugins WHERE folder = "authentication" AND element = "ldap"');
$paramstring = $dbo->loadResult() or die($dbo->getErrorMsg());
if(!$paramstring) die('Failed to get a param string!');

$params = new JParameter($paramstring);

$ldap = new JLDAP($params);

if(!$ldap->connect()) {
	echo '<p>Failed to connect to LDAP server: '. $ldap->getErrorMsg() . '</p>';
	return false;
}

if(!$ldap->bind()) {
	echo '<p>Failed to bind to LDAP server: '. $ldap->getErrorMsg() .'</p>';
	return false;
}

echo '<p>Successfully bound to LDAP server</p>';


