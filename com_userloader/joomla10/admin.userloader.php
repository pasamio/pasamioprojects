<?php

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$task = mosGetParam($_GET,'task',null);
switch($task) {
        case 'load':
                loadUsers();
                break;
        default:
                displayMessage();
                break;
}

function loadUsers() {
	global $database;
	echo 'Load Users';
	$users_text = mosGetParam($_POST,'users',null);
	if(!$users_text) {
		echo 'No users specified!';
		return;
	}
	$users = explode("\n", $users_text);
	echo '<div align="left"><b>Loading users...</b><br>';
	foreach($users as $user) {
		$user_details = explode(',',$user);
		$user_details[3] = trim($user_details[3]);		
		echo '<div align="left" style="left-padding: 10px;">Name: '. $user_details[0] .'<br>';
		echo 'Username: '.$user_details[1].'<br>';
		echo 'Email: '.$user_details[2].'<br>';
		echo 'Group: '.$user_details[3].'<br>';
		echo 'Group ID: '.getGroupIdFromName($user_details[3]).'<br></div>';
		$user = new mosUser($database);
        $user->id = 0;
        $user->name = $user_details[0];
        $user->username = $user_details[1];
        $user->gid = getGroupIdFromName($user_details[3]);
        $user->usertype = $user_details[3];
        $user->email = $user_details[2];
        $user->password = trim($user_details[4]);
		$user->store();
	}
	echo '<b>Done.</b></div>';
}

function getGroupIdFromName($name) {
	global $database;
	$database->setQuery("SELECT group_id FROM #__core_acl_aro_groups WHERE name = '$name'");	
	$result = $database->loadResult() ;
	if(!$result) {
		return 18;
	}
	return $result;
}

function displayMessage() {
	echo 'User Loader';
	echo '<form action="index2.php?option=com_userloader&task=load" method="post">';
	echo '<table><tr><td valign="top">Users:</td><td><textarea cols="100" rows="10" name="users" id="users"></textarea></td></tr>';
	echo '<tr><td colspan="2"><input type="submit" value="Load Users"></td></tr></table>';
}
?>

