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
	JToolbarHelper::title('User Loader - Loading Users');
	$users_text = JRequest::getVar('users',null);
	if(!$users_text) {
		echo 'No users specified!';
		return;
	}
	$users = explode("\n", $users_text);
	$sendmail = JRequest::getVar('sendmail', false);
	// Pull the user language
	$lang =& JFactory::getLanguage();
	$lang->load('com_user');
	$me =& JFactory::getUser();
	
	$app =& JFactory::getApplication();
	$MailFrom	= $app->getCfg('mailfrom');
	$FromName	= $app->getCfg('fromname');
	$SiteName	= $app->getCfg('sitename');
		
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
        $user_details[4] = trim($user_details[4]);
        $salt  = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword($user_details[4], $salt);
        $user->password = $crypt.':'.$salt;
        $user->password_clear = $user_details[4];
		if(!$user->save()) {
			echo '<p>User Store Failed: '. $user->getError() .'</p>';
		} else if($sendmail) {
			$adminEmail = $me->get('email');
			$adminName	= $me->get('name');

			$subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
			$message = sprintf ( JText::_('NEW_USER_MESSAGE'), $user->get('name'), $SiteName, JURI::root(), $user->get('username'), $user->password_clear );

			if ($MailFrom != '' && $FromName != '')
			{
				$adminName 	= $FromName;
				$adminEmail = $MailFrom;
			}
			JUtility::sendMail( $adminEmail, $adminName, $user->get('email'), $subject, $message );
		}
	}
	echo '<b>Done.</b></div>';
	echo '<p><a href="index.php?option=com_userloader">Load more users?</a></p>';
}

function getGroupIdFromName($name) {
	static $cache = null;
	if(empty($cache)) {
		$cache = Array();
	}

	if(!array_key_exists($name, $cache)) {
		$database =& JFactory::getDBO();
		$database->setQuery("SELECT id FROM #__core_acl_aro_groups WHERE name = '$name'");	
		$result = $database->loadResult() ;
		if(!$result) {
			$cache[$name] = 18;
		} else {
			$cache[$name] = $result;
		}
	}
	return $cache[$name];
}

function displayMessage() {
	JToolbarHelper::title('User Loader');
	?>
	<p>Input Format: Full Name,Username,Email Address,Group Name,Password</p>
	<form action="index.php?option=com_userloader&task=load" method="post">
	<table><tr><td valign="top">Users:</td><td><textarea cols="100" rows="10" name="users" id="users"></textarea></td></tr>
	<tr><td colspan="2"><input type="checkbox" name="sendmail" />Send Emails</td></tr>
	<tr><td colspan="2"><input type="submit" value="Load Users" /></td></tr></table>
	<?php
}


