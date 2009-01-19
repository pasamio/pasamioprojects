#!/usr/bin/env php
<?php

if($argc < 3) {
	die("Usage: php uploader.php section identifier filenames\n");
}

$valid_sections = Array('frsrelease');
$args = $argv; // make a copy of this
array_shift($args); // remove the file name
$section = array_shift($args);
$frs_release_id = intval(array_shift($args));

include(dirname(__FILE__).'/../common/user_config.php');
include(dirname(__FILE__).'/../common/gforgeconnector.php');
$config = new Config();
$client = new GForgeConnector($config->site);
$client->login($config->username, $config->password) or die('Error logging in: '. $client->getError() ."\n");


foreach($args as $filename) {
		$result = $client->addFilesystem('frsrelease', $frs_release_id, basename($filename), '', base64_encode(file_get_contents($filename)));
		if($result) {
			echo "Added new filesystem object $result\n";	
		} else {		
			echo 'Error:'. $client->getError() ."\n";
		}
}


$client->logout();

