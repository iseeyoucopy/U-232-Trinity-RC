<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'password_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
require_once (CLASS_DIR . 'class_browser.php');
dbconn();
global $CURUSER, $TRINITY20;
if (!$CURUSER) {
    get_template();
} else {
    header("Location: {$TRINITY20['baseurl']}/index.php");
    exit();
}
session_start();
//smth putyn
$auth_key = array(
    '2d257f64005d740db092a6b91170ab5f'
);
$gotkey = isset($_POST['key']) && strlen($_POST['key']) == 32 && in_array($_POST['key'], $auth_key) ? true : false;
if (!$gotkey) {
    $newpage = new page_verify();
    $newpage->check('takelogin');
}
$lang = array_merge(load_language('global') , load_language('takelogin'));
// 09 failed logins thanks to pdq - Retro
function failedloginscheck()
{
    global $TRINITY20, $lang;
    $total = 0;
    $ip = getip();
    $res = sql_query("SELECT SUM(attempts) FROM failedlogins WHERE ip=" . sqlesc($ip)) or sqlerr(__FILE__, __LINE__);
    list($total) = $res->fetch_row();
    if ($total >= $TRINITY20['failedlogins']) {
        sql_query("UPDATE failedlogins SET banned = 'yes' WHERE ip=" . sqlesc($ip)) or sqlerr(__FILE__, __LINE__);
        stderr($lang['tlogin_locked'], "{$lang['tlogin_lockerr1']} . <b>(" . htmlsafechars($ip) . ")</b> . {$lang['tlogin_lockerr2']}");
    }
} // End
if (!mkglobal('username:password' . ($TRINITY20['captcha_on'] ? (!$gotkey ? ":captchaSelection:" : ":") : ":") . 'submitme')) die("{$lang['tlogin_sww']}");
if (!$submitme) stderr($lang['tlogin_err1'], $lang['tlogin_err2']);
if ($TRINITY20['captcha_on'] && !$gotkey) {
    if (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection) {
        header('Location: login.php');
        exit();
    }
}
function bark($text = 'Username or password incorrect')
{
    global $lang, $TRINITY20, $cache;
    $sha = sha1($_SERVER['REMOTE_ADDR']);
    $dict_key = 'dictbreaker:::' . $sha;
    $flood = $cache->get($dict_key);
    if ($flood === false) $cache->set($dict_key, 'flood_check', 20);
    else die("{$lang['tlogin_err4']}");
    stderr($lang['tlogin_failed'], $text);
}
failedloginscheck();
$res = sql_query("SELECT * FROM users WHERE username = " . sqlesc($username) . " AND status = 'confirmed'");
//$res = sql_query("SELECT id, ip, old_passhash, passhash, loginhash, perms, enabled, class FROM users WHERE username = " . sqlesc($username) . " AND status = 'confirmed'");
$row = $res->fetch_assoc();
$ip_escaped = sqlesc(getip());
$ip = getip();
$added = TIME_NOW;
if (!$row) {
    $fail = (@mysqli_fetch_row(sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped"))) or sqlerr(__FILE__, __LINE__);
    if ($fail[0] == 0) sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") or sqlerr(__FILE__, __LINE__);
    else sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") or sqlerr(__FILE__, __LINE__);
    bark();
}
/*=== check for dupe account by GodFather ===*/
if ($TRINITY20['dupeaccount_check_on'] == 1) {
	if(!empty(get_mycookie('log_uid'))){
		$check = sql_query("SELECT * FROM users WHERE loginhash=" . sqlesc(get_mycookie('log_uid')));
		$check_class = $check->fetch_assoc();
		if (($row['class'] < UC_SYSOP) && ($check_class['class'] < UC_SYSOP)){
            if((!empty($row['loginhash'])) && (get_mycookie('log_uid') == $row['loginhash']) && (password_verify($password, $row['passhash']))){
            
		    }else if((!empty($row['loginhash'])) && (get_mycookie('log_uid') != $row['loginhash']) && (password_verify($password, $row['passhash']))){
			    $a = sql_query("SELECT * FROM users WHERE loginhash=" . sqlesc(get_mycookie('log_uid')));
			    if($r = $a->fetch_assoc()){
                    $message = "User " . $r['username'] . " has logged in on another account. Has logged with User: " . $username . " credentials.";
				    write_log("User " . $r['username'] . " has logged in on another account. Has logged with User: " . $username . " credentials.");
				    sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (" . sqlesc($TRINITY20['bot_id']) . "," . sqlesc($TRINITY20['bot_name']) . "," . sqlesc($TRINITY20['bot_role']) . ",'4'," . sqlesc(TIME_DATE) . "," . sqlesc($_SERVER['REMOTE_ADDR']) . "," . sqlesc($message) . ")") or sqlerr(__FILE__, __LINE__);
			    }
			
		    }else if((!empty($row['loginhash'])) && (get_mycookie('log_uid') != $row['loginhash']) && (!password_verify($password, $row['passhash']))){
			    $b = sql_query("SELECT * FROM users WHERE loginhash=" . sqlesc(get_mycookie('log_uid')));
			    if($s = $b->fetch_assoc()){
                    $message = "User " . $s['username'] . " has tried to login on another account. Has tried to login with User: " . $username . " credentials.";
				    write_log("User " . $s['username'] . " has tried to login on another account. Has tried to login with User: " . $username . " credentials.");
				    sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (" . sqlesc($TRINITY20['bot_id']) . "," . sqlesc($TRINITY20['bot_name']) . "," . sqlesc($TRINITY20['bot_role']) . ",'4'," . sqlesc(TIME_DATE) . "," . sqlesc($_SERVER['REMOTE_ADDR']) . "," . sqlesc($message) . ")") or sqlerr(__FILE__, __LINE__);
			    }	
		    }		
        }			
    }
}	
/*=== end check for dupe account ===*/
$pass_hash = password_verify($password, $row['passhash']);
if (!$pass_hash) {
    $fail = (@mysqli_fetch_row(sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped"))) or sqlerr(__FILE__, __LINE__);
    if ($fail[0] == 0) sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") or sqlerr(__FILE__, __LINE__);
    else sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") or sqlerr(__FILE__, __LINE__);
    $to = ((int)$row["id"]);
    $subject = "{$lang['tlogin_log_err1']}";
    $msg = "[color=red]{$lang['tlogin_log_err2']}[/color]\n{$lang['tlogin_mess1']}" . (int)$row['id'] . "{$lang['tlogin_mess2']}" . htmlsafechars($username) . "{$lang['tlogin_mess3']}" . "{$lang['tlogin_mess4']}" . htmlsafechars($ip) . "{$lang['tlogin_mess5']}";
    $sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', " . sqlesc($to) . ", " . sqlesc($msg) . ", " . sqlesc($subject) . ", $added);";
    $res = sql_query("SET SESSION sql_mode = ''", $sql) or sqlerr(__FILE__, __LINE__);
    $cache->delete('inbox_new::' . $row['id']);
    $cache->delete('inbox_new_sb::' . $row['id']);
    bark("<b>{$lang['gl_error']}</b>{$lang['tlogin_forgot']}");
}
/*
if (($row['passhash'] == NULL) && ($row['old_passhash'] != make_pass_hash($row['secret'], md5($password)))) {
    $fail = (@mysqli_fetch_row(sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped"))) or sqlerr(__FILE__, __LINE__);
    if ($fail[0] == 0) sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") or sqlerr(__FILE__, __LINE__);
    else sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") or sqlerr(__FILE__, __LINE__);
    $to = ((int)$row["id"]);
    $subject = "{$lang['tlogin_log_err1']}";
    $msg = "[color=red]{$lang['tlogin_log_err2']}[/color]\n{$lang['tlogin_mess1']}" . (int)$row['id'] . "{$lang['tlogin_mess2']}" . htmlsafechars($username) . "{$lang['tlogin_mess3']}" . "{$lang['tlogin_mess4']}" . htmlsafechars($ip) . "{$lang['tlogin_mess5']}";
    $sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', " . sqlesc($to) . ", " . sqlesc($msg) . ", " . sqlesc($subject) . ", $added);";
    $res = sql_query("SET SESSION sql_mode = ''", $sql) or sqlerr(__FILE__, __LINE__);
    $cache->delete('inbox_new::' . $row['id']);
    $cache->delete('inbox_new_sb::' . $row['id']);
    bark("<b>{$lang['gl_error']}</b>{$lang['tlogin_forgot']}");
	
}else if (($row['passhash'] == NULL) && ($row['old_passhash'] == make_pass_hash($row['secret'], md5($password)))) {
	$newpassword = make_passhash($password);
	sql_query("UPDATE users SET passhash=" . sqlesc($newpassword) . ", old_passhash=NULL WHERE username = ". sqlesc($username));
	
} else if ((!$pass_hash) && ($row['old_passhash'] == NULL)) {
	$fail = (@mysqli_fetch_row(sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped"))) or sqlerr(__FILE__, __LINE__);
if ($fail[0] == 0) sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") or sqlerr(__FILE__, __LINE__);
else sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") or sqlerr(__FILE__, __LINE__);
$to = ((int)$row["id"]);
$subject = "{$lang['tlogin_log_err1']}";
$msg = "[color=red]{$lang['tlogin_log_err2']}[/color]\n{$lang['tlogin_mess1']}" . (int)$row['id'] . "{$lang['tlogin_mess2']}" . htmlsafechars($username) . "{$lang['tlogin_mess3']}" . "{$lang['tlogin_mess4']}" . htmlsafechars($ip) . "{$lang['tlogin_mess5']}";
$sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', " . sqlesc($to) . ", " . sqlesc($msg) . ", " . sqlesc($subject) . ", $added);";
$res = sql_query("SET SESSION sql_mode = ''", $sql) or sqlerr(__FILE__, __LINE__);
$cache->delete('inbox_new::' . $row['id']);
$cache->delete('inbox_new_sb::' . $row['id']);
bark("<b>{$lang['gl_error']}</b>{$lang['tlogin_forgot']}");
}
*/
if ($row['enabled'] == 'no') bark($lang['tlogin_disabled']);
sql_query("DELETE FROM failedlogins WHERE ip = $ip_escaped");
$userid = (int)$row["id"];
$row['perms'] = (int)$row['perms'];
//== Start ip logger - Melvinmeow, Mindless, pdq
$no_log_ip = ($row['perms'] & bt_options::PERMS_NO_IP);
if ($no_log_ip) {
    $ip = '127.0.0.1';
    $ip_escaped = sqlesc($ip);
}
if (!$no_log_ip) {
    $res = sql_query("SELECT * FROM ips WHERE ip=$ip_escaped AND userid =" . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
    if ($res->num_row() == 0) {
        sql_query("INSERT INTO ips (userid, ip, lastlogin, type) VALUES (" . sqlesc($userid) . ", $ip_escaped , $added, 'Login')") or sqlerr(__FILE__, __LINE__);
        $cache->delete('ip_history_' . $userid);
    } else {
        sql_query("UPDATE ips SET lastlogin=$added WHERE ip=$ip_escaped AND userid=" . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
        $cache->delete('ip_history_' . $userid);
    }
} // End Ip logger
// output browser
//$u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
//$ua = get_browser($u_agent, true);
//$browser = "Browser: " . $ua['browser'] . " " . $ua['version'] . ". Os: " . $ua['platform'];
$ua = getBrowser();
$browser = "Browser: " . $ua['name'] . " " . $ua['version'] . ". Os: " . $ua['platform'];
sql_query('UPDATE users SET browser=' . sqlesc($browser) . ', ip = ' . $ip_escaped . ', last_access=' . TIME_NOW . ', last_login=' . TIME_NOW . ' WHERE id=' . sqlesc($row['id'])) or sqlerr(__FILE__, __LINE__);
$cache->update_row($keys['my_userid'] . $row['id'], [
    'browser' => $browser,
    'ip' => $ip,
    'last_access' => TIME_NOW,
    'last_login' => TIME_NOW
], $TRINITY20['expires']['curuser']);
$cache->update_row('user' . $row['id'], [
    'browser' => $browser,
    'ip' => $ip,
    'last_access' => TIME_NOW,
    'last_login' => TIME_NOW
], $TRINITY20['expires']['user_cache']);
//$passh = hash("ripemd160", "" . $row["passhash"] . $_SERVER["REMOTE_ADDR"] . "");
$passh = hash("sha3-512", "" . $row["passhash"] . $_SERVER["REMOTE_ADDR"] . "");
/*=== for dupe account ===*/
$uid = $row['id'];
$hashlog = make_hash_log($uid, $passh);
if((empty($row['loginhash'])) || ($row['loginhash'] != $hashlog)){
	sql_query('UPDATE users SET loginhash=' . sqlesc($hashlog) . ' WHERE id=' . sqlesc($uid))or sqlerr(__FILE__, __LINE__);
	$a = (mysqli_fetch_row(sql_query("SELECT COUNT(id) FROM doublesignup WHERE userid=" . sqlesc($uid)))) or sqlerr(__FILE__, __LINE__);
    if ($a[0] != 0){
        sql_query('UPDATE doublesignup SET login_hash=' . sqlesc($hashlog) . ' WHERE userid=' . sqlesc($uid))or sqlerr(__FILE__, __LINE__);
    }		
}
/*=== ===*/
logincookie($row['id'], $passh);
header("Location: {$TRINITY20['baseurl']}/index.php");
?>
