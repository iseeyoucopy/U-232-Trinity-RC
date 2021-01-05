<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// List containing the registered chat users:
//$users = array();
/*
if ($users === false || is_null($users)) {
	if(isset($CURUSER)){
	$all_users = sql_query('SELECT id, username, status, class, override_class FROM users WHERE id = '. $CURUSER['id'] . '');
    $all_users = $this->_fluent->from('users')
                               ->select(null)
                               ->select('id')
                               ->select('chatpost')
                               ->select('status')
                               ->select('class')
                               ->select('override_class');

    foreach ($all_users as $user) {
        $user_class = $user['override_class'] != 255 ? (int) $user['override_class'] : (int) $user['class'];
        $users[$user['id']]['userRole'] = $user_class;
		$users[$user['id']]['userName'] = $this->trimUserName($user['username']);
		
		if($user_class == UC_CEO){
			$users[$user['id']]['userRole'] = AJAX_CHAT_SYSOP;
		}elseif ($user_class == UC_ADMINISTRATOR){
			$users[$user['id']]['userRole'] = AJAX_CHAT_ADMIN;			
		}elseif ($user_class == UC_MODERATOR){
			$users[$user['id']]['userRole'] = AJAX_CHAT_MODERATOR;
		}elseif ($user_class == UC_UPLOADER){
			$users[$user['id']]['userRole'] = AJAX_CHAT_UPLOADER;
		}elseif ($user_class == UC_VIP){
			$users[$user['id']]['userRole'] = AJAX_CHAT_VIP;
		}elseif ($user_class == UC_POWER_USER){
			$users[$user['id']]['userRole'] = AJAX_CHAT_POWER_USER;
		}else if ($user_class == UC_USER){
			$users[$user['id']]['userRole'] = AJAX_CHAT_USER;
		}
		
		if($user_class == UC_SYSOP){
			$users[$user['id']]['channels'] = $TRINITY20['ajax_chat']['sysop_access'];
		}elseif ($user_class == UC_ADMINISTRATOR){
			$users[$user['id']]['channels'] = $TRINITY20['ajax_chat']['staff_access'];
		}elseif ($user_class == UC_MODERATOR){
			$users[$user['id']]['channels'] = $TRINITY20['ajax_chat']['staff_access'];
		}else {
			$users[$user['id']]['channels'] = $TRINITY20['ajax_chat']['staff_access'];
		}	
    }
	}
}*/
/*
// Default guest user (don't delete this one):
$users[0] = array();
$users[0]['userRole'] = AJAX_CHAT_GUEST;
$users[0]['userName'] = null;
$users[0]['password'] = null;
$users[0]['channels'] = array(0);

$users[1] = array();
$users[1]['userRole'] = AJAX_CHAT_SYSOP;
$users[1]['userName'] = 'sysop';
$users[1]['password'] = 'sysop';
$users[1]['channels'] = array(1,2,3,4);

$users[2] = array();
$users[2]['userRole'] = AJAX_CHAT_ADMIN;
$users[2]['userName'] = 'admin';
$users[2]['password'] = 'admin';
$users[2]['channels'] = array(1,2,3,4);

$users[3] = array();
$users[3]['userRole'] = AJAX_CHAT_MODERATOR;
$users[3]['userName'] = 'moderator';
$users[3]['password'] = 'moderator';
$users[3]['channels'] = array(1,2,3,4);

$users[4] = array();
$users[4]['userRole'] = AJAX_CHAT_UPLOADER;
$users[4]['userName'] = 'uploader';
$users[4]['password'] = 'uploader';
$users[4]['channels'] = array(1,2,3);

$users[5] = array();
$users[5]['userRole'] = AJAX_CHAT_VIP;
$users[5]['userName'] = 'vip';
$users[5]['password'] = 'vip';
$users[5]['channels'] = array(1,2,3);

$users[6] = array();
$users[6]['userRole'] = AJAX_CHAT_POWER_USER;
$users[6]['userName'] = 'puser';
$users[6]['password'] = 'puser';
$users[6]['channels'] = array(1,2,3);

$users[7] = array();
$users[7]['userRole'] = AJAX_CHAT_USER;
$users[7]['userName'] = 'user';
$users[7]['password'] = 'user';
$users[7]['channels'] = array(1,2,3);
*/
?>