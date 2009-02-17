<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.user.helper');

$lang =& JFactory::getLanguage();
$lang->load('com_users');

$task = JRequest::getCMD('task',null);

switch($task) {
        case 'load':
                loadUsers();
                break;
        default:
                displayMessage();
                break;
}

function loadUsers() {
	echo 'Load Users';
	$users_text = JRequest::getVar('users',null);
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
		$user = new JUser();
        $user->id = 0;
        $user->name = $user_details[0];
        $user->username = $user_details[1];
        $user->gid = getGroupIdFromName($user_details[3]);
        $user->usertype = $user_details[3];
        $user->email = $user_details[2];
        if(!isset($user_details[4]) || empty($user_details[4])) {
        	$user_details[4] = JUserHelper::genRandomPassword();
        	echo '<p>Assigning user password: '. $user_details[4] .'<p>';
        }
        $salt  = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword($user_details[4], $salt);
        $user->password = $crypt.':'.$salt;
        $user->password_clear = $user_details[4];
		if(!$user->save()) {
			echo '<p>User Store Failed: '. $user->getError() .'</p>';
		}
	}
	echo '<b>Done.</b></div>';
}

function getGroupIdFromName($name) {
	$database =& JFactory::getDBO();
	$database->setQuery("SELECT id FROM #__core_acl_aro_groups WHERE name = '$name'");	
	$result = $database->loadResult() ;
	if(!$result) {
		return 18;
	}
	return $result;
}

function displayMessage() {
	echo 'User Loader';
	echo '<form action="index.php?option=com_userloader&task=load" method="post">';
	echo '<table><tr><td valign="top">Users:</td><td><textarea cols="100" rows="10" name="users" id="users"></textarea></td></tr>';
	echo '<tr><td colspan="2"><input type="submit" value="Load Users"></td></tr></table>';
}


