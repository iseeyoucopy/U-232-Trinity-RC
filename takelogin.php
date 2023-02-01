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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'password_functions.php');
require_once(CLASS_DIR.'page_verify.php');
require_once(CLASS_DIR.'class_browser.php');
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
$auth_key = [
    '2d257f64005d740db092a6b91170ab5f',
];
$gotkey = isset($_POST['key']) && strlen($_POST['key']) == 128 && in_array($_POST['key'], $auth_key);
if (!$gotkey) {
    $newpage = new page_verify();
    $newpage->check('takelogin');
}
$lang = array_merge(load_language('global'), load_language('takelogin'));
// 09 failed logins thanks to pdq - Retro
function failedloginscheck()
{
    global $TRINITY20, $lang;
    $total = 0;
    $ip = getip();
    ($res = sql_query("SELECT SUM(attempts) FROM failedlogins WHERE ip=".sqlesc($ip))) || sqlerr(__FILE__, __LINE__);
    [$total] = $res->fetch_row();
    if ($total >= $TRINITY20['failedlogins']) {
        sql_query("UPDATE failedlogins SET banned = 'yes' WHERE ip=".sqlesc($ip)) || sqlerr(__FILE__, __LINE__);
        stderr($lang['tlogin_locked'], "{$lang['tlogin_lockerr1']} . <b>(".htmlsafechars($ip).")</b> . {$lang['tlogin_lockerr2']}");
    }
} // End
if (!mkglobal('username:password'.($TRINITY20['captcha_on'] ? ($gotkey ? ":" : ":captchaSelection:") : ":").'submitme')) {
    die("{$lang['tlogin_sww']}");
}
if (!$submitme) {
    stderr($lang['tlogin_err1'], $lang['tlogin_err2']);
}
if ($TRINITY20['captcha_on'] && !$gotkey && (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection)) {
    header('Location: login.php');
    exit();
}
function bark($text = 'Username or password incorrect')
{
    global $lang, $TRINITY20, $cache, $cache_keys;
    $sha = sha1($_SERVER['REMOTE_ADDR']);
    $flood = $cache->get($cache_keys['dictbreaker'].$sha);
    if ($flood === false) {
        $cache->set($cache_keys['dictbreaker'].$sha, 'flood_check', 20);
    } else {
        die("{$lang['tlogin_err4']}");
    }
    stderr($lang['tlogin_failed'], $text);
}

failedloginscheck();
$res = sql_query("SELECT * FROM users WHERE username = ".sqlesc($username)." AND status = 'confirmed'");
$row = $res->fetch_assoc();
$ip_escaped = sqlesc(getip());
$ip = getip();
$added = TIME_NOW;
if (!$row) {
    ($fail_query = sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped")) || sqlerr(__FILE__, __LINE__);
    $fail = $fail_query->fetch_row();
    if ($fail[0] == 0) {
        sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") || sqlerr(__FILE__, __LINE__);
    }
    bark();
}
$hash1 = t_Hash($row['email'], $row['username'], $row['added']);
$hash2 = t_Hash($row['birthday'], $row['secret'], $row['pin_code']);
$tri_hash = password_verify($hash1.hash("ripemd160", $password).$hash2, $row['passhash']);
$pass_hash = password_verify($password, $row['passhash']);

/*=== check for dupe account by GodFather ===*/
if ($TRINITY20['dupeaccount_check_on'] == 1 && !empty(get_mycookie('log_uid'))) {
    $check = sql_query("SELECT * FROM users WHERE loginhash=".sqlesc(get_mycookie('log_uid')));
    $check_class = $check->fetch_assoc();
    if (($row['class'] < UC_SYSOP) && ($check_class['class'] < UC_SYSOP)) {
        if ((!empty($row['loginhash'])) && (get_mycookie('log_uid') == $row['loginhash']) && ($pass_hash || $tri_hash)) {
        } elseif ((!empty($row['loginhash'])) && (get_mycookie('log_uid') != $row['loginhash']) && ($pass_hash || $tri_hash)) {
            $a = sql_query("SELECT * FROM users WHERE loginhash=".sqlesc(get_mycookie('log_uid')));
            if ($r = $a->fetch_assoc()) {
                $message = "User ".$r['username']." has logged in on another account. Has logged with User: ".$username." credentials.";
                write_log("User ".$r['username']." has logged in on another account. Has logged with User: ".$username." credentials.");
                sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (".sqlesc($TRINITY20['bot_id']).",".sqlesc($TRINITY20['bot_name']).",".sqlesc($TRINITY20['bot_role']).",'4',".sqlesc(TIME_DATE).",".sqlesc($_SERVER['REMOTE_ADDR']).",".sqlesc($message).")") || sqlerr(__FILE__,
                    __LINE__);
            }
        } elseif ((!empty($row['loginhash'])) && (get_mycookie('log_uid') != $row['loginhash']) && (!$pass_hash || !$tri_hash)) {
            $b = sql_query("SELECT * FROM users WHERE loginhash=".sqlesc(get_mycookie('log_uid')));
            if ($s = $b->fetch_assoc()) {
                $message = "User ".$s['username']." has tried to login on another account. Has tried to login with User: ".$username." credentials.";
                write_log("User ".$s['username']." has tried to login on another account. Has tried to login with User: ".$username." credentials.");
                sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (".sqlesc($TRINITY20['bot_id']).",".sqlesc($TRINITY20['bot_name']).",".sqlesc($TRINITY20['bot_role']).",'4',".sqlesc(TIME_DATE).",".sqlesc($_SERVER['REMOTE_ADDR']).",".sqlesc($message).")") || sqlerr(__FILE__,
                    __LINE__);
            }
        }
    }
}
if (!$pass_hash && !$tri_hash) {
    ($fail_query = sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped")) || sqlerr(__FILE__, __LINE__);
    $fail = $fail_query->fetch_row();
    if ($fail[0] == 0) {
        sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") || sqlerr(__FILE__, __LINE__);
    }
    $to = ((int)$row["id"]);
    $subject = "{$lang['tlogin_log_err1']}";
    $msg = "[color=red]{$lang['tlogin_log_err2']}[/color]\n{$lang['tlogin_mess1']}".(int)$row['id']."{$lang['tlogin_mess2']}".htmlsafechars($username)."{$lang['tlogin_mess3']}"."{$lang['tlogin_mess4']}".htmlsafechars($ip)."{$lang['tlogin_mess5']}";
    $sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', ".sqlesc($to).", ".sqlesc($msg).", ".sqlesc($subject).", $added);";
    ($res = sql_query("SET SESSION sql_mode = ''", $sql)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($cache_keys['inbox_new'].$row['id']);
    $cache->delete($cache_keys['inbox_new_sb'].$row['id']);
    bark("<b>{$lang['gl_error']}</b>{$lang['tlogin_forgot']}");
} elseif ($pass_hash && !$tri_hash) {
    $secret = mksecret();
    $hash_1 = t_Hash($row['email'], $row['username'], $row['added']);
    $hash_2 = t_Hash($row['birthday'], $secret, $row['pin_code']);
    $hash3 = t_Hash($row['birthday'], $row['username'], $row['email']);
    $newpassword = make_passhash($hash_1, hash("ripemd160", $password), $hash_2);
    sql_query("UPDATE users SET passhash=".sqlesc($newpassword).", secret=".sqlesc($secret).", hash3=".sqlesc($hash3)." WHERE username = ".sqlesc($username));
}

$ress = sql_query("SELECT * FROM users WHERE username = ".sqlesc($username)." AND status = 'confirmed'");
$rows = $ress->fetch_assoc();
$ip_escaped = sqlesc(getip());
$ip = getip();
$added = TIME_NOW;
if (!$rows) {
    ($fail_query = sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped")) || sqlerr(__FILE__, __LINE__);
    $fail = $fail_query->fetch_row;
    if ($fail[0] == 0) {
        sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") || sqlerr(__FILE__, __LINE__);
    }
    bark();
}
$hash1 = t_Hash($rows['email'], $rows['username'], $rows['added']);
$hash2 = t_Hash($rows['birthday'], $rows['secret'], $rows['pin_code']);
$tri_hash = password_verify($hash1.hash("ripemd160", $password).$hash2, $rows['passhash']);
if (!$tri_hash) {
    ($fail_query = sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped")) || sqlerr(__FILE__, __LINE__);
    $fail = $fail_query->fetch_row();
    if ($fail[0] == 0) {
        sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") || sqlerr(__FILE__, __LINE__);
    }
    $to = ((int)$rows["id"]);
    $subject = "{$lang['tlogin_log_err1']}";
    $msg = "[color=red]{$lang['tlogin_log_err2']}[/color]\n{$lang['tlogin_mess1']}".(int)$rows['id']."{$lang['tlogin_mess2']}".htmlsafechars($username)."{$lang['tlogin_mess3']}"."{$lang['tlogin_mess4']}".htmlsafechars($ip)."{$lang['tlogin_mess5']}";
    $sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', ".sqlesc($to).", ".sqlesc($msg).", ".sqlesc($subject).", $added);";
    ($res = sql_query("SET SESSION sql_mode = ''", $sql)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($cache_keys['inbox_new'].$rows['id']);
    $cache->delete($cache_keys['inbox_new_sb'].$rows['id']);
    bark("<b>{$lang['gl_error']}</b>{$lang['tlogin_forgot']}");
}

if ($rows['enabled'] == 'no') {
    bark($lang['tlogin_disabled']);
}
sql_query("DELETE FROM failedlogins WHERE ip = $ip_escaped");
$userid = (int)$rows["id"];
$rows['perms'] = (int)$rows['perms'];
//== Start ip logger - Melvinmeow, Mindless, pdq
$no_log_ip = ($rows['perms'] & bt_options::PERMS_NO_IP);
if ($no_log_ip !== 0) {
    $ip = '127.0.0.1';
    $ip_escaped = sqlesc($ip);
}
if ($no_log_ip === 0) {
    ($res = sql_query("SELECT * FROM ips WHERE ip=$ip_escaped AND userid =".sqlesc($userid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) {
        sql_query("INSERT INTO ips (userid, ip, lastlogin, type) VALUES (".sqlesc($userid).", $ip_escaped , $added, 'Login')") || sqlerr(__FILE__,
            __LINE__);
        $cache->delete($cache_keys['ip_history'].$userid);
    } else {
        sql_query("UPDATE ips SET lastlogin=$added WHERE ip=$ip_escaped AND userid=".sqlesc($userid)) || sqlerr(__FILE__, __LINE__);
        $cache->delete($cache_keys['ip_history'].$userid);
    }
}
$ua = getBrowser();
$browser = "Browser: ".$ua['name']." ".$ua['version'].". Os: ".$ua['platform'];
sql_query('UPDATE users SET browser='.sqlesc($browser).', ip = '.$ip_escaped.', last_access='.TIME_NOW.', last_login='.TIME_NOW.' WHERE id='.sqlesc($rows['id'])) || sqlerr(__FILE__,
    __LINE__);
$cache->update_row($cache_keys['my_userid'].$rows['id'], [
    'browser' => $browser,
    'ip' => $ip,
    'last_access' => TIME_NOW,
    'last_login' => TIME_NOW,
], $TRINITY20['expires']['curuser']);
$cache->update_row($cache_keys['user'].$rows['id'], [
    'browser' => $browser,
    'ip' => $ip,
    'last_access' => TIME_NOW,
    'last_login' => TIME_NOW,
], $TRINITY20['expires']['user_cache']);
$passh = h_cook($rows["hash3"], $_SERVER["REMOTE_ADDR"], $rows["id"]);
/*=== for dupe account ===*/
$hashlog = make_hash_log($rows['id'], $passh);
if ((empty($rows['loginhash'])) || ($rows['loginhash'] != $hashlog)) {
    sql_query('UPDATE users SET loginhash='.sqlesc($hashlog).' WHERE id='.sqlesc($rows['id'])) || sqlerr(__FILE__, __LINE__);
    ($a_query = sql_query("SELECT COUNT(id) FROM doublesignup WHERE userid=".sqlesc($rows['id']))) || sqlerr(__FILE__, __LINE__);
    $a = $a_query->fetch_row();
    if ($a[0] != 0) {
        sql_query('UPDATE doublesignup SET login_hash='.sqlesc($hashlog).' WHERE userid='.sqlesc($rows['id'])) || sqlerr(__FILE__, __LINE__);
    }
}
/*=== ===*/
logincookie($rows['id'], $passh);
header("Location: {$TRINITY20['baseurl']}/index.php");
?>
