<?php
include(dirname(__FILE__).'/../common/user_config.php');
include(dirname(__FILE__).'/../common/gforgeconnector.php');
$config = new Config();
$client = new GForgeConnector($config->site);
$client->login($config->username, $config->password);
//$user = $client->getUser();
$project = $client->getProject('joomla');
$packages = $client->getFrsPackages($project->project_id);
// Mock this below, creation once is enough
//$frs_package_id = $client->addFrsPackage($project->project_id, 'Test SOAP Package', 1, 1, 1);
//$frs_package_id = 4059; // TestSOAPPackage (write testing)
//$frs_package_id = 2588; // Migrator (read testing)

//$frs_release_id = $client->addFrsRelease($frs_package_id, 'Test', 'This is a test release', 'Testing SOAP');
//$frs_release_id = 8707; // Test release for TestSOAPPackage
//$filename = 'Meat_pie-small.png';
//var_dump($client->addFilesystem('frsrelease', $frs_release_id, $filename, '', base64_encode(file_get_contents($filename))));
//$filesystem_id = 32092;
//var_dump($client->getFilesystem($filesystem_id));
$last = null;

foreach($packages as $package) {
	if($last == null) {
		$last = $package;
	} else {
		if($package->is_public && $package->status_id && $package->frs_package_id > $last->frs_package_id && strpos($package->package_name, '1.5')) {
			$last = $package;
		}
	}	
}

if($last) {
	$package = $last;
	header('Content-type: text/xml');
	$joomla_version = str_replace('Joomla', '', $package->package_name);
	$minver = '1.5.1';
	$curver = '1.5.1';
	$updateurl = 'http://joomlacode.org/gf/project/pasamioprojects/frs/';
	$message = '';
	echo '<?xml version="1.0" ?>'."\n";
	echo '<update release="'. $joomla_version .'">'."\n";
	echo '<message>'. $message .'</message>'."\n";
	echo '<updater minimumversion="'. $minver .'" currentversion="'. $curver .'">'. $updateurl .'</updater>'."\n";
	$releases = $client->getFrsReleases($package->frs_package_id);
	
	foreach($releases as $release) {
		$update = 0;
		if(strpos($release->release_name, 'updates')) {
			$update = 1;
			echo '<patches>'."\n";
		}
		$files = $client->getFilesystems('frsrelease', $release->frs_release_id);
		foreach($files as $file) {
			if(substr($file->file_name, -6) == 'tar.gz') {
				if($update) {
					$version_parts = explode('_', $file->file_name);
					echo "\t".'<patchpackage version="'. $version_parts[1] .'" url="http://joomlacode.org'. $file->download_url.'" filename="'. $file->file_name_safe .'" filesize="'. $file->file_size .'" md5="'. $file->md5_hash .'" />'."\n";	
				} else {
					echo '<fullpackage url="http://joomlacode.org'. $file->download_url .'" filename="'. $file->file_name_safe.'" filesize="'. $file->file_size .'" md5="'. $file->md5_hash .'" />'."\n";
				}
			}
		}
		if($update) {
			echo  '</patches>'."\n";
		}
	}
	echo '</update>'."\n";
} else {
	echo 'Failed to find a valid package';
}

$client->logout();


//*/
