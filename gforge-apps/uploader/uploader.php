#!/usr/bin/env php
<?php

if($argc < 3) {
	echo("Usage: php uploader.php [section] [identifier] filenames\n");
	echo("Example: php uploader.php frsrelease 1234 pie.png\n");
	die();
}

$valid_sections = Array('frsrelease');
$args = $argv; // make a copy of this
array_shift($args); // remove the file name
$section = array_shift($args);
$frs_release_id = intval(array_shift($args));

if(!file_exists(dirname(__FILE__).'/../common/user_config.php')) {
	die("failed to load config file!");
}
include(dirname(__FILE__).'/../common/user_config.php'); 
include(dirname(__FILE__).'/../common/gforgeconnector.php');
$config = new Config();
$client = new GForgeConnector($config->site);
echo "Logging into GForge\n";
$client->login($config->username, $config->password) or die('Error logging in: '. $client->getError() ."\n\n");


foreach($args as $filename) {
		echo "Adding ". basename($filename) . "\n";
		$result = $client->addFilesystem('frsrelease', $frs_release_id, basename($filename), '', base64_encode(file_get_contents($filename)));
		if($result) {
			echo "Added new filesystem object $result\n";	
		} else {		
			echo 'Error:'. $client->getError() ."\n";
		}
}


$client->logout();

