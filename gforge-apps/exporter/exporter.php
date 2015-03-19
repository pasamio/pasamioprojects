<?php
include(dirname(__FILE__).'/../common/user_config.php');
include(dirname(__FILE__).'/../common/gforgeconnector.php');

$project = 'pasamioprojects';
$exportDirectory = '/tmp/joomlacode';

$config = new Config();
$client = new GForgeConnector($config->site, $config->soap_options);
$client->login($config->username, $config->password);
$user = $client->getUser();
$projects = $client->getUserProjects($config->username);

$allFiles = array();

if (!file_exists($exportDirectory))
{
	mkdir($exportDirectory, 0777, true);
}

foreach($projects as $project)
{
	$projectExportDirectory = $exportDirectory . '/' . $project->unix_name;
	if (!file_exists($projectExportDirectory))
	{
		mkdir($projectExportDirectory, 0777, true);
	}
	processProject($client, $project->unix_name, $allFiles, $projectExportDirectory);
	exportTracker($client, $project->project_id, $projectExportDirectory);
}

downloadFiles($client, $allFiles, $exportDirectory);

function exportTracker($client, $projectId, $exportDirectory)
{
	$trackers = $client->getTrackers($projectId);
	foreach($trackers as &$tracker)
	{
		$tracker->items = $client->getTrackerItems($tracker->tracker_id);
	}
	file_put_contents($exportDirectory . '/trackers.json', json_encode($trackers));
	return $trackers;
}

function processProject($client, $projectName, &$allFiles, $exportDirectory) {
	echo "Processing $projectName...";
	$project = $client->getProject($projectName);

	if (!is_object($project))
	{
		var_dump($project);
		echo "Expecting object for project!";
		return false;
	}
	$packages = $client->getFrsPackages($project->project_id);


	echo "Scanning packages...\n";
	foreach($packages as &$package) {
		echo "Scanning package {$package->package_name}...\n";
		$releases = $client->getFrsReleases($package->frs_package_id);
		$package->releases = $releases;
		$packageFiles = array();
		foreach($releases as &$release)
		{
			echo "Scanning release {$release->release_name}...";
			$release->files = $client->getFilesystems('frsrelease', $release->frs_release_id);
			echo "Found " . count($release->files) . " files\n";
			$allFiles = array_merge($allFiles, $release->files);
			$packageFiles = array_merge($packageFiles, $release->files);
		}
		echo "Finished package {$package->package_name} with " . count($packageFiles) . " files found.\n";
	}

	echo "Found " . count($allFiles) . " files in total\n";

	echo "Dumping packages.json file...\n";
	file_put_contents($exportDirectory . '/packages.json', json_encode($packages));
	echo "Dumping files.json file...\n";
	file_put_contents($exportDirectory . '/files.json', json_encode($allFiles));

	$project->packages = $packages;
	return $project;
}

function downloadFiles($client, $files, $exportDirectory)
{
	foreach($files as $file)
	{
		$target = $exportDirectory . '/' . $file->download_url;
		if (file_exists($target))
		{
			echo "File already exists: $target\n";
			continue;
		}

		$targetDirectory = dirname($target);
		if (!file_exists($targetDirectory))
		{
			mkdir($targetDirectory, 0777, true);
		}
		echo "Downloading {$file->file_name_safe} to $target...\n";
		file_put_contents($target, base64_decode($client->getFilesystemData($file->filesystem_id)->file_data));
	}
}


$client->logout();

//*/
